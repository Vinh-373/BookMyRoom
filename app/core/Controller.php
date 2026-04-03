<?php
/**
 * Base Controller Class
 * Cung cấp các phương thức để nạp Model, Service và View
 */
class Controller {

    // 1. Nạp Model (Tương tác Database cơ bản)
    public function model($model) {
        if (file_exists('../app/models/partner/' . $model . '.php')) {
            require_once '../app/models/partner/' . $model . '.php';
            return new $model();
        }
        die("Model " . $model . " không tồn tại.");
    }

    // 2. Nạp Service (Xử lý logic nghiệp vụ phức tạp)
    public function service($service) {
        if (file_exists('../app/services/partner/' . $service . '.php')) {
            require_once '../app/services/partner/' . $service . '.php';
            return new $service();
        }
        die("Service " . $service . " không tồn tại.");
    }

    // 3. Nạp View (Hiển thị giao diện cho Partner)
    // Tự động sử dụng Master Layout để bọc nội dung trang con
    public function viewPartner($view, $data = []) {
        if (file_exists('../app/views/partner/pages/' . $view . '.php')) {
            // Chuyển mảng dữ liệu thành các biến tự do (VD: $data['hotels'] thành $hotels)
            extract($data);

            // Sử dụng Output Buffering để bắt nội dung trang con vào biến $content
            ob_start();
            require_once '../app/views/partner/pages/' . $view . '.php';
            $content = ob_get_clean();

            // Nạp Master Layout (Nơi sẽ echo $content)
            require_once '../app/views/partner/layouts/master.php';
        } else {
            die("View " . $view . " không tồn tại.");
        }
    }
}