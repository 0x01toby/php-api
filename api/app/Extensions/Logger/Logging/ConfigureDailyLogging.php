<?php


namespace App\Extensions\Logger\Logging;

use App\Extensions\Logger\JsonFormatter;
use Illuminate\Log\Logger;

class ConfigureDailyLogging
{
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {

            $handler->setFormatter(new JsonFormatter());
        }
    }

}
