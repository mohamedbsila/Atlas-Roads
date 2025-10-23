<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $twilio;
    protected $from;
    protected $notifyNumber;

    public function __construct()
    {
        // Get credentials from config (reads from .env)
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from = config('services.twilio.from');
        $this->notifyNumber = config('services.twilio.notify_number');

        // Debug: log what we're using
        Log::info("Twilio Service initialized", [
            'sid' => $sid,
            'token_preview' => substr($token, 0, 8) . '...',
            'from' => $this->from,
            'notify_to' => $this->notifyNumber
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
        $to = $this->notifyNumber;
        $message = "📚 Atlas Roads Library: New book added - '{$bookTitle}'";
        
        return $this->sendSMS($to, $message);
    }

    /**
     * Send notification for book borrowed
     *
     * @param string $bookTitle
     * @param string $borrowerName
     * @return bool
     */
    public function notifyBookBorrowed($bookTitle, $borrowerName)
    {
        $to = $this->notifyNumber;
        $message = "📖 Atlas Roads Library: '{$bookTitle}' has been borrowed by {$borrowerName}";
        
        return $this->sendSMS($to, $message);
    }

    /**
     * Send notification for book returned
     *
     * @param string $bookTitle
     * @return bool
     */
    public function notifyBookReturned($bookTitle)
    {
        $to = $this->notifyNumber;
        $message = "✅ Atlas Roads Library: '{$bookTitle}' has been returned";
        
        return $this->sendSMS($to, $message);
    }

    /**
     * Send custom notification
     *
     * @param string $message
     * @param string|null $to Override notify number
     * @return bool
     */
    public function sendNotification($message, $to = null)
    {
        $recipient = $to ?? $this->notifyNumber;
        return $this->sendSMS($recipient, $message);
    }
}

