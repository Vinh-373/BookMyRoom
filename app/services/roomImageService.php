<?php
namespace Services;
use Models\RoomImageModel;
require_once "./app/models/RoomImageModel.php";
class RoomImageService {
    private $roomImageModel;

    public function __construct() {
        $this->roomImageModel = new RoomImageModel();
    }
    function getImageByRoomConfigId($roomId){
        return $this->roomImageModel->select_array('*',['roomConfigId'=>$roomId]);
    }
}