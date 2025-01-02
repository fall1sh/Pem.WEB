<?php
// Konfigurasi database
$host = 'localhost';        // Nama host, biasanya 'localhost' jika menggunakan XAMPP
$user = 'root';             // Username default MySQL untuk XAMPP
$password = '';             // Password default MySQL untuk XAMPP (kosong)
$dbname = 'roaskop';        // Nama database yang telah Anda buat

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses GET request untuk membaca data dari tabel users
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Jika ID dikirim melalui parameter
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Pastikan ID adalah integer
        $sql = "SELECT id, email, username FROM users WHERE id = $id";
    } else {
        $sql = "SELECT id, email, username FROM users";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        // Mengirim data dalam format JSON
        header('Content-Type: application/json');
        echo json_encode($users);
    } else {
        echo json_encode(["message" => "Tidak ada data ditemukan."]);
    }
    exit; // Hentikan proses agar tidak tereksekusi bagian POST
}

// Proses input form jika request method adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validasi password
    if ($password !== $confirmPassword) {
        echo "Password dan Ulangi Password tidak cocok!";
        exit; // Hentikan proses jika password tidak cocok
    }

    // Enkripsi password menggunakan bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert ke database
    $sql = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$hashedPassword')";

    // Mengeksekusi query dan memberikan feedback
    if ($conn->query($sql) === TRUE) {
        echo "Registrasi berhasil! <a href='login.html'>Login sekarang</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Menutup koneksi
$conn->close();
?>
