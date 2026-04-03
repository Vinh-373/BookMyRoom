<?php
/**
 * Setup Script - Kết nối MySQL và Import Database
 * Chạy file này tại browser: http://localhost/BookMyRoom/setup.php
 */

// Cấu hình kết nối
$host = "127.0.0.1";
$username = "root";
$password = "123456";
$database = "bookmyroom";

echo "<h2>✓ Setup BookMyRoom Database</h2>";

// Step 1: Kết nối tới MySQL Server (không cần database)
echo "<h3>Step 1: Kết nối tới MySQL Server</h3>";

$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("<p style='color: red;'>❌ Lỗi kết nối: " . $conn->connect_error . "</p>");
}

echo "<p style='color: green;'>✓ Kết nối MySQL thành công</p>";

// Step 2: Tạo database
echo "<h3>Step 2: Tạo Database</h3>";

$sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Database '$database' đã được tạo/tồn tại</p>";
} else {
    die("<p style='color: red;'>❌ Lỗi tạo database: " . $conn->error . "</p>");
}

// Step 3: Chọn database
echo "<h3>Step 3: Chọn Database</h3>";

if ($conn->select_db($database)) {
    echo "<p style='color: green;'>✓ Chọn database '$database' thành công</p>";
} else {
    die("<p style='color: red;'>❌ Lỗi chọn database: " . $conn->error . "</p>");
}

// Step 4: Import SQL file
echo "<h3>Step 4: Import SQL File</h3>";

$sqlFile = __DIR__ . '/bookmyroom.sql';

if (!file_exists($sqlFile)) {
    die("<p style='color: red;'>❌ File bookmyroom.sql không tìm thấy tại: $sqlFile</p>");
}

$sqlContent = file_get_contents($sqlFile);

// Tách các câu lệnh SQL
$sqlStatements = array_filter(
    array_map(
        'trim',
        explode(';', $sqlContent)
    )
);

$successCount = 0;
$errorCount = 0;
$errors = [];

foreach ($sqlStatements as $statement) {
    // Bỏ qua comment và câu lệnh rỗng
    if (empty($statement) || strpos($statement, '--') === 0) {
        continue;
    }

    // Loại bỏ comment
    $lines = explode("\n", $statement);
    $cleanStatement = '';
    foreach ($lines as $line) {
        if (strpos(trim($line), '--') === 0) {
            continue;
        }
        if (strpos(trim($line), '/*') === 0 || strpos(trim($line), '*') === 0) {
            continue;
        }
        $cleanStatement .= $line . "\n";
    }

    $cleanStatement = trim($cleanStatement);

    if (!empty($cleanStatement)) {
        if ($conn->query($cleanStatement)) {
            $successCount++;
        } else {
            $errorCount++;
            $errors[] = $conn->error;
        }
    }
}

echo "<p>Câu lệnh thực thi thành công: <strong style='color: green;'>$successCount</strong></p>";

if ($errorCount > 0) {
    echo "<p>Lỗi: <strong style='color: red;'>$errorCount</strong></p>";
    echo "<details>";
    echo "<summary>Xem chi tiết lỗi</summary>";
    echo "<pre>" . implode("\n", $errors) . "</pre>";
    echo "</details>";
}

// Step 5: Kiểm tra các bảng
echo "<h3>Step 5: Kiểm tra các bảng</h3>";

$result = $conn->query("SHOW TABLES");

if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Các bảng đã được tạo:</p>";
    echo "<ul>";
    while ($row = $result->fetch_row()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: orange;'>⚠ Không tìm thấy bảng nào</p>";
}

// Step 6: Test kết nối từ Database.php
echo "<h3>Step 6: Test Kết nối từ Database.php</h3>";

require_once __DIR__ . '/app/core/Database.php';

try {
    $db = new Database();
    echo "<p style='color: green;'>✓ Database.php kết nối thành công</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
}

$conn->close();

echo "<h3>✓ Setup Hoàn Thành!</h3>";
echo "<p><a href='index.php'>Quay về trang chủ</a></p>";
?>
