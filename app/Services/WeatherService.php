<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;


class WeatherService
{
    public function temperatureDataObtained($message) {
        Kafka::publish('kafka:29092')
            ->onTopic('weather1')
            ->withKafkaKey('temperature')
            ->withBodyKey('temperature', $message)
            ->send(json_encode($message));
    }
}