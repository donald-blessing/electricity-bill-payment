<?php

namespace App\Listeners;

use App\Events\LowBalanceDetected;
use App\Services\AWS\SNSService;
use App\Services\AWS\SQSService;

class PublishLowBalanceEvent
{
    private $snsService;
    private $sqsService;

    public function __construct(SNSService $snsService, SQSService $sqsService)
    {
        $this->snsService = $snsService;
        $this->sqsService = $sqsService;
    }

    /**
     * @param  LowBalanceDetected  $event
     *
     * @return void
     */
    public function handle(LowBalanceDetected $event): void
    {
        // Publish to SNS
        $this->snsService->publishLowBalance($event->toBroadcast());

        // Send to SQS for async processing
        $this->sqsService->sendBalanceNotification($event->toBroadcast());
    }
}
