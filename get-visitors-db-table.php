<?php
// File path to user_log.txt
$filename = 'user_log.txt';

// Check if the file exists
if (file_exists($filename)) {
    // Read the file into an array, each line as an element
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Check if the file is empty
    if (empty($lines)) {
        echo "No data to display.";
    } else {
        echo '<table border="1">';

        // Assuming the first line contains column headers
        $headers = str_getcsv(array_shift($lines));
        echo '<tr>';
        foreach ($headers as $header) {
            echo '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
        }
        echo '</tr>';

        // Process each line of the file
        foreach ($lines as $line) {
            $data = str_getcsv($line);
            echo '<tr>';
            foreach ($data as $field) {
                echo '<td>' . htmlspecialchars($field, ENT_QUOTES, 'UTF-8') . '</td>';
            }
            echo '</tr>';
        }

        echo '</table>';
    }
} else {
    echo "File does not exist.";
}
?>
