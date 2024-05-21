<?php

header("Access-Control-Allow-Methods: " . implode(', ', $allowed_methods));
header("Access-Control-Allow-Headers: " . implode(', ', $allowed_headers));

$backend_url = 'http://XX.XX.XXX.XXX/'; // Change this to your server IP address

$request_method = $_SERVER['REQUEST_METHOD'];
if (!in_array($request_method, $allowed_methods)) {
    http_response_code(405); // Method Not Allowed
    exit();
}

$request_uri = $_SERVER['REQUEST_URI'];
$proxy_path = '/proxy.php';
if (strpos($request_uri, $proxy_path) === false) {
    http_response_code(400); // Bad Request
    exit();
}

$request_path = substr($request_uri, strpos($request_uri, $proxy_path) + strlen($proxy_path));
$full_url = rtrim($backend_url, '/') . $request_path;

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $full_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => $request_method,
    CURLOPT_HTTPHEADER => getallheaders(),
]);

// Handle POST and PUT requests
if ($request_method === 'POST' || $request_method === 'PUT') {
    $request_body = file_get_contents('php://input');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
}

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    $error_message = curl_error($ch);
    http_response_code(500); // Internal Server Error
    echo "Error: $error_message";
    exit();
}

curl_close($ch);

http_response_code($http_code);

echo $response;
