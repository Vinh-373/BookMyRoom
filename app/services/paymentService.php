<?php
namespace Services;
use Models\PaymentModel;
require_once "./app/models/paymentModel.php";
class PaymentService {
    private $paymentModel;

    public function __construct() {
        $this->paymentModel = new PaymentModel();
    }
    function getPaymentById($paymentId){
        return $this->paymentModel->select_array('*',['id'=>$paymentId]);
    }
    function createPayment($data){
        return $this->paymentModel->insert($data);
    }
    function updatePaymentByBooking($bookingId, $data) {
        return $this->paymentModel->update($data, ['bookingId' => $bookingId]);
    }
    function deletePaymentByBooking($bookingId) {
        return $this->paymentModel->delete($bookingId);
    }
}