<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-9
 * Time: 下午2:54
 */

namespace App\Extensions\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Date;

class CustomGuard implements Guard
{

    use GuardHelpers;

    /** 配置文件 */
    protected $name;

    /** @var $request Request */
    protected $request;

    /** @var UserProvider 用户 */
    protected $provider;

    protected $logged_out = false;

    protected $token = "";
    /**
     * The user we last attempted to retrieve.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $lastAttempted;

    public function __construct(
        $name,
        UserProvider $provider      // authServerProvider 中 provider 配置
    ){
        // 自定义guard 名称 对应 auth.guards.$name
        $this->name = $name;

        $this->provider = $provider;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return User|null|object
     */
    public function user()
    {

        if ($this->logged_out) {
            return null;
        }

        if (! is_null($this->user)) {
            return $this->user;
        }

        // 走登录逻辑
        if ($id = $this->request->get('email')) {
            return  $this->user = $this->provider->retrieveById($id);
        }

        return null ;
    }

    /**
     * 登录则更新当前用户的最后登录时间
     * @param User $user
     */
    public function login(User $user)
    {
        list($user_email, $user_date, $user_real_token) = explode('|', decrypt($user->getRememberToken()));

        $token = encrypt(
            $user_email . "|" .
            Date::createFromTimestamp(time())->toDateTimeLocalString() . "|" .
            $user_real_token
        );

        $this->provider->updateRememberToken($user,  $token);

        $this->token = $token;

        $user->setAttribute($user->getRememberTokenName(), $token);

        $this->setUser($user);

        // Login Event
    }

    public function getToken()
    {
        return $this->token;
    }

    public function id()
    {
        if ($this->logged_out) {
            return null ;
        }

        return $this->user ? $this->user->getAuthIdentifier() : null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        return ! is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        app()->instance(User::class, $user);
    }

    public function logout()
    {
        $this->logged_out = true;
    }

    /**
     * Set the current request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

}
