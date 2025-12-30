<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class SmsService
{
    protected $apiUrl;
    protected $senderNumber;

    public function __construct()
    {
        $this->apiUrl = Setting::where('key', 'sms_api_url')->value('value') ?? 'https://metinyildirim.net/api/smsapi.php';
        $this->senderNumber = Setting::where('key', 'sms_sender_number')->value('value') ?? 'YOUR_SENDER_NUMBER'; // Default sender number
    }

    public function sendSms(string $number, string $message): bool
    {
        try {
            $response = Http::asForm()->post($this->apiUrl, [
                'number' => $number,
                'sms' => $message,
                // Add any other required parameters like API key if needed by the service
                // 'api_key' => Setting::where('key', 'sms_api_key')->value('value'),
            ]);

            // Log the response for debugging
            \Log::info('SMS API Response:', ['status' => $response->status(), 'body' => $response->body()]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('SMS gönderme hatası: ' . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function parseTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace("{{ {$key} }}", $value, $template);
        }
        return $template;
    }
}
