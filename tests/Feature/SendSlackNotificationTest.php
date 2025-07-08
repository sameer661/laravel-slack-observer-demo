<?php

use App\Jobs\SendSlackNotification;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    // Set Slack webhook config value
    config()->set('slack.webhook_urls.new_registration', 'https://hooks.slack.com/test');
});

it('sends Slack message successfully', function () {
    Http::fake([
        'https://hooks.slack.com/test' => Http::response(['ok' => true], 200),
    ]);

    $user = User::factory()->make(['created_at' => now()]);
    $job = new SendSlackNotification($user);
    $job->handle();

    Http::assertSent(function ($request) use ($user) {
        return $request->url() === 'https://hooks.slack.com/test' &&
               str_contains($request['attachments'][0]['fields'][0]['value'], $user->name);
    });
});

it('logs error if Slack webhook fails', function () {
    config()->set('slack.webhook_urls.new_registration', 'https://hooks.slack.com/test');

    Http::fake([
        'https://hooks.slack.com/test' => Http::response('Slack down', 500),
    ]);

    Log::shouldReceive('warning')
        ->once()
        ->withArgs(function ($message, $context) {
            // Print for debug - you can remove after confirming
            dump($message, $context);

            return $message === 'Slack webhook responded with error'
                && is_array($context)
                && array_key_exists('status', $context)
                && array_key_exists('body', $context);
        });

    $user = User::factory()->make(['created_at' => now()]);
    $job = new SendSlackNotification($user);
    $job->handle();
});


it('logs exception if Slack call throws', function () {
    Http::fake([
        'https://hooks.slack.com/test' => function () {
            throw new \Exception('Connection failed');
        },
    ]);

    Log::shouldReceive('error')
        ->once()
        ->with(\Mockery::on(function ($message) {
            return str_contains($message, 'Slack webhook request failed');
        }));

    $user = User::factory()->make();
    $job = new SendSlackNotification($user);
    $job->handle();
});
