<?php

namespace Jtant\LaravelEnvSync\Notifications;

use Illuminate\Notifications\Notifiable;

class SlackChannel
{
    use Notifiable;

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return config('env-sync.slack.url');
    }
}
