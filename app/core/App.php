<?php
define('BASE_URL', 'http://localhost/BookMyRoom/');

define('URLROOT', 'http://localhost/BookMyRoom');

class App {
    protected $role = 'customer';
    protected $controller = 'booking';
    protected $method = 'index';
    protected $params = [];
    
    function __construct(){
        // Xác định vai trò của user từ session
        $this->role = $this->getRole();
        
        $urlArr = $this->urlProcess();
        
        // Nếu URL bắt đầu bằng role (partner, admin), lấy role từ URL
        if (isset($urlArr[0]) && in_array($urlArr[0], ['partner', 'admin'])) {
            $this->role = $urlArr[0];
            unset($urlArr[0]);
            $urlArr = array_values($urlArr);
        }
        
        // Kiểm tra quyền truy cập
        if (!$this->checkPermission()) {
            http_response_code(403);
            die('Bạn không có quyền truy cập trang này');
        }
        
        // Lấy tên controller từ URL
        if (isset($urlArr[0]) && !empty($urlArr[0])) {
            $controllerName = $urlArr[0];
            $controllerPath = './app/controllers/' . $this->role . '/' . $controllerName . '.php';
            
            if (file_exists($controllerPath)) {
                $this->controller = $controllerName;
                unset($urlArr[0]);
            }
        } else {
            // Controller mặc định theo role
            $defaultControllers = [
                'customer' => 'booking',
                'partner' => 'hotel',
                'admin' => 'dashboard'
            ];
            $this->controller = $defaultControllers[$this->role];
        }
        
        // Tạo đường dẫn file controller (dùng đường dẫn nhất quán)
        $controllerPath = './app/controllers/' . $this->role . '/' . $this->controller . '.php';
        
        if (!file_exists($controllerPath)) {
            http_response_code(404);
            die('Controller không tồn tại: ' . $controllerPath);
        }
        
        // Require controller
        require_once $controllerPath;
        
        // Tạo namespace động (folder thường, class hoa)
        $controllerClass = 'Controllers\\' . $this->role . '\\' . ucfirst($this->controller);
        
        if (!class_exists($controllerClass)) {
            http_response_code(500);
            die('Class không tồn tại: ' . $controllerClass);
        }
        
        $this->controller = new $controllerClass();
        
        // Lấy method từ URL
        $urlArr = array_values($urlArr);
        if (isset($urlArr[0]) && !empty($urlArr[0])) {
            if (method_exists($this->controller, $urlArr[0])) {
                $this->method = $urlArr[0];
                unset($urlArr[0]);
            }
        }
        
        // Lấy params
        $this->params = $urlArr ? array_values($urlArr) : [];
        
        // Gọi controller method với params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    /**
     * Xác định vai trò của user từ session
     * @return string
     */
    function getRole() {
        if (isset($_SESSION['role'])) {
            return $_SESSION['role'];
        }
        return 'customer'; // Default role
    }
    
    /**
     * Kiểm tra quyền truy cập dựa trên role
     * @return bool
     */
    function checkPermission() {
        // Nếu chưa đăng nhập, chỉ cho truy cập trang public
        if (!isset($_SESSION['user_id'])) {
            return true;
        }
        
        // Kiểm tra user có quyền truy cập role này không
        $userRole = $_SESSION['role'];
        
        // Admin có thể truy cập tất cả
        if ($userRole === 'admin') {
            return true;
        }
        
        // User chỉ có thể truy cập route của role của mình
        if ($userRole === $this->role) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Xử lý URL thành mảng
     * @return array
     */
    function urlProcess(){
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            if (!empty($url)) {
                return explode('/', $url);
            }
        }
        return [];
    }
}
?>