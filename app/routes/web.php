<?php
return [
    'partner'       => ['PartnerController', 'index'],
    'manage'        => ['PartnerController', 'manage'],
    'partner/getWardsAjax' => ['PartnerController', 'getWardsAjax'],
    'partner/addHotel' => ['PartnerController', 'addHotel'],
    'partner/editHotel' => ['PartnerController', 'editHotel'],
    'partner/requestStop' => ['PartnerController', 'requestStop'],
    'partner/updateProfileAjax' => ['PartnerController', 'updateProfileAjax'],
    'dashboard' => ['DashboardController', 'index'],
    'bookings'  => ['BookingController', 'index'],
    'booking/detail' => ['BookingController', 'show'],
    'rooms' => ['RoomController','index'],
    'partner/addRoom' => ['RoomController','addRoom'],
    'partner/updateRoom' => ['RoomController','updateRoom'],
    'partner/addPhysicalRoom' => ['RoomController','addPhysicalRoom'],
    'partner/deletePhysicalRoom' => ['RoomController','deletePhysicalRoom'],
    'partner/deleteRoom' => ['RoomController','deleteRoom'],
    'partner/changeRoomStatus' => ['RoomController','changeRoomStatus'],

    'inventory' => ['InventoryController','index'],
    'partner/processBulkUpdate' => ['InventoryController','processBulkUpdate'],
    'partner/updateInventory' => ['InventoryController','updateInventory'],

    'staff'         => ['StaffController', 'index'],
    'partner/toggleStatus'  => ['StaffController', 'toggleStatus'],
    'partner/resetPassword'     => ['StaffController', 'resetPassword'],
    'partner/changeRole'  => ['StaffController', 'changeRole'],
    'partner/removeStaff'     => ['StaffController', 'removeStaff'],
    'partner/createStaff'  => ['StaffController', 'createStaff'],
    
    'reviews'       => ['ReviewController', 'index'],
    'partner/replyToReview' => ['ReviewController', 'replyToReview'],
    // 'partner/exportReviewsCSV' => ['ReviewController', 'exportReviewsCSV'],

    'reports'       => ['FinanceController', 'index'],
    'partner/exportFinanceCSV' => ['FinanceController', 'exportFinanceCSV'],
    'transactions'       => ['FinanceController', 'transactions'],

    'vouchers'       => ['VoucherController', 'index'],
    'partner/deleteVoucher'       => ['VoucherController', 'delete'],
    'partner/saveVoucher'       => ['VoucherController', 'save'],
];