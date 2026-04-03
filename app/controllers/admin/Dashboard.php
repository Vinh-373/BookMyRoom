<?php
namespace Controllers\admin;
use Controller;

require_once __DIR__ . '/../../../app/models/revenueModel.php';

class Dashboard extends Controller {
    public function index() {
        if (isset($_GET['partial']) && $_GET['partial'] == '1') {
            $this->view('admin/dashboard');
            return;
        }

        // Load revenue model
        $revenueModel = new \Models\revenueModel();

        // Get current statistics
        $monthRevenue = $revenueModel->getRevenueByMonth(date('Y-m'));
        $yearRevenue = $revenueModel->getRevenueByYear(date('Y'));
        $todayRevenue = $revenueModel->getRevenueByDay(date('Y-m-d'));

        // Get chart data
        $dailyChartData = $revenueModel->getDailyRevenueForMonth(date('Y-m'));
        $monthlyChartData = $revenueModel->getMonthlyRevenueForYear(date('Y'));

        // Get revenue by source
        $revenueBySource = $revenueModel->getRevenueBySource('month', date('Y-m'));
        $topHotels = $revenueModel->getTopHotels('month', date('Y-m'), 5);

        $this->view('layout/admin/admin', [
            'viewFile' => './app/views/admin/dashboard.php',
            'monthRevenue' => $monthRevenue,
            'yearRevenue' => $yearRevenue,
            'todayRevenue' => $todayRevenue,
            'dailyChartData' => json_encode($dailyChartData),
            'monthlyChartData' => json_encode($monthlyChartData),
            'revenueBySource' => $revenueBySource,
            'topHotels' => $topHotels
        ]);
    }
}