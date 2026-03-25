<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary-fixed-dim": "#adc7ff",
              "on-error": "#ffffff",
              "error": "#ba1a1a",
              "surface": "#f7f9fb",
              "on-tertiary-fixed-variant": "#7c2e00",
              "secondary": "#305ea4",
              "surface-dim": "#d8dadc",
              "tertiary-fixed": "#ffdbcc",
              "on-primary-container": "#fefcff",
              "on-background": "#191c1e",
              "on-secondary-fixed": "#001b3f",
              "tertiary": "#9e3d00",
              "on-secondary": "#ffffff",
              "tertiary-fixed-dim": "#ffb695",
              "surface-container-low": "#f2f4f6",
              "on-primary": "#ffffff",
              "secondary-container": "#87b1fd",
              "on-error-container": "#93000a",
              "primary-fixed": "#d8e2ff",
              "on-tertiary-container": "#fffbff",
              "background": "#f7f9fb",
              "outline-variant": "#c1c6d7",
              "surface-container-high": "#e6e8ea",
              "surface-bright": "#f7f9fb",
              "surface-container-highest": "#e0e3e5",
              "on-tertiary-fixed": "#351000",
              "tertiary-container": "#c64f00",
              "on-secondary-container": "#044287",
              "secondary-fixed": "#d7e3ff",
              "outline": "#717786",
              "on-tertiary": "#ffffff",
              "inverse-surface": "#2d3133",
              "surface-variant": "#e0e3e5",
              "primary-container": "#0070ea",
              "surface-tint": "#005bc0",
              "on-primary-fixed": "#001a41",
              "on-secondary-fixed-variant": "#0c458b",
              "on-surface": "#191c1e",
              "inverse-on-surface": "#eff1f3",
              "inverse-primary": "#adc7ff",
              "surface-container-lowest": "#ffffff",
              "secondary-fixed-dim": "#abc7ff",
              "on-surface-variant": "#414754",
              "on-primary-fixed-variant": "#004493",
              "error-container": "#ffdad6",
              "surface-container": "#eceef0",
              "primary": "#0059bb"
            },
            fontFamily: {
              "headline": ["Manrope"],
              "body": ["Inter"],
              "label": ["Inter"]
            },
            borderRadius: {"DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem"},
          },
        },
      }
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
    <link rel="stylesheet" href="../../../../public/css/admin/admin.css">
</head>
<body class="bg-background text-on-background antialiased">
<?php
session_start();
// Sidebar
require_once 'sidebar.php';
// Header
require_once 'header.php';
?>

<main class="main-content" style="margin-left: 240px; margin-top: 70px; width: calc(100% - 240px); min-height: calc(100dvh - 70px); background-color: #f7f9fb; box-sizing: border-box; overflow-y: auto;">
<?php
if (isset($viewFile) && file_exists($viewFile)) {
    require_once $viewFile;
} else {
    require_once __DIR__ . '/../../admin/dashboard.php';
}
?>
</main>
<script src="../../../../public/js/admin/sidebar.js"></script>
</body>
</html>