<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwilioService;

class TestSmsCommand extends Command
{
    protected $signature = 'sms:test {message?}';
    protected $description = 'Test SMS notification functionality';

    public function handle()
    {
        $this->info('🔍 Testing SMS Service...');
        $this->newLine();

        try {
            $twilioService = new TwilioService();
            $this->info('✅ TwilioService initialized successfully!');
            $this->newLine();

            $message = $this->argument('message') ?? 'Test message from Atlas Roads Library';
            
            $this->info('📤 Sending SMS...');
            $result = $twilioService->sendNotification($message);

            if ($result) {
                $this->info('✅ SMS sent successfully!');
                $this->info('📱 Check your phone: ' . config('services.twilio.notify_number'));
            } else {
                $this->error('❌ SMS failed to send. Check logs for details.');
            }

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('💡 Solutions:');
            $this->line('1. Get valid Twilio credentials from https://www.twilio.com');
            $this->line('2. Update your .env file:');
            $this->line('   TWILIO_SID=your_account_sid');
            $this->line('   TWILIO_AUTH_TOKEN=your_auth_token');
            $this->line('   TWILIO_FROM=+1234567890');
            $this->line('   TWILIO_NOTIFY_NUMBER=+1234567890');
            $this->line('3. Restart your server: php artisan serve');
        }

        return 0;
    }
}


