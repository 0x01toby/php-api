<?php


namespace App\Extensions\Auth;


use App\Extensions\Auth\Jwt\JwtService;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Lumen\Http\Request;

class JwtGuard implements Guard
{
    /** @var User $user */
    protected $user;

    /** 配置文件 */
    protected $name;

    /** @var UserProvider 用户 */
    protected $provider;

    /** @var $request Request */
    protected $request;

    /** @var $jwt_token  */
    protected $jwt_token;

    public function __construct(
        $name,
        UserProvider $provider      // authServerProvider 中 provider 配置
    ){
        // 自定义guard 名称 对应 auth.guards.$name
        $this->name = $name;

        $this->provider = $provider;
    }

    /**
     * 设置request
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 用户登陆
     * @param User $user
     */
    public function login(User $user)
    {
        // user 对象 => 生成jwt token
        $this->jwt_token = app(JwtService::class)->getToken($user->getAttribute("email"));
        $this->setUser($user);
    }

    /**
     * 获取jwt token
     * @return string
     */
    public function getToken()
    {
        return $this->jwt_token;
    }

    /**
     * 检查用户是否有效
     * @return bool
     */
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
        return ! $this->check();
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

        $jwt_token = $this->getJwtTokenFromRequest();
        if (empty(trim($jwt_token))) {
            return $this->user = null;
        }
        // 从 http request 中恢复用户信息
        $claims = $this->provider->retrieveByJwtToken($jwt_token);
        return $this->user = $this->provider->retrieveById(Arr::get($claims, "uid", null));
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

    /**
     * 获取jwt token
     * @return string
     */
    protected function getJwtTokenFromRequest()
    {
        if ($authorization = $this->request->header('Authorization')) {
            if (Str::contains($authorization, "Bearer")) {
                return $this->jwt_token = trim(str_replace("Bearer", "", $authorization));
            }
        }

        if (trim($this->request->query("Authorization", ""))) {
            return $this->jwt_token = trim($this->request->query("Authorization", ""));
        }

        return $this->jwt_token = $this->request->cookie("token", "");
    }

}

