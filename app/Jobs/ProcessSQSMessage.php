<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSQSMessage implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $messageData;

    public function __construct(array $messageData)
    {
        $this->messageData = $messageData;
    }

    public function handle(): void
    {
        // Process the message based on queue URL
        switch ($this->messageData['QueueUrl']) {
            case 'bill_notifications':
                $this->processBillNotification();
                break;
            case 'payment_notifications':
                $this->processPaymentNotification();
                break;
            case 'balance_notifications':
                $this->processBalanceNotification();
                break;
        }
    }

    private function processBillNotification(): void
    {
        $data = json_decode($this->messageData['Body'], true);

        Log::info('Processing bill notification', $data);
    }

    private function processPaymentNotification(): void
    {
        $data = json_decode($this->messageData['Body'], true);
        Log::info('Processing payment notification', $data);
    }

    private function processBalanceNotification(): void
    {
        $data = json_decode($this->messageData['Body'], true);

        Log::info('Processing balance notification', $data);
    }
}
