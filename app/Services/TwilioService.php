<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $twilio;
    protected $from;

    public function __construct()
    {
        // TEMPORARY FIX: Hardcode credentials to bypass cache issues
        // TODO: Remove after server restart and use config() instead
        $sid = 'AC5c2cb54a83038ccf39e39b2e41001ee6';
        $token = '65c15a1b8db322c2c06083af43205efd';
        $this->from = '+19786437950';

        // Debug: log what we're using (remove after testing)
        Log::info("Twilio Service initialized (HARDCODED)", [
            'sid' => $sid,
            'token_preview' => substr($token, 0, 8) . '...',
            'from' => $this->from
        ]);

        $this->twilio = new Client($sid, $token);
    }

    /**
     * Send SMS notification
     *
     * @param string $to Phone number to send to
     * @param string $message Message content
     * @return bool
     */
    public function sendSMS($to, $message)
    {
        try {
            $this->twilio->messages->create($to, [
                'from' => $this->from,
                'body' => $message
            ]);

            Log::info("SMS sent successfully to {$to}: {$message}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification for new book
     *
     * @param string $bookTitle
     * @return bool
     */
    public function notifyNewBook($bookTitle)
    {
        // TEMPORARY FIX: Hardcode phone number
        $to = '+21624019297';
        $message = "New book added named {$bookTitle}";
        
        return $this->sendSMS($to, $message);
    }
}

