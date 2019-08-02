<?php


namespace App\Extensions\Auth;


use App\Extensions\Auth\Jwt\JwtService;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Arr;
use Laravel\Lumen\Application;

class JwtUserProvider implements UserProvider
{
    /** @var $app Application */
    protected $app;
    // 配置文件
    protected $config;
    /** @var $model User */
    protected $model;

    /**
     * JwtUserProvider constructor.
     * @param $app
     * @param $config
     */
    public function __construct($app, $config)
    {
        $this->app = $app;
        $this->config = $config;
        $this->model = $config['model'];
    }

    /**
     * 获取用户信息
     * @param mixed $identifier
     * @return Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->model::where('email', '=', $identifier)->first();
    }

    /**
     * 验证和获取claims
     * @param $token
     * @return bool | array
     */
    public function retrieveByJwtToken($token)
    {
        if (!$claims = $this->app->make(JwtService::class)->validToken($token)) {
            return false;
        }
        return $claims;
    }

    /**
     * 验证token是否有效
     * @param mixed $identifier
     * @param string $token
     * @return bool|Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        if (!$user = $this->retrieveById($identifier)) {
            return false;
        }
        if (!$claims = $this->retrieveByJwtToken($token)) {
            return false;
        }
        return hash_equals($identifier, Arr::get($claims, "uid", ""));
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    public function retrieveByCredentials(array $credentials)
    {
        // TODO: Implement retrieveByCredentials() method.
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
    }

}
