<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\{MailConfirmationController as AsyncEmail};

class EmailService
{
    public function sendAsync(string $email, $data)
    {
        try {
            // Queue the email to the database queue
            Mail::to($email)->queue(new AsyncEmail($data));
            
            // Log the job for debugging
            \Log::info("Email job saved to database queue for: {$email}");
        } catch (\Exception $e) {
            \Log::error("Failed to queue email for {$email}: " . $e->getMessage());
            throw $e;
        }
    }
}
