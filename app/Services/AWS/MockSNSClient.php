<?php

namespace App\Services\AWS;

use Exception;
use Illuminate\Support\Facades\Log;

class MockSNSClient
{
    private array $topics = [];
    private array $subscriptions = [];

    public function createTopic(array $params): array
    {
        $topicArn                = 'arn:aws:sns:mock:'.$params['Name'];
        $this->topics[$topicArn] = [
            'TopicArn'      => $topicArn,
            'Subscriptions' => [],
        ];

        return ['TopicArn' => $topicArn];
    }

    public function publish(array $params): array
    {
        $messageId = uniqid('mock_message_');
        $topicArn  = $params['TopicArn'];

        if (!isset($this->topics[$topicArn])) {
            throw new Exception('Topic not found');
        }

        foreach ($this->topics[$topicArn]['Subscriptions'] as $subscription) {
            $this->deliverMessage($subscription['Endpoint'], $params['Message']);
        }

        return ['MessageId' => $messageId];
    }

    public function subscribe(array $params): array
    {
        $subscriptionArn = 'arn:aws:sns:mock:subscription:'.uniqid();
        $topicArn        = $params['TopicArn'];

        if (!isset($this->topics[$topicArn])) {
            throw new Exception('Topic not found');
        }

        $this->topics[$topicArn]['Subscriptions'][] = [
            'SubscriptionArn' => $subscriptionArn,
            'Protocol'        => $params['Protocol'],
            'Endpoint'        => $params['Endpoint'],
        ];

        return ['SubscriptionArn' => $subscriptionArn];
    }

    private function deliverMessage($endpoint, $message): void
    {
        // Log for mock purposes
        Log::info("Message delivered to {$endpoint}: {$message}");
    }
}
