<?php
// login.php
session_start();

// Konfigurasi database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'roaskop';

$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            echo json_encode(["status" => "success", "message" => "Login berhasil", "username" => $username]);
        } else {
            echo json_encode(["status" => "error", "message" => "Password salah"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Username tidak ditemukan"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode tidak valid"]);
}

$conn->close();
?>
