<?php
/**
 * Standalone Test Script for Azure OpenAI Text-to-Speech API
 * This script tests the TTS functionality independently
 */

echo "<h2>Azure OpenAI Text-to-Speech API Test</h2>";
echo "<hr>";

// Azure OpenAI TTS Configuration
$azure_tts_endpoint = "https://abhay-mh5stdgf-swedencentral.cognitiveservices.azure.com/openai/deployments/tts/audio/speech?api-version=2025-03-01-preview";

// Load API key from environment file
$env_file = __DIR__ . '/.env';
$azure_api_key = "";
$found_tts_key = false;

if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key_trimmed = trim($key);
            // Prioritize AZURE_TTS_API_KEY over AZURE_OPENAI_API_KEY
            if ($key_trimmed === 'AZURE_TTS_API_KEY') {
                $azure_api_key = trim($value);
                $found_tts_key = true;
                break;
            } elseif ($key_trimmed === 'AZURE_OPENAI_API_KEY' && !$found_tts_key) {
                $azure_api_key = trim($value);
            }
        }
    }
}

if (empty($azure_api_key)) {
    die("ERROR: Azure API key not found in .env file");
}

echo "<p><strong>API Endpoint:</strong> " . htmlspecialchars($azure_tts_endpoint) . "</p>";
echo "<p><strong>API Key Source:</strong> " . ($found_tts_key ? "AZURE_TTS_API_KEY" : "AZURE_OPENAI_API_KEY") . "</p>";
echo "<p><strong>API Key:</strong> " . substr($azure_api_key, 0, 10) . "..." . substr($azure_api_key, -10) . " (masked)</p>";
echo "<hr>";

// Test text
$test_text = "The quick brown fox jumped over the lazy dog. This is a test of the Azure OpenAI Text to Speech API.";
echo "<p><strong>Test Text:</strong> " . htmlspecialchars($test_text) . "</p>";

// Prepare the request payload
$payload = [
    'model' => 'tts',
    'input' => $test_text,
    'voice' => 'alloy'
];

echo "<p><strong>Request Payload:</strong></p>";
echo "<pre>" . json_encode($payload, JSON_PRETTY_PRINT) . "</pre>";
echo "<hr>";

// Initialize cURL
echo "<p>Sending request to Azure OpenAI TTS API...</p>";
$ch = curl_init($azure_tts_endpoint);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'api-key: ' . $azure_api_key
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

// Execute the request
$start_time = microtime(true);
$response = curl_exec($ch);
$execution_time = microtime(true) - $start_time;

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

echo "<p><strong>HTTP Response Code:</strong> " . $http_code . "</p>";
echo "<p><strong>Execution Time:</strong> " . round($execution_time, 2) . " seconds</p>";
echo "<p><strong>Content Type:</strong> " . htmlspecialchars($content_type) . "</p>";

// Handle errors
if ($curl_error) {
    echo "<p style='color: red;'><strong>cURL Error:</strong> " . htmlspecialchars($curl_error) . "</p>";
    exit;
}

if ($http_code !== 200) {
    echo "<p style='color: red;'><strong>API Error:</strong> HTTP " . $http_code . "</p>";
    echo "<pre>" . htmlspecialchars(substr($response, 0, 1000)) . "</pre>";
    exit;
}

// Save the audio file
$output_file = __DIR__ . '/output.mp3';
$save_result = file_put_contents($output_file, $response);

if ($save_result === false) {
    echo "<p style='color: red;'><strong>Error:</strong> Failed to save audio file</p>";
    exit;
}

echo "<p style='color: green;'><strong>✓ SUCCESS!</strong> Audio generated successfully</p>";
echo "<p><strong>File Size:</strong> " . number_format(filesize($output_file)) . " bytes</p>";
echo "<p><strong>File Path:</strong> " . htmlspecialchars($output_file) . "</p>";
echo "<hr>";

echo "<h3>Play Audio:</h3>";
echo "<audio controls style='width: 100%;'>";
echo "<source src='output.mp3?t=" . time() . "' type='audio/mpeg'>";
echo "Your browser does not support the audio element.";
echo "</audio>";

echo "<hr>";
echo "<p><a href='output.mp3' download>Download Audio File</a></p>";
?>
