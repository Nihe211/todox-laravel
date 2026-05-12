# 📝 TodoX - Hệ thống Quản lý Công việc

![TodoX Banner](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) ![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white) ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

Một ứng dụng web quản lý công việc (To-Do List) tối giản, mượt mà được xây dựng theo mô hình MVC bằng framework **Laravel** và giao diện **Bootstrap 5**.

🔗 **Live Demo:** [https://thanh-hien211.infinityfree.me/](https://thanh-hien211.infinityfree.me/)

👤 **Sinh viên thực hiện:** Nguyễn Thanh Hiển - K2022 - Khoa Hệ thống Thông tin (UIT)

---

## ✨ Tính năng nổi bật

Dự án tập trung vào trải nghiệm cốt lõi của một hệ thống quản lý tác vụ cá nhân:

* **Quản lý Công việc (CRUD):** Thêm mới và xóa các công việc nhanh chóng.
* **Theo dõi Trạng thái:** Đánh dấu công việc đã hoàn thành (tự động ghi nhận và hiển thị thời gian `completedAt` chính xác theo múi giờ Việt Nam).
* **Bộ lọc Linh hoạt:**
    * Lọc theo trạng thái: *Đang làm (Active)*, *Hoàn thành (Done)*.
    * Lọc theo thời gian: *Hôm nay*, *Tuần này*, *Tháng này*, *Tất cả*.
* **Trải nghiệm người dùng (UX):**
    * Giao diện hiển thị thay đổi linh hoạt (Empty States) khi danh sách trống, đưa ra các thông báo khích lệ hoặc nhắc nhở phù hợp.
    * Thanh phân trang (Pagination) tích hợp sẵn giúp hệ thống load mượt mà dù có hàng nghìn công việc.
* **Thiết kế Responsive:** Giao diện tối ưu hiển thị tốt trên cả giao diện Máy tính và Điện thoại.

---

## 🛠️ Công nghệ sử dụng

* **Backend:** PHP 8.x, Laravel Framework (Routing, Eloquent ORM, Blade Template).
* **Frontend:** HTML5, CSS3, Bootstrap 5 (Sử dụng qua CDN để tối ưu hóa dung lượng dự án), Bootstrap Icons.
* **Database:** MySQL.
* **Deployment:** InfinityFree (Shared Hosting).

---

## 🚀 Hướng dẫn cài đặt tại Local

Nếu bạn muốn chạy thử dự án này trên máy tính cá nhân, hãy làm theo các bước sau:

**1. Clone dự án về máy:**
```bash
git clone <đường-link-github-của-bạn>
cd todox-app
2. Cài đặt các thư viện PHP:

Bash
composer install
3. Cấu hình môi trường (Database):

Copy file .env.example thành file .env:

Bash
cp .env.example .env
Mở file .env lên và điền thông tin Database ở máy bạn (ví dụ dùng XAMPP):

Đoạn mã
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todox_db
DB_USERNAME=root
DB_PASSWORD=
4. Khởi tạo Key bảo mật và Tạo bảng Database:

Bash
php artisan key:generate
php artisan migrate
5. Chạy server Laravel:

Bash
php artisan serve
Truy cập vào http://127.0.0.1:8000 trên trình duyệt để sử dụng hệ thống.

📂 Cấu trúc dự án (Core)
Những file xử lý logic cốt lõi của dự án nằm tại:

Controller: app/Http/Controllers/TaskController.php (Xử lý logic thêm, xóa, cập nhật trạng thái và các bộ lọc thời gian).

Model: app/Models/Task.php (Giao tiếp với bảng tasks trong database).

View: resources/views/tasks/index.blade.php (Giao diện chính chứa toàn bộ HTML và logic hiển thị của Bootstrap 5).

© 2026 Todo_app | Nguyễn Thanh Hiển.
