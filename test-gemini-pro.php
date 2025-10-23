<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$apiKey = 'AIzaSyAF_OLWVxUIyCqB8YQPkItyh8pT8mKpYGw';
$model = 'gemini-1.5-pro';
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
$client = new Client();

try {
    $response = $client->post($url, [
        'json' => [
            'contents' => [[
                'parts' => [['text' => 'Dis bonjour en français']]
            ]],
            'generationConfig' => ['temperature' => 0.7, 'maxOutputTokens' => 100]
        ],
        'timeout' => 60
    ]);
    $data = json_decode($response->getBody(), true);
    echo "✅ SUCCÈS avec gemini-1.5-pro !\n\n";
    echo $data['candidates'][0]['content']['parts'][0]['text'];
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage();
}
