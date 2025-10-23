<?php

require __DIR__.'/vendor/autoload.php';

use Twilio\Rest\Client;

// Test Twilio credentials
$sid = 'AC5c2cb54a83038ccf39e39b2e41001ee6';
$token = '65c15a1b8db322c2c06083af43205efd';
$from = '+19786437950';
$to = '+21624019297';

echo "Testing Twilio SMS...\n";
echo "SID: {$sid}\n";
echo "From: {$from}\n";
echo "To: {$to}\n\n";

try {
    $client = new Client($sid, $token);
    
    $message = $client->messages->create($to, [
        'from' => $from,
        'body' => 'Test SMS from Atlas Roads'
    ]);
    
    echo "✅ SUCCESS! SMS sent!\n";
    echo "Message SID: {$message->sid}\n";
    echo "Status: {$message->status}\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
    echo "\nPossible issues:\n";
    echo "1. Invalid or expired Twilio credentials\n";
    echo "2. Trial account limitations (unverified phone numbers)\n";
    echo "3. Account suspended or out of credits\n";
    echo "4. 'From' number not registered with your Twilio account\n";
}

