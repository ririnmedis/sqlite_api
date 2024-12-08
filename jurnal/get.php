<?php
// Include database configuration
include('../config/config.php');

// Set the content type to JSON
header('Content-Type: application/json');

// Create a response array
$response = [
    'status' => 'success', // Default status
    'message' => 'Data retrieved successfully.', // Default success message
    'data' => [] // Default empty data
];

try {
    // Query to get data from tb_jurnal, including the id
    $sql = "SELECT id, judul, isi FROM tb_jurnal";
    $result = $koneksi->query($sql);

    // Check if the query was successful
    if ($result) {
        // Check if there are rows in the result
        if ($result->num_rows > 0) {
            $data = [];

            // Fetch data into an array
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // Populate response with data
            $response['data'] = $data;
        } else {
            // If no rows, return empty data with success message
            $response['message'] = 'No data found.';
        }
    } else {
        // If query failed, return error message
        $response['status'] = 'error';
        $response['message'] = 'Query failed to execute.';
    }

    // Close the connection
    $koneksi->close();
} catch (Exception $e) {
    // Handle exception and return error
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

// Output the response as JSON
echo json_encode($response);
?>
