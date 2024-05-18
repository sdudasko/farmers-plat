<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;

class ConsumeWeatherUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume messages from a Kafka topic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Kafka consumer...");
    
        // $consumer = Kafka::consumer(['weather1'], 'group', 'kafka:29092')
        //     ->withHandler(function ($message) {
        //         $data = [];
        //         try {
        //             // Decode the message body into an array
        //             \Log::info("Raw message body: ", ['body' => $message->getBody()]);
        //             $body = $message->getBody();
        //             $data = is_string($body) ? json_decode($body, true) : $body;
                    
        //             if (json_last_error() !== JSON_ERROR_NONE) {
        //                 throw new \Exception('JSON decode error: ' . json_last_error_msg());
        //             }

        //             \Log::info("Data after parsing: ", ['data' => $data]);
                    
        //             // Check if 'weather_data' key exists
        //             if (isset($data['weather_data'])) {
        //                 $weatherData = $data['weather_data'];
        //                 $this->info("Received weather data: " . print_r($weatherData, true));
    

        //                 \Log::info("Weather Data: ", ['data' => $weatherData]);
        //                 $this->processWeatherData($weatherData); // call a custom function if needed
        //             } else {
        //                 \Log::error("ERRORdata: ", ['data' => $data]);  // Log $data along with the error message

        //                 throw new \Exception('The key "weather_data" is missing from the message');
        //             }
        //         } catch (\Exception $e) {
        //             $this->error("Error handling message: " . $e->getMessage());
        //             \Log::error("ERRORdata: " . $e->getMessage(), ['data' => $data]);  // Log $data along with the error message
        //         }
        //     })
        //     ->build();
    
        // $consumer->consume();

        $consumer = Kafka::consumer(['weather1'], 'group', 'kafka:29092')
            ->withHandler(function ($message) {
                $data = [];
                try {
                    // Log the raw message body
                    \Log::info("Raw message body: ", ['body' => $message->getBody()]);
                    $body = $message->getBody();
                    $data = is_string($body) ? json_decode($body, true) : $body;
                    
                    // Check for JSON decoding errors
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('JSON decode error: ' . json_last_error_msg());
                    }

                    // Log the data after parsing
                    \Log::info("Data after parsing: ", ['data' => $data]);
                    
                    // Check if 'temperature' key exists and is formatted correctly
                    if (isset($data['temperature']) && is_array($data['temperature'])) {
                        $temperatureData = $data['temperature'];
                        $this->info("Received temperature data: " . print_r($temperatureData, true));
                        \Log::info("Temperature Data: ", ['data' => $temperatureData]);

                        $this->processWeatherData($temperatureData); // Call a custom function if needed
                    } else {
                        // Log the error with data if the expected key or format is missing
                        \Log::error("ERROR: Missing or incorrect 'temperature' data format", ['data' => $data]);
                        throw new \Exception('The key "temperature" is missing from the message or is not formatted correctly');
                    }
                } catch (\Exception $e) {
                    $this->error("Error handling message: " . $e->getMessage());
                    \Log::error("Error with data: " . $e->getMessage(), ['data' => $data]);  // Log $data along with the error message
                }
            })
            ->build();

        $consumer->consume();

    }

    public function processWeatherData($weatherData)
    {
        // var_dump($weatherData);
        // die();
        \Log::error("insertinggg", ['data' => $weatherData]);

        // Check for the 'temperature' key and value 'High'
        if (isset($weatherData['temperature'])) {
            // dd("inserting");
            // Save to the database
            \Log::info("INSERTING!!!");
            \DB::table('weather_alerts')->insert([
                'city' => $weatherData['city'],
                'alert' => $weatherData['temperature'],
                'created_at' => now(),
            ]);
        }
    }
    
}
