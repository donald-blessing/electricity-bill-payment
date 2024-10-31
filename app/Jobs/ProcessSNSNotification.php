<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Models\Wallet;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSNSNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    private string $notificationType;

    public function __construct(array $data, string $notificationType)
    {
        $this->data             = $data;
        $this->notificationType = $notificationType;
    }

    public function handle(SmsService $smsService): void
    {
        switch ($this->notificationType) {
            case 'bill_created':
                $this->processBillCreated($smsService);
                break;
            case 'payment_completed':
                $this->processPaymentCompleted($smsService);
                break;
            case 'low_balance':
                $this->processLowBalance($smsService);
                break;
        }
    }

    private function processBillCreated(SmsService $smsService): void
    {
        $bill = Bill::query()->find($this->data['id']);
        $smsService->sendBillCreatedNotification($bill);
    }

    private function processPaymentCompleted(SmsService $smsService): void
    {
        $bill = Bill::query()->find($this->data['id']);
        $smsService->sendPaymentCompletedNotification($bill);
    }

    private function processLowBalance(SmsService $smsService): void
    {
        $wallet = Wallet::query()->find($this->data['id']);
        $smsService->sendLowBalanceNotification($wallet);
    }
}
