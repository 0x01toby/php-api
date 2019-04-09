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

    /**
     * The user we last attempted to retrieve.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $lastAttempted;

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

        if ($id = $this->request->get('id')) {
            return  $this->user = $this->provider->retrieveById($id);
        }

        return null ;
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
