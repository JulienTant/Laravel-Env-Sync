<?php

return [

    'slack' => [
        /**
         * Using the channel config you can modify the channel to which the notification is sent.
         * This allows you to reuse slack web-hooks, create one and use it for all projects.
         * If you want the notification to go to the default channel just leave it blank.
         * You can specify channel like this `#general` or user like this `@john.doe`.
         */
        'channel' => env('ENV_SYNC_SLACK_CHANNEL'),

        /**
         * The slack web-hook url used to deliver the notification.
         */
        'url' => env('ENV_SYNC_SLACK_URL'),

        /**
         * The name displayed as a sender of the notification in slack.
         */
        'username' => 'Deployer',

        /**
         * The icon displayed as a sender of the notification in slack.
         * You can use any slack icon here.
         */
        'icon' => ':boom:',
    ],
];
