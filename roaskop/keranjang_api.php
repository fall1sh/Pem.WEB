<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$file = 'keranjang.json';

// Membaca data keranjang dari file JSON
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}
$data = json_decode(file_get_contents($file), true);

switch ($method) {
    case 'GET':
        // Menampilkan semua item keranjang
        echo json_encode($data);
        break;

    case 'POST':
        // Menambahkan item baru ke keranjang
        $item = [
            'id' => uniqid(),
            'nama' => $input['nama'],
            'harga' => $input['harga'],
            'jumlah' => $input['jumlah']
        ];
        $data[] = $item;
        file_put_contents($file, json_encode($data));
        echo json_encode(['status' => 'success', 'message' => 'Item added', 'item' => $item]);
        break;

    case 'PUT':
        // Memperbarui item di keranjang
        $found = false;
        foreach ($data as &$item) {
            if ($item['id'] === $input['id']) {
                $item['nama'] = $input['nama'] ?? $item['nama'];
                $item['harga'] = $input['harga'] ?? $item['harga'];
                $item['jumlah'] = $input['jumlah'] ?? $item['jumlah'];
                $found = true;
                break;
            }
        }
        if ($found) {
            file_put_contents($file, json_encode($data));
            echo json_encode(['status' => 'success', 'message' => 'Item updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Item not found']);
        }
        break;

    case 'DELETE':
        // Menghapus item dari keranjang
        $id = $input['id'];
        $data = array_filter($data, fn($item) => $item['id'] !== $id);
        file_put_contents($file, json_encode($data));
        echo json_encode(['status' => 'success', 'message' => 'Item deleted']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
        break;
}
?>
