<?php
class App {
    protected $controller = "PartnerController"; // Controller mặc định
    protected $action = "index";
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();
        
        // Nạp file routes sử dụng APPROOT
        $routes = require_once APPROOT . '/routes/web.php';
        $path = '';

        // Xử lý xác định Path từ URL để khớp với Route
        if (isset($url[0])) {
            $path = $url[0];
            if (isset($url[1]) && array_key_exists($url[0].'/'.$url[1], $routes)) {
                $path = $url[0].'/'.$url[1];
                unset($url[1]); 
            }
        }

        // 1. KIỂM TRA TRONG ROUTES TRƯỚC (Explicit Routing)
        if (array_key_exists($path, $routes)) {
            $this->controller = $routes[$path][0];
            $this->action = $routes[$path][1];
            unset($url[0]);
            
            // Tìm và nạp file Controller
            if (file_exists(APPROOT . "/controllers/partner/" . $this->controller . ".php")) {
                require_once APPROOT . "/controllers/partner/" . $this->controller . ".php";
            } else {
                require_once APPROOT . "/controllers/" . $this->controller . ".php";
            }
        } 
        // 2. NẾU KHÔNG CÓ TRONG ROUTES -> DÙNG AUTO-ROUTING
        else if (!empty($url[0])) {
            $name = ucfirst($url[0]) . "Controller";
            
            if (file_exists(APPROOT . "/controllers/partner/" . $name . ".php")) {
                $this->controller = $name;
                require_once APPROOT . "/controllers/partner/" . $this->controller . ".php";
                unset($url[0]);
            } elseif (file_exists(APPROOT . "/controllers/" . $name . ".php")) {
                $this->controller = $name;
                require_once APPROOT . "/controllers/" . $this->controller . ".php";
                unset($url[0]);
            } else {
                // Nếu không tìm thấy controller nào khớp, nạp controller mặc định
                require_once APPROOT . "/controllers/" . $this->controller . ".php";
            }
        } else {
            // Trường hợp URL trống (Trang chủ)
            require_once APPROOT . "/controllers/" . $this->controller . ".php";
        }

        // Khởi tạo Object Controller
        if (!class_exists($this->controller)) {
            die("Lỗi: Controller {$this->controller} không tồn tại.");
        }
        $this->controller = new $this->controller;

        // 3. XỬ LÝ ACTION
        // Reset lại index của mảng $url để lấy action dễ hơn
        $url = array_values($url);
        
        // Ưu tiên action từ Route, nếu không có mới lấy từ URL
        if (isset($url[0]) && method_exists($this->controller, $url[0])) {
            $this->action = $url[0];
            unset($url[0]);
        }

        // 4. XỬ LÝ PARAMS
        $this->params = $url ? array_values($url) : [];

        // THỰC THI
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}