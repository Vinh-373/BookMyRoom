<?php
class Controller1 {

    /**
     * 1. Nạp Model (Tương tác Database)
     * Sử dụng APPROOT để trỏ thẳng vào thư mục models
     */
    public function model($model) {
        $file = APPROOT . '/models/partner/' . $model . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return new $model();
        }
        
        die("Lỗi hệ thống: Model " . $model . " không tìm thấy tại " . $file);
    }

    /**
     * 2. Nạp Service (Xử lý logic nghiệp vụ)
     */
    public function service($service) {
        $file = APPROOT . '/services/partner/' . $service . '.php';

        if (file_exists($file)) {
            require_once $file;
            return new $service();
        }
        
        die("Lỗi hệ thống: Service " . $service . " không tìm thấy tại " . $file);
    }

    /**
     * 3. Nạp View (Hiển thị giao diện cho Partner)
     * Tự động sử dụng Master Layout để bọc nội dung trang con
     */
    public function viewPartner($view, $data = []) {
        $viewFile = APPROOT . '/views/partner/pages/' . $view . '.php';
        $masterFile = APPROOT . '/views/partner/layouts/master.php';

        if (file_exists($viewFile)) {
            // Chuyển mảng dữ liệu thành các biến tự do (VD: $data['hotels'] thành $hotels)
            extract($data);

            // Sử dụng Output Buffering để bắt nội dung trang con vào biến $content
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();

            // Nạp Master Layout (Nơi sẽ echo $content)
            if (file_exists($masterFile)) {
                require_once $masterFile;
            } else {
                die("Lỗi hệ thống: Master Layout không tồn tại.");
            }
        } else {
            die("Lỗi hệ thống: View " . $view . " không tìm thấy tại " . $viewFile);
        }
    }
}