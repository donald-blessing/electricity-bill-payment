<?php

namespace App\Services\AWS;

use App\Jobs\ProcessSQSMessage;
use Exception;
use Illuminate\Support\Facades\Queue;

class MockSQSClient
{
    private array $queues = [];

    public function createQueue(array $params): array
    {
        $queueUrl                = 'https://mock-sqs.amazonaws.com/'.$params['QueueName'];
        $this->queues[$queueUrl] = [];

        return ['QueueUrl' => $queueUrl];
    }

    /**
     * @throws Exception
     */
    public function sendMessage(array $params): array
    {
        $messageId = uniqid('mock_message_');
        $queueUrl  = $params['QueueUrl'];

        if (!isset($this->queues[$queueUrl])) {
            throw new Exception('Queue not found');
        }

        // Use Laravel's queue system to simulate SQS
        Queue::push(new ProcessSQSMessage([
            'MessageId' => $messageId,
            'Body'      => $params['MessageBody'],
            'QueueUrl'  => $queueUrl,
        ]));

        return ['MessageId' => $messageId];
    }

    public function receiveMessage(array $params)
    {
        $queueUrl = $params['QueueUrl'];

        if (!isset($this->queues[$queueUrl])) {
            throw new Exception('Queue not found');
        }

        // Simulate message retrieval
        return array_shift($this->queues[$queueUrl]) ?? null;
    }
}
