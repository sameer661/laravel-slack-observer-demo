<?php

return [
    'webhook_urls' => [
       //The webhook URLs that we'll use to send a message to Slack.
        'new_registration' => env('SLACK_ALERT_WEBHOOK'),
    ],

];
