import tkinter as tk
from tkinter import messagebox

def register():
    name = name_entry.get()
    if name:
        register_face(name)
        messagebox.showinfo("Thành công", f"Đã đăng ký khuôn mặt cho {name}")
    else:
        messagebox.showwarning("Lỗi", "Vui lòng nhập tên")

def authenticate():
    authenticate_face()

root = tk.Tk()
root.title("Face Authentication")

tk.Label(root, text="Nhập tên để đăng ký khuôn mặt:").pack()
name_entry = tk.Entry(root)
name_entry.pack()

tk.Button(root, text="Đăng ký", command=register).pack(pady=10)
tk.Button(root, text="Xác thực", command=authenticate).pack(pady=10)

root.mainloop()
