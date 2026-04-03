<?php
namespace Services;
use Models\RoomConfigModel;
require_once "./app/models/roomConfigModel.php";
class RoomConfigService {
    private $roomConfigModel;

    public function __construct() {
        $this->roomConfigModel = new RoomConfigModel();
    }
    function getRoomConfigByHotelId($hotelId){
        return $this->roomConfigModel->join_multi( [
        [
     
            'table' => 'roomtypes',
            'on'    => 'roomtypes.id = roomTypeId'
        ],
        
    ],
    'roomconfigurations.*,name',
    ['hotelId' => $hotelId]);
    }
    function getRoomConfigsAvailableByHotel($hotelId, $dates = null){
        return $this->roomConfigModel->getRoomConfigsAvailableByHotel($hotelId, $dates);
    }
    function getById($id){
        return $this->roomConfigModel->getById($id);
    }
}