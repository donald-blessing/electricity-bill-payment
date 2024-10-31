<?php

namespace App\Services;

use App\Actions\SendSmsAction;
use App\Models\Bill;
use App\Models\Wallet;
use Exception;

class SmsService
{
    /**
     * @throws Exception
     */
    public function sendBillCreatedNotification(Bill $bill): void
    {
        $message = "New electricity bill created. Amount: {$bill->amount}. Reference: {$bill->id}";
        SendSmsAction::execute($bill->user->phone, $message);
    }

    /**
     * @throws Exception
     */
    public function sendPaymentCompletedNotification(Bill $bill): void
    {
        $message = "Payment successful! Your token: {$bill->token}";
        SendSmsAction::execute($bill->user->phone, $message);
    }

    /**
     * @throws Exception
     */
    public function sendLowBalanceNotification(Wallet $wallet): void
    {
        $message = "Low wallet balance alert! Current balance: {$wallet->balance}";
        SendSmsAction::execute($wallet->user->phone, $message);
    }
}
