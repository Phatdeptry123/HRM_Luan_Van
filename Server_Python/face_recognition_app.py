import tkinter as tk
from tkinter import simpledialog, messagebox
import cv2
import face_recognition
import numpy as np
import os

# Hàm để lưu trữ encoding khuôn mặt vào file
def register_face(name):
    # Mở camera để lấy ảnh
    video_capture = cv2.VideoCapture(0)

    print("Đang ghi nhận khuôn mặt, vui lòng nhìn vào camera...")

    while True:
        ret, frame = video_capture.read()
        if not ret:
            print("Không thể lấy ảnh từ camera.")
            break
        
        cv2.imshow('Video', frame)

        # Nhấn 'q' để chụp và lưu khuôn mặt
        if cv2.waitKey(1) & 0xFF == ord('q'):
            face_locations = face_recognition.face_locations(frame)
            if len(face_locations) > 0:
                face_encodings = face_recognition.face_encodings(frame, face_locations)[0]
                if not os.path.exists('faces'):
                    os.makedirs('faces')
                with open(f'faces/{name}.txt', 'w') as f:
                    f.write(str(face_encodings.tolist()))
                print(f"Khuôn mặt đã được lưu với tên: {name}")
                messagebox.showinfo("Thành công", f"Khuôn mặt đã được lưu với tên: {name}")
                break
            else:
                print("Không phát hiện khuôn mặt, vui lòng thử lại.")

    video_capture.release()
    cv2.destroyAllWindows()

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

# Xử lý khung hình trong một luồng riêng
def process_frame(frame, registered_faces):
    face_locations = face_recognition.face_locations(frame)
    face_encodings = face_recognition.face_encodings(frame, face_locations)

    for face_encoding in face_encodings:
        matches = face_recognition.compare_faces(list(registered_faces.values()), face_encoding)
        face_distances = face_recognition.face_distance(list(registered_faces.values()), face_encoding)
        best_match_index = np.argmin(face_distances)

        if matches[best_match_index]:
            name = list(registered_faces.keys())[best_match_index]
            print(f"Xác thực thành công: {name}")
            return name
    return None

# Xác thực khuôn mặt
def authenticate_face():
    registered_faces = load_registered_faces()
    
    if not registered_faces:
        print("Không có khuôn mặt nào đã được đăng ký.")
        return 

    video_capture = cv2.VideoCapture(0)
    
    print("Đang xác thực khuôn mặt, vui lòng nhìn vào camera...")

    frame_interval = 5  # Số khung hình để bỏ qua
    frame_count = 0

    while True:
        ret, frame = video_capture.read()
        if not ret:
            print("Không thể lấy ảnh từ camera.")
            break

        frame_count += 1

        if frame_count % frame_interval == 0:
            # Thay đổi kích thước khung hình để giảm độ phân giải
            small_frame = cv2.resize(frame, (0, 0), fx=0.5, fy=0.5)
            
            # Xử lý khung hình trong một luồng riêng
            name = process_frame(small_frame, registered_faces)
            if name:
                messagebox.showinfo("Thành công", f"Khuôn mặt đã được xác thực: {name}")
                break
            else:
                print("Không nhận diện được khuôn mặt")

        cv2.imshow('Video', frame)

        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    video_capture.release()
    cv2.destroyAllWindows()

# Hàm để hiển thị hộp thoại nhập tên và bắt đầu đăng ký khuôn mặt
def start_registration():
    name = simpledialog.askstring("Đăng ký khuôn mặt", "Nhập tên bạn để đăng ký khuôn mặt:")
    if name:
        register_face(name)
    else:
        messagebox.showwarning("Cảnh báo", "Tên không được để trống.")

# Hàm để bắt đầu xác thực khuôn mặt
def start_authentication():
    authenticate_face()

# Tạo giao diện người dùng với tkinter
root = tk.Tk()
root.title("Ứng dụng Đăng Ký và Xác Thực Khuôn Mặt")

# Chuyển sang chế độ toàn màn hình
root.attributes('-fullscreen', True)

# Nút để thoát khỏi chế độ toàn màn hình
def quit_fullscreen(event=None):
    root.attributes('-fullscreen', False)

# Thêm sự kiện để thoát khỏi chế độ toàn màn hình khi nhấn phím Esc
root.bind("<Escape>", quit_fullscreen)

# Tạo nút để bắt đầu đăng ký khuôn mặt
register_button = tk.Button(root, text="Đăng ký khuôn mặt", command=start_registration, font=("Arial", 24))
register_button.pack(pady=50)

# Tạo nút để bắt đầu xác thực khuôn mặt
authenticate_button = tk.Button(root, text="Xác thực khuôn mặt", command=start_authentication, font=("Arial", 24))
authenticate_button.pack(pady=50)

# Chạy ứng dụng tkinter
root.mainloop()
