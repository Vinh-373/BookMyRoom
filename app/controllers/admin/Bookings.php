<?php
namespace Controllers\admin;
use Controller;

    class Bookings extends Controller {
        public function index() {
            // Load models
            require_once __DIR__ . '/../../models/myModels.php';
            require_once __DIR__ . '/../../models/bookingsModel.php';

            $bookingsModel = new \Models\bookingsModel();

            // Get statistics
            $stats = $bookingsModel->getBookingStats();
            $totalBookings = $stats['total'];
            $pendingBookings = $stats['pending'];
            $confirmedBookings = $stats['confirmed'];
            $cancelledBookings = $stats['cancelled'];
            $completedBookings = $stats['completed'];
            $totalRevenue = $stats['totalRevenue'];

            // Get bookings for initial display
            $bookingsData = $bookingsModel->getBookings(1, 10);
            $bookings = $bookingsData['bookings'];
            $totalPages = $bookingsData['totalPages'];

            if (isset($_GET['partial']) && $_GET['partial'] == '1') {
                $this->view('admin/bookings', [
                    'totalBookings' => $totalBookings,
                    'pendingBookings' => $pendingBookings,
                    'confirmedBookings' => $confirmedBookings,
                    'cancelledBookings' => $cancelledBookings,
                    'completedBookings' => $completedBookings,
                    'totalRevenue' => $totalRevenue,
                    'bookings' => $bookings,
                    'totalPages' => $totalPages
                ]);
                return;
            }

            $this->view('layout/admin/admin', [
                'viewFile' => './app/views/admin/bookings.php',
                'totalBookings' => $totalBookings,
                'pendingBookings' => $pendingBookings,
                'confirmedBookings' => $confirmedBookings,
                'cancelledBookings' => $cancelledBookings,
                'completedBookings' => $completedBookings,
                'totalRevenue' => $totalRevenue,
                'bookings' => $bookings,
                'totalPages' => $totalPages
            ]);
        }
    }
