<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SendNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $user_id;
    private $ticket_title;
    private $company_name;

    /**
     * Create a new notification instance.
     */
    public function __construct($user_id, $ticket_title, $company_name)
    {
        $this->user_id = $user_id;
        $this->ticket_title = $ticket_title;
        $this->company_name = $company_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return $this->sendNotificationToPanel($this->user_id, $this->company_name, $this->ticket_title);
    }

    private function sendNotificationToPanel($id, $company_name, $title)
    {
        $data = [
            'user_id' => $id,
            'url_name' => $company_name,
            'ticket_title' => $title,
        ];

        try {
            $response = Http::timeout(60)->post($this->getPanelUrl($company_name), $data);
            if ($response->successful()) {
                return $response->body();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function getPanelUrl($input)
    {
        $domain = '';

        switch ($input) {
            case "parso":
                $domain = env('PARSO_PANEL_URL') . 'api/send-notification-to-user';
                break;
            case 'barman':
                $domain = env('BARMAN_PANEL_URL') . 'api/send-notification-to-user';
                break;
            case "sayman":
                $domain = env('SAYMAN_PANEL_URL') . 'api/send-notification-to-user';
                break;
            case "adakhamrah":
                $domain = env('ADAKHAMRAH_PANEL_URL') . 'api/send-notification-to-user';
                break;
            case "adaktejarat":
                $domain = env('ADAKTEJARAT_PANEL_URL') . 'api/send-notification-to-user';
                break;
            case "adaksanat":
                $domain = env('ADAKSANAT_PANEL_URL') . 'api/send-notification-to-user';
                break;
            case "adakpetro":
                $domain = env('ADAKPETRO_PANEL_URL') . 'api/send-notification-to-user';
                break;
            default:
                $domain = 'Domain not found';
        }

        return $domain;
    }
}
