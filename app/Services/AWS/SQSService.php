<?php

namespace App\Services\AWS;

use Exception;

class SQSService
{
    private MockSQSClient $sqsClient;
    private array $queues = [];

    public function __construct(MockSQSClient $sqsClient)
    {
        $this->sqsClient = $sqsClient;
        $this->initializeQueues();
    }

    private function initializeQueues(): void
    {
        // Create standard queues
        $this->queues['bill_notifications']    = $this->sqsClient->createQueue(['QueueName' => 'bill_notifications'])['QueueUrl'];
        $this->queues['payment_notifications'] = $this->sqsClient->createQueue(['QueueName' => 'payment_notifications'])['QueueUrl'];
        $this->queues['balance_notifications'] = $this->sqsClient->createQueue(['QueueName' => 'balance_notifications'])['QueueUrl'];
    }

    /**
     * @throws Exception
     */
    public function sendBillNotification(array $data): array
    {
        return $this->sqsClient->sendMessage([
            'QueueUrl'    => $this->queues['bill_notifications'],
            'MessageBody' => json_encode($data),
        ]);
    }

    /**
     * @throws Exception
     */
    public function sendPaymentNotification(array $data): array
    {
        return $this->sqsClient->sendMessage([
            'QueueUrl'    => $this->queues['payment_notifications'],
            'MessageBody' => json_encode($data),
        ]);
    }

    public function sendBalanceNotification(array $data): array
    {
        return $this->sqsClient->sendMessage([
            'QueueUrl'    => $this->queues['balance_notifications'],
            'MessageBody' => json_encode($data),
        ]);
    }
}
