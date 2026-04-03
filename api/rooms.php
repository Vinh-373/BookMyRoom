<?php
ini_set('display_errors', '0');
// Import Database
require_once __DIR__ . '/../app/core/Database.php';

// Import Models
require_once __DIR__ . '/../app/models/myModels.php';
require_once __DIR__ . '/../app/models/roomsModel.php';

// Import API Controllers
require_once __DIR__ . '/../app/controllers/api/RoomsApi.php';

// Handle the request
RoomsApi::handleRequest();
