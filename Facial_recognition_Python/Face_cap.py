import cv2
import os

# Đường dẫn tới thư mục lưu trữ ảnh
save_path = 'employees'
if not os.path.exists(save_path):
    os.makedirs(save_path)

# Khởi tạo camera
cap = cv2.VideoCapture(0)

print("Nhấn 's' để chụp và lưu ảnh, 'q' để thoát.")

while True:
    success, img = cap.read()
    cv2.imshow('Camera', img)

    key = cv2.waitKey(1)
    
    # Nhấn phím 's' để chụp ảnh
    if key & 0xFF == ord('s'):
        name = input("Nhập tên cho ảnh này: ")
        file_name = os.path.join(save_path, f'{name}.jpg')
        cv2.imwrite(file_name, img)
        print(f"Ảnh đã được lưu vào {file_name}")

    # Nhấn phím 'q' để thoát
    elif key & 0xFF == ord('q'):
        break

# Giải phóng camera và đóng cửa sổ
cap.release()
cv2.destroyAllWindows()
