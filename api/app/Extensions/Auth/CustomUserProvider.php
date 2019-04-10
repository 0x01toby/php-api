<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-9
 * Time: 下午3:18
 */

namespace App\Extensions\Auth;


use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Support\Arrayable;

class CustomUserProvider implements UserProvider
{

    protected $app;
    protected $config;
    /** @var $model User */
    protected $model;

    public function __construct($app, $config)
    {
        $this->app = $app;
        $this->config = $config;
        $this->model = $config['model'];
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return User|null|object
     */
    public function retrieveById($identifier)
    {
        return $this->model::query()->where('email', '=', $identifier)->first();
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     * @return User|null
     */
    public function retrieveByToken($identifier, $token)
    {
        if (empty($user = $this->retrieveById($identifier))) {
            return null;
        }

        $user_remember_token = $user->getRememberToken();

        list($email, $date, $real_token) = explode('|', decrypt($token));
        list($user_email, $user_date, $user_real_token) = explode('|', decrypt($user_remember_token));

        return $user && $user_remember_token && hash_equals($email, $user_email) && hash_equals($real_token, $user_real_token)
            ? $user : null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        /** @var $user User */
        $this->model::query()->where($user->getAuthIdentifierName(), $user->getAuthIdentifier())
            ->update([$user->getRememberTokenName() => $token]);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return User|null|object
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (!(isset($credentials['token']) && is_string($credentials['token']))) {
            return null;
        }

        list($email_base64, $date, $real_token) = explode('|', decrypt($credentials['token']));

        return $this->retrieveByToken(base64_decode($email_base64), $credentials['token']);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {

        list($user_email_base64, $user_login_date, $user_real_token) = $user->getCustomToken();
        list($email_base64, $login_date, $real_token)  = explode('|', decrypt($credentials['token']));

        return hash_equals($user_email_base64 . $user_real_token, $email_base64 . $real_token)
            && (strtotime($user_login_date) - strtotime($login_date) <= 24 * 3600 * 30);
    }


}
