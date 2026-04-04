<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
    </style>
    <style>
    html, body {
      height: 100%;
      margin: 0;
      min-height: 100dvh;
      overflow: hidden;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f7f9fb;
    }
  </style>
    <link rel="stylesheet" href="/BookMyRoom/public/css/admin/admin.css">
</head>
<body class="bg-background text-on-background antialiased">
<?php
session_start();
$partial = isset($_GET['partial']) && $_GET['partial'] == 1;
if (!$partial) {
    // Sidebar
    require_once 'sidebar.php';
    // Header
    require_once 'header.php';
}

if ($partial) {
    // Khi load partial từ AJAX, chỉ trả nội dung trong trang (tránh lồng layout gây xê dịch)
    if (isset($viewFile)) {
        // Ưu tiên đường dẫn gốc đã hợp lệ, sau đó thử resolve từ layout folder
        if (!file_exists($viewFile)) {
            $viewFile = __DIR__ . '/../../' . ltrim($viewFile, '/');
        }
        if (!file_exists($viewFile) && strpos($viewFile, './') === 0) {
            $viewFile = __DIR__ . '/../../' . substr($viewFile, 2);
        }

        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo '<p style="color:red;">View file not found: ' . htmlspecialchars($viewFile) . '</p>';
        }
    }
    return;
}
?>

  <main class="main-content">
    <?php 
      // $viewFile được truyền từ controller
      if (isset($viewFile)) {
          $pageFile = $viewFile;

          $projectRoot = dirname(__DIR__, 4); // .../BookMyRoom

          // Nếu đường dẫn là ./app/views/...
          if (strpos($viewFile, './') === 0) {
              $pageFile = $projectRoot . '/' . substr($viewFile, 2);
          }

          // Nếu đường dẫn là app/views/...
          if (strpos($viewFile, 'app/') === 0) {
              $pageFile = $projectRoot . '/' . $viewFile;
          }

          // Nếu đường dẫn tương đối tới admin folder (vd ../../admin/hotels.php)
          if (!file_exists($pageFile) && (strpos($viewFile, '../') === 0 || strpos($viewFile, './') === 0)) {
              $pageFile = realpath(__DIR__ . '/' . $viewFile);
          }

          if (file_exists($pageFile)) {
              include $pageFile;
          } else {
              echo '<p style="color:red;">View file not found: ' . htmlspecialchars($pageFile) . '</p>';
          }
      }
    ?>
  </main>

<script type="module" src="/BookMyRoom/public/js/admin/admin.js"></script>
</body>
</html>