<?php
/**
 * Azure OpenAI Text-to-Speech API Handler
 * Converts text to speech audio using Azure OpenAI TTS
 */

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Load environment variables from .env file
$env_file = dirname(__DIR__) . '/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Set as environment variable if not already set
            if (!getenv($key)) {
                putenv("$key=$value");
            }
        }
    }
}

// Azure OpenAI TTS Configuration
$azure_tts_endpoint = "https://abhay-mh5stdgf-swedencentral.cognitiveservices.azure.com/openai/deployments/tts/audio/speech?api-version=2025-03-01-preview";
$azure_tts_api_key = getenv('AZURE_TTS_API_KEY') ?: getenv('AZURE_OPENAI_API_KEY') ?: "";
$azure_api_key = $azure_tts_api_key;

if (empty($azure_api_key)) {
    http_response_code(500);
    echo json_encode(['error' => 'Azure API key not configured']);
    exit;
}

// Get the input text from POST request
$input_data = json_decode(file_get_contents('php://input'), true);

if (!isset($input_data['text']) || empty(trim($input_data['text']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Text input is required']);
    exit;
}

$text = trim($input_data['text']);
$voice = isset($input_data['voice']) ? $input_data['voice'] : 'alloy';
$question_id = isset($input_data['question_id']) ? $input_data['question_id'] : 'temp';

// Available voices: alloy, echo, fable, onyx, nova, shimmer
$valid_voices = ['alloy', 'echo', 'fable', 'onyx', 'nova', 'shimmer'];
if (!in_array($voice, $valid_voices)) {
    $voice = 'alloy';
}

// Prepare the request payload
$payload = [
    'model' => 'tts',
    'input' => $text,
    'voice' => $voice
];

// Initialize cURL
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
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// Handle errors
if ($curl_error) {
    error_log("TTS cURL Error: " . $curl_error);
    http_response_code(500);
    echo json_encode(['error' => 'Failed to connect to TTS service']);
    exit;
}

if ($http_code !== 200) {
    error_log("TTS API Error: HTTP $http_code - Response: " . substr($response, 0, 500));
    http_response_code($http_code);
    echo json_encode(['error' => 'TTS service returned an error', 'code' => $http_code]);
    exit;
}

// Create audio cache directory if it doesn't exist
$audio_cache_dir = __DIR__ . '/audio_cache';
if (!file_exists($audio_cache_dir)) {
    mkdir($audio_cache_dir, 0755, true);
}

// Generate unique filename based on question ID and text hash
$text_hash = md5($text . $voice);
$filename = "question_{$question_id}_{$text_hash}.mp3";
$filepath = $audio_cache_dir . '/' . $filename;

// Save the audio file
$save_result = file_put_contents($filepath, $response);

if ($save_result === false) {
    error_log("Failed to save TTS audio file: " . $filepath);
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save audio file']);
    exit;
}

// Return success response with audio file URL
http_response_code(200);
echo json_encode([
    'success' => true,
    'audio_url' => 'audio_cache/' . $filename,
    'filename' => $filename,
    'size' => filesize($filepath)
]);
