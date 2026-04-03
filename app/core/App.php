<?php
// define('BASE_URL', 'http://localhost/BookMyRoom/');

// define('URLROOT', 'http://localhost/BookMyRoom');

// class App {
//     protected $role = 'customer';
//     protected $controller = 'booking';
//     protected $method = 'index';
//     protected $params = [];
    
//     function __construct(){
//         // Xác định vai trò của user từ session
//         $this->role = $this->getRole();
        
//         $urlArr = $this->urlProcess();
        
//         // Nếu URL bắt đầu bằng role (partner, admin), lấy role từ URL
//         if (isset($urlArr[0]) && in_array($urlArr[0], ['partner', 'admin'])) {
//             $this->role = $urlArr[0];
//             unset($urlArr[0]);
//             $urlArr = array_values($urlArr);
//         }
        
//         // Kiểm tra quyền truy cập
//         if (!$this->checkPermission()) {
//             http_response_code(403);
//             die('Bạn không có quyền truy cập trang này');
//         }
        
//         // Lấy tên controller từ URL
//         if (isset($urlArr[0]) && !empty($urlArr[0])) {
//             $controllerName = $urlArr[0];
//             $controllerPath = './app/controllers/' . $this->role . '/' . $controllerName . '.php';
            
//             if (file_exists($controllerPath)) {
//                 $this->controller = $controllerName;
//                 unset($urlArr[0]);
//             }
//         } else {
//             // Controller mặc định theo role
//             $defaultControllers = [
//                 'customer' => 'booking',
//                 'partner' => 'hotel',
//                 'admin' => 'dashboard'
//             ];
//             $this->controller = $defaultControllers[$this->role];
//         }
        
//         // Tạo đường dẫn file controller (dùng đường dẫn nhất quán)
//         $controllerPath = './app/controllers/' . $this->role . '/' . $this->controller . '.php';
        
//         if (!file_exists($controllerPath)) {
//             http_response_code(404);
//             die('Controller không tồn tại: ' . $controllerPath);
//         }
        
//         // Require controller
//         require_once $controllerPath;
        
//         // Tạo namespace động (folder thường, class hoa)
//         $controllerClass = 'Controllers\\' . $this->role . '\\' . ucfirst($this->controller);
        
//         if (!class_exists($controllerClass)) {
//             http_response_code(500);
//             die('Class không tồn tại: ' . $controllerClass);
//         }
        
//         $this->controller = new $controllerClass();
        
//         // Lấy method từ URL
//         $urlArr = array_values($urlArr);
//         if (isset($urlArr[0]) && !empty($urlArr[0])) {
//             if (method_exists($this->controller, $urlArr[0])) {
//                 $this->method = $urlArr[0];
//                 unset($urlArr[0]);
//             }
//         }
        
//         // Lấy params
//         $this->params = $urlArr ? array_values($urlArr) : [];
        
//         // Gọi controller method với params
//         call_user_func_array([$this->controller, $this->method], $this->params);
//     }
    
//     /**
//      * Xác định vai trò của user từ session
//      * @return string
//      */
//     function getRole() {
//         if (isset($_SESSION['role'])) {
//             return $_SESSION['role'];
//         }
//         return 'customer'; // Default role
//     }
    
//     /**
//      * Kiểm tra quyền truy cập dựa trên role
//      * @return bool
//      */
//     function checkPermission() {
//         // Nếu chưa đăng nhập, chỉ cho truy cập trang public
//         if (!isset($_SESSION['user_id'])) {
//             return true;
//         }
        
//         // Kiểm tra user có quyền truy cập role này không
//         $userRole = $_SESSION['role'];
        
//         // Admin có thể truy cập tất cả
//         if ($userRole === 'admin') {
//             return true;
//         }
        
//         // User chỉ có thể truy cập route của role của mình
//         if ($userRole === $this->role) {
//             return true;
//         }
        
//         return false;
//     }
    
//     /**
//      * Xử lý URL thành mảng
//      * @return array
//      */
//     function urlProcess(){
//         if (isset($_GET['url'])) {
//             $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
//             if (!empty($url)) {
//                 return explode('/', $url);
//             }
//         }
//         return [];
//     }
// }

// class App {
//     protected $controller = "PartnerController"; // Controller mặc định
//     protected $action = "index";
//     protected $params = [];

//     public function __construct() {
//         $url = $this->parseUrl();
        
//         // Nạp file routes sử dụng APPROOT
//         $routes = require_once APPROOT . '/routes/web.php';
//         $path = '';

//         // Xử lý xác định Path từ URL để khớp với Route
//         if (isset($url[0])) {
//             $path = $url[0];
//             if (isset($url[1]) && array_key_exists($url[0].'/'.$url[1], $routes)) {
//                 $path = $url[0].'/'.$url[1];
//                 unset($url[1]); 
//             }
//         }

//         // 1. KIỂM TRA TRONG ROUTES TRƯỚC (Explicit Routing)
//         if (array_key_exists($path, $routes)) {
//             $this->controller = $routes[$path][0];
//             $this->action = $routes[$path][1];
//             unset($url[0]);
            
//             // Tìm và nạp file Controller
//             if (file_exists(APPROOT . "/controllers/partner/" . $this->controller . ".php")) {
//                 require_once APPROOT . "/controllers/partner/" . $this->controller . ".php";
//             } else {
//                 require_once APPROOT . "/controllers/" . $this->controller . ".php";
//             }
//         } 
//         // 2. NẾU KHÔNG CÓ TRONG ROUTES -> DÙNG AUTO-ROUTING
//         else if (!empty($url[0])) {
//             $name = ucfirst($url[0]) . "Controller";
            
//             if (file_exists(APPROOT . "/controllers/partner/" . $name . ".php")) {
//                 $this->controller = $name;
//                 require_once APPROOT . "/controllers/partner/" . $this->controller . ".php";
//                 unset($url[0]);
//             } elseif (file_exists(APPROOT . "/controllers/" . $name . ".php")) {
//                 $this->controller = $name;
//                 require_once APPROOT . "/controllers/" . $this->controller . ".php";
//                 unset($url[0]);
//             } else {
//                 // Nếu không tìm thấy controller nào khớp, nạp controller mặc định
//                 require_once APPROOT . "/controllers/" . $this->controller . ".php";
//             }
//         } else {
//             // Trường hợp URL trống (Trang chủ)
//             require_once APPROOT . "/controllers/" . $this->controller . ".php";
//         }

//         // Khởi tạo Object Controller
//         if (!class_exists($this->controller)) {
//             die("Lỗi: Controller {$this->controller} không tồn tại.");
//         }
//         $this->controller = new $this->controller;

//         // 3. XỬ LÝ ACTION
//         // Reset lại index của mảng $url để lấy action dễ hơn
//         $url = array_values($url);
        
//         // Ưu tiên action từ Route, nếu không có mới lấy từ URL
//         if (isset($url[0]) && method_exists($this->controller, $url[0])) {
//             $this->action = $url[0];
//             unset($url[0]);
//         }

//         // 4. XỬ LÝ PARAMS
//         $this->params = $url ? array_values($url) : [];

//         // THỰC THI
//         call_user_func_array([$this->controller, $this->action], $this->params);
//     }

//     private function parseUrl() {
//         if (isset($_GET['url'])) {
//             return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
//         }
//         return [];
//     }
// }



class App
{
    protected $role = 'customer';
    protected $controller = 'booking';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        session_start();

        $this->role = $this->getRole();
            // $this->runPartner();


        // 👉 Nếu là partner → dùng routing riêng
        if ($this->role === 'PARTNER') {
            $this->runPartner();
        } else {
            $this->runDefault();
        }
    }

    /* ================= PARTNER ================= */
    private function runPartner()
    {
        $this->controller = "PartnerController";
        $this->method = "index";

        $url = $this->urlProcess();
        $routes = require_once "./app/routes/web.php";

        $path = '';
        if (isset($url[0])) {
            $path = $url[0];

            if (isset($url[1]) && array_key_exists($url[0] . '/' . $url[1], $routes)) {
                $path = $url[0] . '/' . $url[1];
                unset($url[1]);
            }
        }

        // 1. Ưu tiên routes
        if (array_key_exists($path, $routes)) {
            $this->controller = $routes[$path][0];
            $this->method = $routes[$path][1];
            unset($url[0]);

            if (file_exists("./app/controllers/partner/" . $this->controller . ".php")) {
                require_once "./app/controllers/partner/" . $this->controller . ".php";
            } else {
                require_once "./app/controllers/" . $this->controller . ".php";
            }
        }
        // 2. Auto routing
        else if (isset($url[0])) {
            $name = ucfirst($url[0]) . "Controller";

            if (file_exists("./app/controllers/partner/" . $name . ".php")) {
                $this->controller = $name;
                require_once "./app/controllers/partner/" . $this->controller . ".php";
                unset($url[0]);
            } elseif (file_exists("./app/controllers/" . $name . ".php")) {
                $this->controller = $name;
                require_once "./app/controllers/" . $this->controller . ".php";
                unset($url[0]);
            } else {
                require_once "./app/controllers/" . $this->controller . ".php";
            }
        } else {
            require_once "./app/controllers/" . $this->controller . ".php";
        }

        // Khởi tạo controller
        if (!class_exists($this->controller)) {
            die("Controller không tồn tại: {$this->controller}");
        }

        $this->controller = new $this->controller;

        // method
        $url = array_values($url);
        if (isset($url[0]) && method_exists($this->controller, $url[0])) {
            $this->method = $url[0];
            unset($url[0]);
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /* ================= CUSTOMER + ADMIN ================= */
    private function runDefault()
    {
        $urlArr = $this->urlProcess();

        // lấy role từ URL
        if (isset($urlArr[0]) && in_array($urlArr[0], ['partner', 'admin'])) {
            $this->role = $urlArr[0];
            unset($urlArr[0]);
            $urlArr = array_values($urlArr);
        }

        if (!$this->checkPermission()) {
            http_response_code(403);
            die('Bạn không có quyền truy cập');
        }

        // controller
        if (isset($urlArr[0])) {
            $controllerName = $urlArr[0];
            $path = './app/controllers/' . $this->role . '/' . $controllerName . '.php';

            if (file_exists($path)) {
                $this->controller = $controllerName;
                unset($urlArr[0]);
            }
        } else {
            $default = [
                'customer' => 'booking',
                'admin' => 'dashboard'
            ];
            $this->controller = $default[$this->role];
        }

        $path = './app/controllers/' . $this->role . '/' . $this->controller . '.php';

        if (!file_exists($path)) {
            die('Controller không tồn tại');
        }

        require_once $path;

        $class = 'Controllers\\' . $this->role . '\\' . ucfirst($this->controller);

        if (!class_exists($class)) {
            die('Class không tồn tại');
        }

        $this->controller = new $class();

        // method
        $urlArr = array_values($urlArr);
        if (isset($urlArr[0]) && method_exists($this->controller, $urlArr[0])) {
            $this->method = $urlArr[0];
            unset($urlArr[0]);
        }

        $this->params = $urlArr ? array_values($urlArr) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /* ================= COMMON ================= */
    private function getRole()
    {
        return $_SESSION['user']['role'] ?? 'customer';
    }

    private function checkPermission()
    {
        if (!isset($_SESSION['user_id'])) return true;

        $userRole = $_SESSION['role'];

        if ($userRole === 'admin') return true;

        return $userRole === $this->role;
    }

    private function urlProcess()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}