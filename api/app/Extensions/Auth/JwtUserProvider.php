<?php


namespace App\Extensions\Auth;


use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class JwtUserProvider implements UserProvider
{
    // 全局app
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

    public function retrieveById($identifier)
    {
        return $this->model::where('email', '=', $identifier)->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        if (!$user = $this->retrieveById($identifier)) {
            return false;
        }
        // todo $user 和 token 进行对比鉴权
        return false;
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
