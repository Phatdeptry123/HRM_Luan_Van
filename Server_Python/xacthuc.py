import cv2
import face_recognition
import numpy as np
import os
import tkinter as tk
import threading
import time
import requests  # Import thư viện requests

# Đọc encoding từ file đã lưu
def load_registered_faces():
    registered_faces = {}
    for filename in os.listdir('faces'):
        if filename.endswith('.txt'):
            name = filename.split('.')[0]
            with open(f'faces/{filename}', 'r') as f:
                encoding = np.array(eval(f.read()))
                registered_faces[name] = encoding
    return registered_faces

# Gửi yêu cầu check-in hoặc check-out đến server API trong luồng riêng
def send_attendance_request(name, status):
    def send_request():
        url = f"http://localhost:8000/api/attendance/{name}"  # Truyền username vào URL
        data = {
            "notes": f"{status} attendance"  # Ghi chú có thể tùy chỉnh
        }
        try:
            response = requests.post(url, json=data)
            if response.status_code == 200:
                print(f"{response.json()['message']} cho {name}")
            elif response.status_code == 500:
                print(f"Không thể checkin/checkout cho {name}")
                os._exit(1)
            else:
                print(f"Yêu cầu thất bại với mã lỗi {response.status_code}")
        except Exception as e:
            print(f"Lỗi khi gửi yêu cầu: {e}")

    # Tạo luồng mới cho việc gửi request
    threading.Thread(target=send_request).start()

# Xử lý khung hình trong một luồng riêng
def process_frame(frame, registered_faces):
    face_encodings = face_recognition.face_encodings(frame)

    face_names = []
    for face_encoding in face_encodings:
        matches = face_recognition.compare_faces(list(registered_faces.values()), face_encoding)
        face_distances = face_recognition.face_distance(list(registered_faces.values()), face_encoding)
        best_match_index = np.argmin(face_distances)

        if matches[best_match_index]:
            name = list(registered_faces.keys())[best_match_index]
            face_names.append(name)
        else:
            face_names.append("Unknown")
    
    return face_names

# Xác thực khuôn mặt trong luồng riêng
def authenticate_face():
    registered_faces = load_registered_faces()

    if not registered_faces:
        print("Không có khuôn mặt nào đã được đăng ký.")
        return

    video_capture = cv2.VideoCapture(0)

    print("Đang xác thực khuôn mặt, vui lòng nhìn vào camera...")

    process_interval = 2  # Mỗi 2 giây mới xử lý khung hình một lần
    last_process_time = time.time()

    # Biến lưu thời gian hiển thị thông điệp chào mừng
    welcome_display_time = {}
    attendance_status = {}  # Lưu trạng thái attendance cho mỗi người dùng

    while True:
        ret, frame = video_capture.read()
        if not ret:
            print("Không thể lấy ảnh từ camera.")
            break

        current_time = time.time()
        if current_time - last_process_time > process_interval:
            # Thay đổi kích thước khung hình để giảm độ phân giải
            small_frame = cv2.resize(frame, (0, 0), fx=0.5, fy=0.5)

            # Xử lý khung hình và trả về tên của khuôn mặt
            face_names = process_frame(small_frame, registered_faces)
            current_time_str = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
            print(f'[{current_time_str}] Khuôn mặt:', face_names)

            for name in face_names:
                if name != "Unknown":
                    # Kiểm tra và gửi yêu cầu check-in hoặc check-out
                    if name not in attendance_status:  # Check-in lần đầu
                        attendance_status[name] = 'checkin'
                        send_attendance_request(name, 'checkin')
                    else:
                        attendance_status[name] = 'checkout'
                        send_attendance_request(name, 'checkout')

                    # Lưu thời gian bắt đầu hiển thị thông điệp
                    welcome_display_time[name] = time.time()

            last_process_time = current_time

        # Kiểm tra và hiển thị thông điệp chào mừng nếu thời gian hiển thị chưa quá 2 giây
        for name, start_time in list(welcome_display_time.items()):
            if current_time - start_time < 2:
                welcome_text = f"Welcome {name}!"
                # Hiển thị văn bản "Welcome + name" giữa khung hình
                cv2.putText(frame, welcome_text, (50, 50), cv2.FONT_HERSHEY_DUPLEX, 1.0, (0, 255, 0), 2)
            else:
                # Xóa tên khỏi từ điển sau 2 giây
                del welcome_display_time[name]

        # Hiển thị khung hình
        cv2.imshow('Video', frame)

        # Thoát khi nhấn phím 'q'
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    video_capture.release()
    cv2.destroyAllWindows()

# Hàm chạy trong luồng riêng
def authenticate_in_thread():
    threading.Thread(target=authenticate_face).start()

# Tạo giao diện người dùng với tkinter
root = tk.Tk()
root.title("Ứng dụng Xác Thực Khuôn Mặt")

# Tạo nút để bắt đầu xác thực khuôn mặt
authenticate_button = tk.Button(root, text="Xác thực khuôn mặt", command=authenticate_in_thread)
authenticate_button.pack(pady=20)

# Chạy ứng dụng tkinter
root.mainloop()
