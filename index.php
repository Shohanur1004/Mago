<?php
// List of common bot user-agents to check
function isBot() {
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $bots = [
        'googlebot',
        'bingbot',
        'slackbot',
        'twitterbot',
        'facebookexternalhit',
        'pinterest',
        'linkedinbot',
        'feedfetcher-google',
        'adsbot-google',
        'baiduspider',
        'yandexbot',
        'msnbot',
        'duckduckbot',
        'ahrefsbot',
        'semrushbot',
        'sogou',
        'yahoo! slurp',
        'yahoo-slurp'
    ];

    foreach ($bots as $bot) {
        if (strpos($userAgent, $bot) !== false) {
            return true;
        }
    }
    return false;
}

// Function to get IP address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Function to get location using IP (including country, region, city)
function getLocation($ip) {
    // Replace this with an actual IP-to-location service
    $response = file_get_contents("https://api.iplocation.net/?ip=$ip");
    $data = json_decode($response, true);

    return [
        'country' => $data['country_name'] ?? 'unknown',
        'region' => $data['region_name'] ?? 'unknown',
        'city' => $data['city'] ?? 'unknown'
    ];
}

// Function to get device details
function getDeviceDetails() {
    return $_SERVER['HTTP_USER_AGENT'];
}

// Function to log user details
function logUserDetails($ip, $location, $device) {
    $time = date('Y-m-d H:i:s');
    $logData = "$time, $ip, {$location['country']}, {$location['region']}, {$location['city']}, $device\n";
    file_put_contents('user_log.txt', $logData, FILE_APPEND);
}

// Function to generate a random numeric string with a length between 25 and 30 characters
function generateRandomNumericString() {
    $length = rand(25, 30); // Random length between 25 and 30
    $randomBytes = random_bytes($length);
    $numericString = '';

    // Convert each byte to a numeric character
    foreach (str_split($randomBytes) as $byte) {
        $numericString .= ord($byte) % 10; // Ensure it's a single numeric digit
        if (strlen($numericString) >= $length) break; // Ensure the length is correct
    }

    return $numericString;
}

// Main logic
if (isBot()) {
    // Do nothing or handle bots if needed
    exit;
}

// Check if user is an attacker (example logic)
$attackerIPs = ['192.168.1.1', '10.0.0.1']; // Example attacker IPs
$userIP = getUserIP();

if (in_array($userIP, $attackerIPs)) {
    // If the IP is in the list of known attackers, do not log
    exit;
}

// Log valid user details
$location = getLocation($userIP);
$device = getDeviceDetails();
logUserDetails($userIP, $location, $device);

// Construct the base URL using the server request
$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$hostname = $_SERVER['HTTP_HOST'];
$baseURL = "$scheme://$hostname/";

// Generate random numeric parts and redirect
$randomPart1 = generateRandomNumericString();
$randomPart2 = generateRandomNumericString();
header("Location: {$baseURL}{$randomPart1}/{$randomPart2}");
exit;
?>
