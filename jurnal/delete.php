<?php

// Include konfigurasi database
include('../config/config.php');

// Set header untuk mendukung JSON
header('Content-Type: application/json');

// Periksa metode HTTP
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    // Ambil data dari parameter URL
    if (isset($_GET['id'])) {

        $id = $_GET['id'];

        // Validasi apakah ID adalah angka
        if (!is_numeric($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID jurnal harus berupa angka.'
            ]);
            exit;
        }

        // Buat query SQL untuk menghapus data jurnal
        $query = "DELETE FROM tb_jurnal WHERE id = ?";

        // Siapkan statement SQL
        if ($stmt = $koneksi->prepare($query)) {

            // Bind parameter
            $stmt->bind_param('i', $id);

            // Eksekusi statement
            if ($stmt->execute()) {
                // Berikan respons sukses
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Jurnal berhasil dihapus.'
                ]);
            } else {
                // Berikan respons error jika eksekusi gagal
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menghapus jurnal.'
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
        // Berikan respons error jika ID tidak ditemukan di parameter
        echo json_encode([
            'status' => 'error',
            'message' => 'ID jurnal wajib diberikan sebagai parameter.'
        ]);
    }
} else {
    // Berikan respons error jika metode bukan DELETE
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode HTTP tidak valid.'
    ]);
}

// Tutup koneksi database
$koneksi->close();

?>
