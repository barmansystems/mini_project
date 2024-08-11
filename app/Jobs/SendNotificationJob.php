<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user_id;
    protected $ticket_title;
    protected $company_name;
    protected $message;

    public function __construct($user_id, $ticket_title, $company_name, $message)
    {
        $this->user_id = $user_id;
        $this->ticket_title = $ticket_title;
        $this->company_name = $company_name;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendNotificationToPanel($this->user_id, $this->ticket_title, $this->company_name, $this->message);
    }

    private function sendNotificationToPanel($id, $title, $company_name,$message)
    {
        $data = [
            'user_id' => $id,
            'url_name' => $company_name,
            'ticket_title' => $title,
            'message' => $message,
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
            case "barman":
                $domain = env('BARMAN_PANEL_URL') . 'api/send-notification-to-user';
                break;
            case "adaktejarat":
                $domain = env('ADAKTEJARAT_PANEL_URL') . 'api/send-notification-to-user';
                break;
            default:
                $domain = "DomainNotFoundPleaseTryLater!";
        }

        return $domain;
    }
}
