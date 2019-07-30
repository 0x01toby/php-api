<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-10
 * Time: 下午1:56
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Cookie;

class LoginController extends Controller
{
    /** @var $user User */
    protected $user;

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (empty($email) || empty($password)) {
            return $this->jsonFailed(10010, 'login params failed .');
        }

        if (empty($this->user = User::query()->where('email', $email)->first())) {
            return $this->jsonFailed(10011, 'login failed.');
        }

        if (!hash_equals($this->user->getAuthPassword(), $password . $this->user->salt)) {
            return $this->jsonFailed(1012, 'login failed.');
        }

        Auth::login($this->user);

        return response()->json([
            'data' => $this->user,
            'code' => 0,
            'message' => 'success'
        ])->withCookie(new Cookie('custom_token', $this->user->custom_token));
    }

}
