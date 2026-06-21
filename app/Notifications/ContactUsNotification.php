<?php

namespace App\Notifications;

use App\Models\ContactUs;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactUsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected ContactUs $contactUs) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'broadcast'];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): array
    {
        return [
            'message' => 'New contact message received!',
            'data' => [
                'id' => $this->contactUs->id,
                'name' => $this->contactUs->name,
                'email' => $this->contactUs->email,
                'subject' => $this->contactUs->subject,
                'message' => $this->contactUs->message,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new \Illuminate\Broadcasting\Channel('contact-notifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $setting = Setting::query()->first();
        $companyName = $setting?->doctor_name ?: config('app.name');
        $faviconUrl = $setting?->favicon ? secure_asset($setting->favicon) : null;

        return (new MailMessage)
            ->subject('New Contact Us Message')
            ->view('emails.contact-us', [
                'contactUs' => $this->contactUs,
                'companyName' => $companyName,
                'faviconUrl' => $faviconUrl,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->contactUs->id,
            'name' => $this->contactUs->name,
            'email' => $this->contactUs->email,
            'subject' => $this->contactUs->subject,
            'message' => $this->contactUs->message,
            'read' => $this->contactUs->read,
            'created_at' => $this->contactUs->created_at?->toISOString(),
        ];
    }
}
