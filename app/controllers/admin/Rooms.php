<?php
namespace Controllers\admin;
use Controller;

class Rooms extends Controller {
    public function index() {
        // Load models
        require_once __DIR__ . '/../../models/myModels.php';
        require_once __DIR__ . '/../../models/roomsModel.php';

        $roomModel = new \Models\roomsModel();

        // Get statistics
        $stats = $roomModel->getRoomStats();
        $totalRooms = $stats['total'];
        $availableRooms = $stats['available'];
        $bookedRooms = $stats['booked'];
        $maintenanceRooms = $stats['maintenance'];

        // Get rooms for initial display
        $roomsData = $roomModel->getRooms(1, 12);
        $rooms = $roomsData['rooms'];
        $totalPages = $roomsData['totalPages'];

        // Get hotels and room types for filters
        $hotels = $roomModel->getHotelsForFilter();
        $roomTypes = $roomModel->getRoomTypesForFilter();

        // Pass data to view
        $this->view('admin/rooms', [
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'bookedRooms' => $bookedRooms,
            'maintenanceRooms' => $maintenanceRooms,
            'rooms' => $rooms,
            'totalPages' => $totalPages,
            'hotels' => $hotels,
            'roomTypes' => $roomTypes
        ]);
    }
}
