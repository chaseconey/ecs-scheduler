<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;

class ServicesShutdown extends Notification
{
    private $services;

    /**
     * Create a new notification instance.
     *
     * @param $services
     */
    public function __construct(Collection $services)
    {
        $this->services = $services;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return config('services.slack.enabled') ? ['slack'] : [];
    }

    public function toSlack($notifiable)
    {

        $pluralServices = Str::plural('service', $this->services->count());
        return (new SlackMessage)
            ->to(config('services.slack.channel'))
            ->warning()
            ->content("{$this->services->count()} {$pluralServices} have been shutdown")
            ->attachment(function (SlackAttachment $attachment) use ($pluralServices) {
                $fields = $this->services->countBy('cluster.name')->toArray();

                $attachment->fields($fields)
                    ->action('Go to Scheduler', config('app.url'));
            });
    }
}
