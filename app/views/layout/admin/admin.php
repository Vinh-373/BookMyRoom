<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <?php
    $adminPublicBase = '/BookMyRoom/';
    if (defined('BASE_URL')) {
        $path = parse_url(BASE_URL, PHP_URL_PATH);
        if (is_string($path) && $path !== '' && $path !== '/') {
            $adminPublicBase = rtrim($path, '/') . '/';
        }
    }
    $adminPublicBaseEsc = htmlspecialchars($adminPublicBase, ENT_QUOTES, 'UTF-8');
    ?>
    <link rel="stylesheet" href="<?php echo $adminPublicBaseEsc; ?>public/css/admin/variables.css">
    <link rel="stylesheet" href="<?php echo $adminPublicBaseEsc; ?>public/css/admin/main.css">
    <link rel="stylesheet" href="<?php echo $adminPublicBaseEsc; ?>public/css/admin/admin.css">
    <link rel="stylesheet" href="<?php echo $adminPublicBaseEsc; ?>public/css/admin/hotels.css">
    <link rel="stylesheet" href="<?php echo $adminPublicBaseEsc; ?>public/css/admin/rooms.css">
    <link rel="stylesheet" href="<?php echo $adminPublicBaseEsc; ?>public/css/admin/bookings.css">
    <link rel="stylesheet" href="<?php echo $adminPublicBaseEsc; ?>public/css/admin/settings.css">
    <link rel="stylesheet" href="<?php echo $adminPublicBaseEsc; ?>public/css/admin/dashboard.css">
</head>
<body class="bg-background text-on-background antialiased">
<?php
session_start();
// Sidebar
require_once 'sidebar.php';
// Header
require_once 'header.php';
?>

<?php
// Detect current page from viewFile
$pageName = 'dashboard';
if (isset($viewFile)) {
    preg_match('/admin\/([a-z_]+)\.php/', $viewFile, $matches);
    if (isset($matches[1])) {
        $pageName = $matches[1];
    }
}
?>
<main class="main-content" data-page="<?php echo $pageName; ?>" style="margin-left: 240px; margin-top: 70px; width: calc(100% - 240px); min-height: calc(100dvh - 70px); background-color: #f7f9fb; box-sizing: border-box; overflow-y: auto;">
<?php
if (isset($viewFile) && file_exists($viewFile)) {
    require_once $viewFile;
} else {
    require_once __DIR__ . '/../../admin/dashboard.php';
}
?>
</main>

<?php
$apiBasePath = rtrim($adminPublicBase, '/') . '/api';
$apiBasePathJson = json_encode($apiBasePath, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<!-- Trùng khớp với BASE_URL (App.php); JS dùng cho fetch API, tránh URL /api sai khi suy từ script.src -->
<script>window.BOOKMYROOM_API_BASE = <?php echo $apiBasePathJson; ?>;</script>

<!-- Admin Scripts: đường dẫn tuyệt đối từ web root (tránh resolve sai từ index.php?url=...) -->
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/sidebar.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/utils.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/init.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/hotels.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/rooms.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/bookings.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/settings.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/dashboard.js"></script>
</body>
</html>