<?php


namespace App\Extensions\Auth;


use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class JwtGuard implements Guard
{
    /** @var User $user */
    protected $user;

    /** 配置文件 */
    protected $name;

    /** @var UserProvider 用户 */
    protected $provider;

    public function __construct(
        $name,
        UserProvider $provider      // authServerProvider 中 provider 配置
    ){
        // 自定义guard 名称 对应 auth.guards.$name
        $this->name = $name;

        $this->provider = $provider;
    }

    public function login(User $user)
    {
        // user 对象 => 生成jwt token


    }

    public function check()
    {
       return $this->user() ? true : false;
    }

    /**
     * 判断是否是游客
     * @return bool
     */
    public function guest()
    {
        if (! $this->check()) {
            return false;
        }
        return true;
    }

    /**
     * 获取鉴权id
     * @return int|mixed|string|null
     */
    public function id()
    {
        return $this->user ? $this->user->getAuthIdentifier() : null;
    }

    /**
     * 设置user
     * @param Authenticatable $user
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**获取user对象
     * @return User|Authenticatable|null
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }
        // 从http 中恢复user

        return $this->user = null;
    }

    /**
     * 验证user正确性
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (!$this->user()) {
            return false;
        }

        return $this->provider->validateCredentials($this->user, $credentials);

    }

}

