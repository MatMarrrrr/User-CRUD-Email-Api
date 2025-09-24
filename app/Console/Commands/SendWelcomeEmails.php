<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\SendWelcomeEmail;

class SendWelcomeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:welcome-emails {userId}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome emails to all addresses of a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('userId');
        $user = User::with('emails')->find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        if ($user->emails->isEmpty()) {
            $this->error("User with ID {$userId} has no emails.");
            return;
        }

        foreach ($user->emails as $email) {
            Mail::to($email->email)->send(new SendWelcomeEmail($user));
            $this->info("Email sent to: {$email->email}");
        }

        $this->info("All emails have been sent to user {$user->id} ({$user->first_name} {$user->last_name}).");
    }
}
