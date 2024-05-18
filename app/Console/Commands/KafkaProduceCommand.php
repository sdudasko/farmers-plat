<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;


class KafkaProduceCommand extends Command
{
    protected $signature = 'kafka:produce';
    protected $description = 'Produce a message to a Kafka topic';

    public function handle()
    {
        $message = new Message(
            headers: ['header-key' => 'header-value'],
            body: ['key' => 'value'],
            key: 'kafka key here'  
        );

        Kafka::publish('kafka:29092')->onTopic('weather1')->withBodyKey('asdsad', 'vcxx')->withMessage($message)->send();
    }
}