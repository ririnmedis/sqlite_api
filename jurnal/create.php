<?php

// Include konfigurasi database
include('../config/config.php');

// Set header untuk mendukung JSON
header('Content-Type: application/json');

// Periksa metode HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil data JSON dari request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Validasi input
    if (isset($input['judul']) && isset($input['isi'])) {

        $judul = $input['judul'];
        $isi = $input['isi'];

        // Buat query SQL untuk menambahkan data jurnal
        $query = "INSERT INTO tb_jurnal (judul, isi) VALUES (?, ?)";

        // Siapkan statement SQL
        if ($stmt = $koneksi->prepare($query)) {

            // Bind parameter
            $stmt->bind_param('ss', $judul, $isi);

            // Eksekusi statement
            if ($stmt->execute()) {
                // Ambil ID data yang baru saja ditambahkan
                $last_id = $koneksi->insert_id;

                // Query untuk mengambil data yang baru saja ditambahkan
                $data_query = "SELECT * FROM tb_jurnal WHERE id = ?";
                $data_stmt = $koneksi->prepare($data_query);
                $data_stmt->bind_param('i', $last_id);
                $data_stmt->execute();
                $result = $data_stmt->get_result();
                $data = $result->fetch_assoc();
                $data_stmt->close();
                // Berikan respons sukses
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Jurnal berhasil ditambahkan.',
                    'data' => $data
                ]);
            } else {
                // Berikan respons error
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menambahkan jurnal.'
                ]);
            }

            // Tutup statement
            $stmt->close();
        } else {
            // Berikan respons error jika gagal mempersiapkan statement
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.'
            ]);
        }
    } else {
        // Berikan respons error jika input tidak valid
        echo json_encode([
            'status' => 'error',
            'message' => 'Judul dan isi wajib diisi.'
        ]);
    }
} else {
    // Berikan respons error jika metode bukan POST
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode HTTP tidak valid.'
    ]);
}

// Tutup koneksi database
$koneksi->close();

?>
