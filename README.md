# HiShoe Store

HiShoe Store là dự án website bán giày thể thao, xây dựng bằng Laravel 12.

## Tính năng chính

- Quản lý sản phẩm giày thể thao đa dạng (chạy bộ, bóng đá, bóng rổ, thời trang, v.v.)
- Quản lý danh mục, màu sắc, kích cỡ sản phẩm
- Trang khách hàng: xem sản phẩm, tìm kiếm, liên hệ, đặt hàng
- Trang quản trị: quản lý sản phẩm, đơn hàng, khách hàng
- Giao diện hiện đại, responsive

## Công nghệ sử dụng

- [Laravel 12](https://laravel.com/)
- Bootstrap 4
- MySQL

## Yêu cầu hệ thống

- Apache 2.4.x trở lên
- PHP 8.2.x đến 8.4.x
- mysqli-ext hoặc pdo-mysql-ext
- MySQL 5.7 hoặc MariaDB 10.3 trở lên

## Cài đặt & chạy dự án

1. Clone source code:
    ```sh
    git clone https://github.com/hongson2k4/HiShoe-Store.git
    cd HiShoe-Store
    ```

2. Cài đặt các package PHP:
    ```sh
    composer install
    ```

3. Tạo file `.env` và cấu hình database:
    ```sh
    cp .env.example .env
    # Chỉnh sửa thông tin DB trong .env
    # Cấu hình thanh toán vnpay (thanh toán online)
    # Cấu hình mật khẩu ứng dụng cho gmail
    # Cấu hình auth đăng nhập google
    ```

4. Import tệp sql vào MySQL database được cấu hình trong .env

5. Generate key:
    ```sh
    php artisan key:generate
    ```

6. Khởi động server:
    ```sh
    php artisan serve
    ```

7. Truy cập website tại [http://localhost:8000](http://localhost:8000)

Dự án đang được thử nghiệm tại https://hishoe.datahihi1.id.vn