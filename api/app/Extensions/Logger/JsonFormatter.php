<?php


namespace App\Extensions\Logger;

use Exception;
use \Monolog\Formatter\JsonFormatter AS MonologJsonFormatter;
use Monolog\Utils;
use Throwable;

class JsonFormatter extends MonologJsonFormatter
{

    public function format(array $record)
    {
        return $this->toJson($this->normalize($record), true) . PHP_EOL;
    }


    public function normalize($data, $depth = 0)
    {
        if ($depth > 9) {
            return 'Over 9 levels deep, aborting normalization';
        }

        if (is_array($data) || $data instanceof \Traversable) {
            $normalized = array();

            $count = 1;
            foreach ($data as $key => $value) {
                if ($count++ > 1000) {
                    $normalized['...'] = 'Over 1000 items ('.count($data).' total), aborting normalization';
                    break;
                }

                $normalized[$key] = $this->normalize($value, $depth+1);
            }

            return $normalized;
        }

        if ($data instanceof Exception || $data instanceof Throwable) {
            return $this->normalizeException($data);
        }

        return $data;
    }


    protected function normalizeException($e)
    {
        // TODO 2.0 only check for Throwable
        if (!$e instanceof Exception && !$e instanceof Throwable) {
            throw new \InvalidArgumentException('Exception/Throwable expected, got '.gettype($e).' / '.Utils::getClass($e));
        }

        $data = array(
            'class' => Utils::getClass($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile().':'.$e->getLine(),
        );

        if ($this->includeStacktraces) {
            $trace = $e->getTrace();
            foreach ($trace as $frame) {
                if (isset($frame['file'])) {
                    $data['trace'][] = $frame['file'].':'.$frame['line'];
                } elseif (isset($frame['function']) && $frame['function'] === '{closure}') {
                    // We should again normalize the frames, because it might contain invalid items
                    $data['trace'][] = $frame['function'];
                } else {
                    // We should again normalize the frames, because it might contain invalid items
                    $data['trace'][] = $this->normalize($frame);
                }
            }
        }

        if ($previous = $e->getPrevious()) {
            $data['previous'] = $this->normalizeException($previous);
        }

        return $data;
    }
}
