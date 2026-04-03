<?php
// require_once __DIR__ . '/../../core/Service.php';
class FinanceService extends Service {
    
    public function getFinanceStats($hotelId, $period) {
        $model = $this->model('FinanceModel');
        $gross = $model->getTotalRevenueByPeriod($hotelId, $period);
        
        $commission = $gross * 0.10; // Hoa hồng cố định 10%
        $net = $gross - $commission;

        return [
            'total_revenue' => $gross,
            'commission' => $commission,
            'net_payout' => $net
        ];
    }

    public function getRevenueByRoomType($hotelId, $period) {
        return $this->model('FinanceModel')->getRevenueByRoomType($hotelId, $period);
    }

    public function getRecentPayouts($hotelId, $limit) {
        return $this->model('FinanceModel')->getRecentPayouts($hotelId, $limit);
    }

    public function exportCSV($hotelId, $period) {
        $data = $this->model('FinanceModel')->getRevenueByRoomType($hotelId, $period);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=BaoCaoTaiChinh_'.$period.'_'.date('Ymd').'.csv');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 cho Excel
        
        fputcsv($output, ['Loại Phòng', 'Doanh Thu Gross', 'Hoa Hồng (10%)', 'Thực Nhận (Net)']);
        foreach ($data as $row) {
            $gross = $row['amount'];
            $comm = $gross * 0.1;
            fputcsv($output, [$row['room_type'], $gross, $comm, $gross - $comm]);
        }
        fclose($output);
    }

    public function getTransactionPageData($hotelId, $filters) {
        $model = $this->model('FinanceModel');
        $limit = 10;
        $page = (int)($filters['page'] ?? 1);
        $offset = ($page - 1) * $limit;

        $transactions = $model->getAllTransactions($hotelId, $filters, $limit, $offset);
        $totalCount = $model->getTransactionCount($hotelId, $filters);

        return [
            'transactions' => $transactions,
            'totalCount' => $totalCount,
            'currentPage' => $page,
            'totalPages' => max(1, ceil($totalCount / $limit)),
            'filters' => $filters
        ];
    }
}