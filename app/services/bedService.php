<?php
namespace Services;
use Models\BedModel;
require_once "./app/models/bedModel.php";
class BedService {
    private $bedModel;

    public function __construct() {
        $this->bedModel = new BedModel();
    }

    function getBedByConfigId($configId){
        return $this->bedModel->join_multi([
        [
            'table' => 'bedtypes',
            'on'    => 'bedtypes.id = bedTypeId'
        ],
        
    ],
    '*',
    ['roomConfigId' => $configId]);
    }
}