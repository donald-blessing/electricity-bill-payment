<?php

namespace App\Services\AWS;

class SNSService
{
    private MockSNSClient $snsClient;
    private array $topics = [];

    public function __construct(MockSNSClient $snsClient)
    {
        $this->snsClient = $snsClient;
        $this->initializeTopics();
    }

    private function initializeTopics(): void
    {
        // Create standard topics
        $this->topics['bill_created']      = $this->snsClient->createTopic(['Name' => 'bill_created'])['TopicArn'];
        $this->topics['payment_completed'] = $this->snsClient->createTopic(['Name' => 'payment_completed'])['TopicArn'];
        $this->topics['low_balance']       = $this->snsClient->createTopic(['Name' => 'low_balance'])['TopicArn'];
    }

    public function publishBillCreated(array $data): array
    {
        return $this->snsClient->publish([
            'TopicArn' => $this->topics['bill_created'],
            'Message'  => json_encode($data),
        ]);
    }

    public function publishPaymentCompleted(array $data): array
    {
        return $this->snsClient->publish([
            'TopicArn' => $this->topics['payment_completed'],
            'Message'  => json_encode($data),
        ]);
    }

    public function publishLowBalance(array $data): array
    {
        return $this->snsClient->publish([
            'TopicArn' => $this->topics['low_balance'],
            'Message'  => json_encode($data),
        ]);
    }
}
