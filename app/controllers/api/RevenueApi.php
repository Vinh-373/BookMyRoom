<?php
namespace Controllers\api;

header('Content-Type: application/json; charset=utf-8');

if (!class_exists('Models\revenueModel')) {
    require_once __DIR__ . '/../../models/revenueModel.php';
}

class RevenueApi {
    private $revenueModel;

    public function __construct() {
        $this->revenueModel = new \Models\revenueModel();
    }

    public static function handleRequest() {
        header('Content-Type: application/json');
        
        try {
            $api = new self();
            $action = $_REQUEST['action'] ?? '';

            switch ($action) {
                case 'getRevenueByDay':
                    $api->getRevenueByDay();
                    break;

                case 'getRevenueByMonth':
                    $api->getRevenueByMonth();
                    break;

                case 'getRevenueByYear':
                    $api->getRevenueByYear();
                    break;

                case 'getDailyRevenueChart':
                    $api->getDailyRevenueChart();
                    break;

                case 'getMonthlyRevenueChart':
                    $api->getMonthlyRevenueChart();
                    break;

                case 'getRevenueBySource':
                    $api->getRevenueBySource();
                    break;

                case 'getTopHotels':
                    $api->getTopHotels();
                    break;

                default:
                    self::errorResponse('Invalid action: ' . $action);
            }
        } catch (\Exception $e) {
            error_log("API Error: " . $e->getMessage());
            self::errorResponse($e->getMessage());
        }
    }

    /**
     * Get revenue by day
     */
    private function getRevenueByDay() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $data = $this->revenueModel->getRevenueByDay($date);
        self::successResponse($data);
    }

    /**
     * Get revenue by month
     */
    private function getRevenueByMonth() {
        $month = $_GET['month'] ?? date('Y-m');
        $data = $this->revenueModel->getRevenueByMonth($month);
        self::successResponse($data);
    }

    /**
     * Get revenue by year
     */
    private function getRevenueByYear() {
        $year = $_GET['year'] ?? date('Y');
        $data = $this->revenueModel->getRevenueByYear($year);
        self::successResponse($data);
    }

    /**
     * Get daily revenue chart data for month
     */
    private function getDailyRevenueChart() {
        $month = $_GET['month'] ?? date('Y-m');
        $data = $this->revenueModel->getDailyRevenueForMonth($month);
        
        // Format for chart
        $chartData = [
            'labels' => [],
            'data' => [],
            'counts' => []
        ];

        foreach ($data as $item) {
            $dateObj = new \DateTime($item['date']);
            $day = $dateObj->format('d');
            $chartData['labels'][] = 'Ngày ' . $day;
            $chartData['data'][] = floatval($item['revenue'] ?? 0);
            $chartData['counts'][] = intval($item['bookingCount'] ?? 0);
        }

        self::successResponse($chartData);
    }

    /**
     * Get monthly revenue chart data for year
     */
    private function getMonthlyRevenueChart() {
        $year = $_GET['year'] ?? date('Y');
        $data = $this->revenueModel->getMonthlyRevenueForYear($year);
        
        $chartData = [
            'labels' => [],
            'data' => [],
            'counts' => []
        ];

        $months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                   'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];

        foreach ($data as $item) {
            // Extract month number from 'month' field (Y-m format)
            $monthParts = explode('-', $item['month']);
            $monthNum = intval($monthParts[1] ?? 1) - 1;
            $chartData['labels'][] = $months[$monthNum] ?? 'Unknown';
            $chartData['data'][] = floatval($item['revenue'] ?? 0);
            $chartData['counts'][] = intval($item['bookingCount'] ?? 0);
        }

        self::successResponse($chartData);
    }

    /**
     * Get revenue by source
     */
    private function getRevenueBySource() {
        $period = $_GET['period'] ?? 'month';
        $date = $_GET['date'] ?? null;
        $data = $this->revenueModel->getRevenueBySource($period, $date);
        
        // Format for pie chart
        $chartData = [
            'labels' => [],
            'data' => [],
            'sources' => []
        ];

        foreach ($data as $item) {
            $chartData['labels'][] = $item['source'] ?? 'Unknown';
            $chartData['data'][] = floatval($item['revenue'] ?? 0);
            $chartData['sources'][] = [
                'source' => $item['source'],
                'revenue' => floatval($item['revenue'] ?? 0),
                'bookingCount' => intval($item['bookingCount'] ?? 0),
                'avgBooking' => floatval($item['avgBooking'] ?? 0)
            ];
        }

        self::successResponse($chartData);
    }

    /**
     * Get top hotels
     */
    private function getTopHotels() {
        $period = $_GET['period'] ?? 'month';
        $date = $_GET['date'] ?? null;
        $limit = intval($_GET['limit'] ?? 5);
        
        $data = $this->revenueModel->getTopHotels($period, $date, $limit);
        self::successResponse(['hotels' => $data]);
    }

    private static function successResponse($data) {
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }

    private static function errorResponse($message) {
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit;
    }
}

RevenueApi::handleRequest();
