<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-8
 * Time: 下午2:56
 */

namespace App\Services;

use App\Jobs\Queues\AsyncService;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Contracts\Queue\QueueableEntity;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Config;

abstract class Service
{
    /**
     * @var $user User
     */
    public $user;

    protected $queue = 'async_service';

    /**
     * 设置用户
     * Service constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 获取用户
     * @return User
     */
    public function getUsers()
    {
        return $this->user;
    }

    /**
     * 实现延时和异步方法
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, ['async' ,'delay'])) {
            $method_name = "";
            $delay = 0;

            if (Str::startsWith($name, 'async')) {
                $method_name = preg_replace('/^async/', '', $name);
            } else if (Str::startsWith($name, 'delay')) {
                $method_name = preg_replace('/^delay/', '', $name);
                $delay = intval(array_shift($arguments));
            }

            $method_name = lcfirst($method_name);

            if (!method_exists($this, $method_name)) {
                throw new \Exception("method name: $method_name not exists in this service", 100010);
            }

            $arguments = $this->serializedArgs($arguments);

            $object = clone $this;

            $payload = [
                'object' => $object,
                'method' => $method_name,
                'args'   => $arguments,
                'configs' => [
                    'log_id' => Config::get('log_id')
                ]
            ];

            $job = new AsyncService($this->user, serialize($payload));
            $job->onQueue($this->queue)->delay($delay);
            dispatch($job);
        }
    }

    /**
     * 对异步的方法的参数进转换
     * @param array $args
     * @return array
     */
    protected function serializedArgs(array $args) : array
    {
        $arguments = [];
        foreach ($args as $arg) {
            $arguments[] = $arg instanceof QueueableEntity ? new ModelIdentifier(
                get_class($arg), $arg->getQueueableId(), $arg->getQueueableRelations(), $arg->getQueueableConnection()
            ) : $arg;
        }
        return $arguments;
    }


}
