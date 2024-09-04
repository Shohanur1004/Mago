<?php
$directory = 'downloads'; // Directory containing the files

// Construct the base URL dynamically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$requestUri = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = "$protocol://$host$requestUri/$directory";

// Scan the directory for files
$files = array_diff(scandir($directory), array('..', '.')); // Exclude '.' and '..'

// Check if there are files in the directory
if (count($files) > 0) {
    // Select a random file
    $file = $files[array_rand($files)];
    $filePath = $directory . '/' . $file;

    // Generate a random filename with 30 characters
    function generateRandomFilename($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    $newFilename = generateRandomFilename() . '.' . pathinfo($file, PATHINFO_EXTENSION);
    $newFilePath = $directory . '/' . $newFilename;

    // Rename the file
    if (rename($filePath, $newFilePath)) {
        // Construct the file URL
        $fileUrl = "$baseUrl/$newFilename";
        
        // Return the file URL in JSON format
        header('Content-Type: application/json');
        echo json_encode(['url' => $fileUrl]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to rename the file.']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'No files available in the directory.']);
}
?>
