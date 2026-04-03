<?php
namespace Services;
use Models\PartnerModel;
require_once "./app/models/partnerModel.php";
class PartnerService {
    private $partnerModel;

    public function __construct() {
        $this->partnerModel = new PartnerModel();
    }
    function getPartnerById($partnerId){
        return $this->partnerModel->select_array('*',['userId'=>$partnerId]);
    }
}