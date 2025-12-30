<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Setting;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendIncompleteOrderReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-incomplete-order-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS reminders for incomplete orders that are 1 hour old.';

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for incomplete orders to send reminders...');

        $incompleteStatus = OrderStatus::where('name', 'Tamamlanmayan')->first();

        if (!$incompleteStatus) {
            $this->error('Incomplete order status not found. Please create it.');
            return Command::FAILURE;
        }

        $smsTemplate = Setting::where('key', 'incomplete_order_sms_template')->value('value');
        $smsSenderNumber = Setting::where('key', 'sms_sender_number')->value('value');

        if (!$smsTemplate || !$smsSenderNumber) {
            $this->warn('Incomplete order SMS template or sender number not configured. Skipping.');
            return Command::SUCCESS;
        }

        // Find incomplete orders that were created between 1 hour and 1 hour 15 minutes ago
        // and for which an SMS has not yet been sent.
        $orders = Order::where('order_status_id', $incompleteStatus->id)
            ->where('incomplete_order_sms_sent', false)
            //->where('created_at', '<=', Carbon::now()->subHour())
            //->where('created_at', '>', Carbon::now()->subMinutes(75)) // Window to prevent multiple sends if cron runs frequently
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No eligible incomplete orders found for reminders.');
            return Command::SUCCESS;
        }

        $this->info(sprintf('Found %d eligible incomplete orders.', $orders->count()));

        foreach ($orders as $order) {
            try {
                $smsData = [
                    'customer_name' => $order->customer_name,
                    'order_code' => $order->order_code,
                    'total_amount' => number_format($order->total_amount, 2),
                    'resume_link' => $order->resume_url, // Add resume link
                    // Add more placeholders as needed
                ];
                $parsedSms = $this->smsService->parseTemplate($smsTemplate, $smsData);

                // Assuming SmsService::sendSms returns true on success
                $sendResult = $this->smsService->sendSms($order->customer_phone, $parsedSms);

                if ($sendResult) {
                    $order->incomplete_order_sms_sent = true;
                    $order->incomplete_order_sms_sent_at = Carbon::now();
                    $order->save();
                    $this->info(sprintf('SMS reminder sent for order %s to %s.', $order->order_code, $order->customer_phone));
                } else {
                    $this->error(sprintf('Failed to send SMS reminder for order %s to %s.', $order->order_code, $order->customer_phone));
                }
            } catch (\Exception $e) {
                Log::error(sprintf('Error sending incomplete order SMS for order %s: %s', $order->order_code, $e->getMessage()), ['exception' => $e]);
                $this->error(sprintf('An error occurred while sending SMS for order %s. Check logs.', $order->order_code));
            }
        }

        $this->info('Incomplete order reminder process completed.');
        return Command::SUCCESS;
    }
}
