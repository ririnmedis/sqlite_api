<?php

// Include konfigurasi database
include('../config/config.php');

// Set header untuk mendukung JSON
header('Content-Type: application/json');

// Periksa metode HTTP
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    // Ambil data JSON dari request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Validasi input
    if (isset($input['id']) && isset($input['judul']) && isset($input['isi'])) {

        $id = $input['id'];
        $judul = $input['judul'];
        $isi = $input['isi'];

        // Validasi apakah ID adalah angka
        if (!is_numeric($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID jurnal harus berupa angka.'
            ]);
            exit;
        }

        // Buat query SQL untuk memperbarui data jurnal
        $query = "UPDATE tb_jurnal SET judul = ?, isi = ? WHERE id = ?";

        // Siapkan statement SQL
        if ($stmt = $koneksi->prepare($query)) {

            // Bind parameter
            $stmt->bind_param('ssi', $judul, $isi, $id);

            // Eksekusi statement
            if ($stmt->execute()) {
                // Berikan respons sukses
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Jurnal berhasil diperbarui.'
                ]);
            } else {
                // Berikan respons error jika eksekusi gagal
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui jurnal.'
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
            'message' => 'ID, judul, dan isi wajib diisi.'
        ]);
    }
} else {
    // Berikan respons error jika metode bukan PUT
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode HTTP tidak valid.'
    ]);
}

// Tutup koneksi database
$koneksi->close();

?>
