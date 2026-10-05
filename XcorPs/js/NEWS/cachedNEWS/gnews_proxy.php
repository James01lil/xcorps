<?php
// gnews_proxy.php

$cacheFile = 'news_cache.json';
$cacheTime = 1800; // 30 minutes = 1800 seconds

// Check if cached file exists and is fresh
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    // Serve cached content
    header('Content-Type: application/json');
    echo file_get_contents($cacheFile);
    exit;
}

// Fetch from GNews API
$apiKey = "66e47d59086af2af250337e8777cc88b"; // Replace with your GNews API key
$apiUrl = "https://gnews.io/api/v4/top-headlines?lang=en&country=us&token=$apiKey";

// Fetch the news
$response = @file_get_contents($apiUrl);

// If fetch is successful, save and serve
if ($response !== false) {
    file_put_contents($cacheFile, $response);
    header('Content-Type: application/json');
    echo $response;
} else {
    // On failure, serve old cache if available
    if (file_exists($cacheFile)) {
        header('Content-Type: application/json');
        echo file_get_contents($cacheFile);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            "articles" => [],
            "error" => "News fetch failed and no cache is available."
        ]);
    }
}
?>