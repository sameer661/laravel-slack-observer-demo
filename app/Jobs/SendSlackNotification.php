<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSlackNotification implements ShouldQueue
{
    use Queueable;
    public User $userData;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->userData = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendNotification();
    }

    private function sendNotification(): bool
    {
        $webhookUrl = config('slack.webhook_urls.new_registration');

        if (! $webhookUrl) {
            Log::warning('Slack webhook URL not configured.');
            return false;
        }

        $payload = [
            'text' => '*🚀 New User Registration*',
            'attachments' => [
                [
                    'title' => '👤 Contact Details',
                    'fields' => [
                        [
                            'title' => 'Name',
                            'value' => $this->userData->name ?? 'N/A',
                            'short' => true,
                        ],
                        [
                            'title' => 'Email',
                            'value' => $this->userData->email ?? 'N/A',
                            'short' => true,
                        ],
                        [
                            'title' => 'Registered At',
                            'value' => optional($this->userData->created_at)->format('M d, Y h:i A') ?? 'N/A',
                            'short' => true,
                        ],
                    ],
                ]
            ]
        ];

        try {
            $response = Http::post($webhookUrl, $payload);

            // This is the critical line the test expects:
            if (! $response->successful()) {
                Log::warning('Slack webhook responded with error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Slack webhook request failed: ' . $e->getMessage());
        }

        return true;
    }

}
