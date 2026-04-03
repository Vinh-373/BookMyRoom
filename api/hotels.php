<?php
/** Không in cảnh báo HTML ra output JSON */
ini_set('display_errors', '0');

// Import Database
require_once __DIR__ . '/../app/core/Database.php';

// Import Models
require_once __DIR__ . '/../app/models/myModels.php';
require_once __DIR__ . '/../app/models/hotelsModel.php';

// Import API Controllers
require_once __DIR__ . '/../app/controllers/api/HotelsApi.php';

// Handle the request
\Controllers\api\HotelsApi::handleRequest();
