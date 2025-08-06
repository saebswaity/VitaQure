<?php
// DB connection parameters
$host = 'localhost';      // or your DB host
$db   = 'clinjnuv_Clindalab';
$user = 'root';           // your DB user
$pass = 'your_mysql_password';  // your DB password
$charset = 'utf8mb4';

// Create PDO connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Hash the new password
    $newPassword = '123456';
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Prepare update statement
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->execute([
        ':password' => $hashedPassword,
        ':email'    => 'super_admin@lab.com',
    ]);

    echo "Password updated successfully!";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
