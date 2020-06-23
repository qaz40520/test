<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2020/6/22
 * Time: 下午 12:20
 */

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LINEAuthController extends Controller
{
    /**
     *  Auth Redirect
     */
    public function lineLogin()
    {
        return Socialite::driver('line')
            ->redirect();
    }

    /**
     * Line Auth callback and login/create user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function lineCallback()
    {
        if (isset(request()->error)) {
            throw new \Exception('授權失敗');
        }

        $lineUser = Socialite::driver('line')->user();
        $lineUserEmail = $lineUser->getEmail();
        $lineUserId = $lineUser->getId();
        $lineUserName = $lineUser->getName();

        if (!$lineUserEmail) {
            throw new \Exception('未授權取得使用者Email');
        }

        if ($user = User::getSocialAccount('line_id', $lineUserId)) {
            Auth::login($user);
            return redirect('/home');
        }

        if ($user = User::where('email', $lineUserEmail)->first()) {

            $user->line_id = $lineUserId;
            $user->save();

        } else {

            $fields = [
                'email' => $lineUserEmail,
                'name' => $lineUserName,
                'password' => Hash::make(uniqid()),
                'line_id' => $lineUserId,
            ];
            //新增會員資料
            $user = User::create($fields);
        }

        Auth::login($user);

        return redirect('/home');
    }
}
