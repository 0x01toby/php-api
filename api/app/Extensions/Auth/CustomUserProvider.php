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
        return $this->model::query()->find($identifier);
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
        $user = $this->retrieveById($identifier);

        return $user && $user->getRememberToken() && hash_equals($user->getRememberToken(), $token)
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
        if (empty($credentials)) {
            return null;
        }

        $query = $this->model::query();
        foreach ($credentials as  $key => $credential) {
            if ($credential instanceof Arrayable) {
                $query->whereIn($key, $credential);
            } else {
                $query->where($key, $credential);
            }
        }
        return $query->first();
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
        return hash_equals($user->getAuthPassword(), $credentials['password']);
    }


}
