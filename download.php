<?php
$directory = 'downloads'; // Directory containing the files

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
        // Return the new filename as response
        header('Content-Type: text/plain');
        echo $newFilename;
    } else {
        http_response_code(500);
        echo 'Failed to rename the file.';
    }
} else {
    http_response_code(404);
    echo 'No files available in the directory.';
}
?>
