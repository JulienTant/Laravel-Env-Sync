<?php

namespace Jtant\LaravelEnvSync\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class MissingEnvVariables extends Notification
{
    use Queueable;

    protected $missingVars;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($missingVars)
    {
        $this->missingVars = $missingVars;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from(config('env-sync.slack.username'), config('env-sync.slack.icon'))
            ->to(config('env-sync.slack.channel'))
            ->error()
            ->attachment(function ($attachment) {
                return $attachment
                    ->title('Whoops! You are missing some env variables on the server.')
                    ->content(implode("\n", array_keys($this->missingVars)));
            });
    }
}
