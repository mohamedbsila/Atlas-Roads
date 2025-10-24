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
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from = config('services.twilio.from');

        if (!$sid || !$token || !$this->from) {
            Log::warning("Twilio credentials not configured. SMS notifications will be skipped.");
            return;
        }

        Log::info("Twilio Service initialized", [
            'sid' => substr($sid, 0, 10) . '...',
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
        if (!$this->twilio) {
            Log::warning("Twilio not initialized. Skipping SMS to {$to}");
            return false;
        }

        try {
            $this->twilio->messages->create($to, [
                'from' => $this->from,
                'body' => $message
            ]);

            Log::info("SMS sent successfully", ['to' => $to, 'message' => $message]);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS", [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
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
        $to = config('services.twilio.notify_number');
        
        if (!$to) {
            Log::warning("Twilio notify number not configured. Skipping notification for: {$bookTitle}");
            return false;
        }
        
        $message = "📚 New book added to Atlas Roads: \"{$bookTitle}\"";
        
        return $this->sendSMS($to, $message);
    }
}
