import tkinter as tk
from tkinter import simpledialog, messagebox
import cv2
import face_recognition
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

# Hàm để hiển thị hộp thoại nhập tên và bắt đầu đăng ký khuôn mặt
def start_registration():
    name = simpledialog.askstring("Đăng ký khuôn mặt", "Nhập tên bạn để đăng ký khuôn mặt:")
    if name:
        register_face(name)
    else:
        messagebox.showwarning("Cảnh báo", "Tên không được để trống.")

# Tạo giao diện người dùng với tkinter
root = tk.Tk()
root.title("Đăng ký Khuôn mặt")

# Tạo nút để bắt đầu đăng ký khuôn mặt
register_button = tk.Button(root, text="Đăng ký khuôn mặt", command=start_registration)
register_button.pack(pady=20)

# Chạy ứng dụng tkinter
root.mainloop()
