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
$partial = isset($_GET['partial']) && $_GET['partial'] == 1;

if ($partial) {
    if (isset($viewFile)) {
        $projectRoot = dirname(__DIR__, 4);
        $partialFile = $viewFile;
        if (strpos($viewFile, './') === 0) {
            $partialFile = $projectRoot . '/' . substr($viewFile, 2);
        } elseif (strpos($viewFile, 'app/') === 0) {
            $partialFile = $projectRoot . '/' . $viewFile;
        } elseif (!file_exists($partialFile)) {
            $try = $projectRoot . '/' . ltrim($viewFile, '/');
            if (file_exists($try)) {
                $partialFile = $try;
            }
        }
        if (file_exists($partialFile)) {
            include $partialFile;
        } else {
            echo '<p style="color:red;">View file not found: ' . htmlspecialchars($partialFile) . '</p>';
        }
    }
    return;
}

require_once __DIR__ . '/sidebar.php';
require_once __DIR__ . '/header.php';

$pageName = 'dashboard';
if (isset($viewFile)) {
    if (preg_match('/admin\/([a-z0-9_-]+)\.php/i', $viewFile, $matches)) {
        $pageName = $matches[1];
    }
}
$pageNameEsc = htmlspecialchars($pageName, ENT_QUOTES, 'UTF-8');
?>
<main class="main-content" data-page="<?php echo $pageNameEsc; ?>" style="margin-left: 240px; margin-top: 70px; width: calc(100% - 240px); min-height: calc(100dvh - 70px); background-color: #f7f9fb; box-sizing: border-box; overflow-y: auto;">
<?php
$fallbackDashboard = __DIR__ . '/../../admin/dashboard.php';
if (isset($viewFile)) {
    $pageFile = $viewFile;
    $projectRoot = dirname(__DIR__, 4);

    if (strpos($viewFile, './') === 0) {
        $pageFile = $projectRoot . '/' . substr($viewFile, 2);
    } elseif (strpos($viewFile, 'app/') === 0) {
        $pageFile = $projectRoot . '/' . ltrim($viewFile, '/');
    }

    if (!file_exists($pageFile) && (strpos($viewFile, '../') === 0 || strpos($viewFile, './') === 0)) {
        $resolved = realpath(__DIR__ . '/' . $viewFile);
        if ($resolved) {
            $pageFile = $resolved;
        }
    }

    if (file_exists($pageFile)) {
        require_once $pageFile;
    } else {
        require_once $fallbackDashboard;
    }
} else {
    require_once $fallbackDashboard;
}
?>
</main>

<?php
$apiBasePath = rtrim($adminPublicBase, '/') . '/api';
$apiBasePathJson = json_encode($apiBasePath, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$publicBaseJson = json_encode(rtrim($adminPublicBase, '/'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$indexPhpJson = json_encode(rtrim($adminPublicBase, '/') . '/index.php', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<script>
window.BOOKMYROOM_API_BASE = <?php echo $apiBasePathJson; ?>;
window.BOOKMYROOM_PUBLIC_BASE = <?php echo $publicBaseJson; ?>;
window.BOOKMYROOM_INDEX_PHP = <?php echo $indexPhpJson; ?>;
</script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/sidebar.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/utils.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/init.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/hotels.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/rooms.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/bookings.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/settings.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/dashboard.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/staff.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/customer.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/partner.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/payments.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/voucher.js"></script>
<script src="<?php echo $adminPublicBaseEsc; ?>public/js/admin/review.js"></script>
</body>
</html>
