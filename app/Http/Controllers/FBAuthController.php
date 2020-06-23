<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2020/6/19
 * Time: 下午 04:55
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;

class FBAuthController extends Controller
{
    /**
     *  Auth Redirect
     */
    public function facebookLogin()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * FB Auth callback and login/create user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function facebookCallback()
    {
        if (request()->error == "access_denied") {
            throw new \Exception('授權失敗');
        }

        $facebookUser = Socialite::driver('facebook')->user();

        //取得 Facebook 資料
        $facebookUserId = $facebookUser->getId();
        $facebookUserName = $facebookUser->getName();
        $facebookUserEmail = $facebookUser->getEmail();

        if (!$facebookUserEmail) {
            throw new \Exception('未授權取得使用者Email');
        }

        if ($user = User::getSocialAccount('facebook_id', $facebookUserId)) {
            Auth::login($user);
            return redirect('/home');
        }

        if ($user = User::where('email', $facebookUserEmail)->first()) {

            $user->facebook_id = $facebookUserId;
            $user->save();

        } else {

            $fields = [
                'email' => $facebookUserEmail,
                'name' => $facebookUserName,
                'password' => Hash::make(uniqid()),
                'facebook_id' => $facebookUserId,
            ];
            //新增會員資料
            $user = User::create($fields);
        }

        Auth::login($user);

        return redirect('/home');

    }
}
