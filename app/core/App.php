<?php
class App {
    protected $controller = ""; 
    protected $action = "index";
    protected $params = [];

    public function __construct() {
        if($_SESSION['user_role']=='Partner'){
            $this->controller = "PartnerController";
            $this->action = "index";
            //Di chuyển toàn bộ đoạn bên dưới lên đây khi gộp file

        }

        $url = $this->parseUrl();
        $routes = require_once "../app/routes/web.php";
        $path = '';
        if (isset($url[0])) {
            $path = $url[0];
            if (isset($url[1]) && array_key_exists($url[0].'/'.$url[1], $routes)) {
                $path = $url[0].'/'.$url[1];
                unset($url[1]); // Xóa phần tử thứ 2 vì nó là một phần của Route key
            }
        }

        // 1. KIỂM TRA TRONG ROUTES TRƯỚC (Explicit Routing)
        if (array_key_exists($path, $routes)) {
            $this->controller = $routes[$path][0];
            $this->action = $routes[$path][1];
            unset($url[0]);
            
            // Tìm file ở cả 2 thư mục
            if (file_exists("../app/controllers/partner/" . $this->controller . ".php")) {
                require_once "../app/controllers/partner/" . $this->controller . ".php";
            } else {
                require_once "../app/controllers/" . $this->controller . ".php";
            }
        } 
        // 2. NẾU KHÔNG CÓ TRONG ROUTES -> DÙNG AUTO-ROUTING
        else if (isset($url[0])) {
            $name = ucfirst($url[0]) . "Controller";
            if (file_exists("../app/controllers/partner/" . $name . ".php")) {
                $this->controller = $name;
                require_once "../app/controllers/partner/" . $this->controller . ".php";
                unset($url[0]);
            } elseif (file_exists("../app/controllers/" . $name . ".php")) {
                $this->controller = $name;
                require_once "../app/controllers/" . $this->controller . ".php";
                unset($url[0]);
            } else {
                require_once "../app/controllers/" . $this->controller . ".php";
            }
        } else {
            require_once "../app/controllers/" . $this->controller . ".php";
        }

        // Khởi tạo Object Controller
        if (!class_exists($this->controller)) {
            die("Lỗi: Controller {$this->controller} không tồn tại.");
        }
        $this->controller = new $this->controller;

        // 2. XỬ LÝ ACTION (Reset chỉ số mảng)
        $url = array_values($url);
        if (isset($url[0]) && method_exists($this->controller, $url[0])) {
            $this->action = $url[0];
            unset($url[0]);
        }

        // 3. XỬ LÝ PARAMS
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