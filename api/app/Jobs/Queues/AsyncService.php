<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-8
 * Time: 下午12:45
 */

namespace App\Jobs\Queues;


use App\Jobs\Job;
use App\Models\User;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AsyncService extends Job
{
    protected $user;

    protected $payload;

    public function __construct(User $user, $payload)
    {
        $this->user = $user;
        $this->payload = $payload;
    }

    /**
     * 异步service方法
     */
    public function handle()
    {
        Auth::login($this->user);
        $payload = unserialize($this->payload);
        try {
            $args = $this->getArguments($payload['args']);
            call_user_func_array([$payload['object'], $payload['method']], $args);
        } catch (\Exception $e) {
            Log::error("call service method failed");
        }
    }

    /**
     * 恢复序列化后的参数
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args): array
    {
        $arguments = [];
        foreach ($args as $arg) {
            $arguments[] = $arg instanceof ModelIdentifier ? (new $arg->class)->findOrFail($arg->id) : $arg;
        }
        return $arguments;
    }
}
