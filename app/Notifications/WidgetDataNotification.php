<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class WidgetDataNotification extends Notification
{
    use Queueable;

    public $user;
    public $time;
    public $widget;
    public $widgetData;
    public $infoData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $widget, $widgetData, $time)
    {
        $this->user = $user;
        $this->time = $time;
        $this->widget = $widget;
        $this->widgetData = $widgetData;
        $this->infoData = json_decode($this->widgetData->info_data) ?? [];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nuevo Widget creado')
                    ->greeting('Hola')
                    ->line('Se ha generado un nuevo widget con la siguiente información:')
                    ->line(
                        'Nro. de teléfono: ' . (method_exists($this->infoData, 'phone'))  ? $this->infoData->phone : ''
                    )
                    ->line('URL: ' . $this->widget->url)
                    ->line('Usuario ' . (
                        ($this->user->id !== $this->widget->user_id)
                        ? 'Referido: ' : 'que creó el widget: '
                    ) . $this->user->name)
                    ->line('Fecha: ' . $this->widget->created_at)
                    ->line('Equipo Ventonic');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'icon' => 'icon-server',
            'title' => 'Nuevo widget de ' . $this->user->name,
            'text'=>  'Se ha generado un nuevo widget',
            'time'=> $this->time
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'icon' => 'icon-server',
            'title' => 'Nuevo widget de ' . $this->user->name,
            'text'=>  'Se ha generado un nuevo widget',
            'time'=> $this->time
        ]);
    }
}
