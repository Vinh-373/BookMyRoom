<?php
namespace Services;
use Models\VoucherModel;
require_once "./app/models/VoucherModel.php";
class VoucherService {
    private $voucherModel;

    public function __construct() {
        $this->voucherModel = new VoucherModel();
    }
    function getVoucherById($voucherId){
        return $this->voucherModel->select_array('*',['id'=>$voucherId]);
    }
    function getAllVouchers(){
        return $this->voucherModel->select_array('*');
    }
}