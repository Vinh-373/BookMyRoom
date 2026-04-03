<?php
// require_once __DIR__ . '/../../core/Service.php';

class VoucherService extends Service {
    
    public function getVoucherPageData($hotelId, $filters) {
        $model = $this->model('VoucherModel');
        $limit = 9;
        $page = isset($filters['page']) ? (int)$filters['page'] : 1;
        $offset = ($page - 1) * $limit;

        $vouchers = $model->getAllByHotelId($filters, $hotelId, $limit, $offset);
        $totalRecords = $model->countAllByHotelId($filters, $hotelId);

        $today = strtotime(date('Y-m-d'));

        foreach ($vouchers as &$v) {
            $start = strtotime($v['startDate']);
            $end = strtotime($v['endDate']);

            if ($v['quantity'] <= 0) {
                $v['status_class'] = 'out_of_stock';
                $v['status_text'] = 'Hết lượt';
            } elseif ($today < $start) {
                $v['status_class'] = 'upcoming';
                $v['status_text'] = 'Sắp diễn ra';
            } elseif ($today > $end) {
                $v['status_class'] = 'expired';
                $v['status_text'] = 'Kết thúc';
            } else {
                $v['status_class'] = 'active';
                $v['status_text'] = 'Hoạt động';
            }
            $v['can_edit_or_delete'] = ($v['status_class'] === 'upcoming');
        }

        return [
            'vouchers' => $vouchers,
            'totalPages' => ceil($totalRecords / $limit),
            'currentPage' => $page,
            'filters' => $filters
        ];
    }

    public function handleSave($postData, $hotelId) {
        $today = strtotime(date('Y-m-d'));
        $tomorrow = strtotime('+1 day', $today);
        $startDate = strtotime($postData['startDate']);
        $endDate = strtotime($postData['endDate']);

        if ($startDate < $tomorrow) {
            return false;
        }

        if ($endDate <= $startDate) {
            return false;
        }

        $data = [
            'code' => strtoupper(trim($postData['code'])),
            'quantity' => (int)$postData['quantity'],
            'type' => $postData['type'],
            'amount' => (float)$postData['amount'],
            'condition' => (float)($postData['condition'] ?? 0),
            'startDate' => $postData['startDate'],
            'endDate' => $postData['endDate'],
            'hotelId' => $hotelId
        ];

        $model = $this->model('VoucherModel');
        if (!empty($postData['id'])) {
            $data['id'] = $postData['id'];
            return $model->updateVoucher($data);
        }
        return $model->createVoucher($data);
    }
}