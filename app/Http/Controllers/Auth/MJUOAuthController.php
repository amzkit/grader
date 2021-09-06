<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;


class MJUOAuthController extends Controller
{
    //

    public function redirect(Request $request){

        $queries = http_build_query([
            'client_id'     =>  config("oauth.client_id"),
            'redirect_uri'  =>  config("oauth.callback_redirect_uri"),
            'response_type' =>  'code',
            'prompt'        =>  'select_account'
        ]);
        return redirect(config("oauth.authorize_url")."?".$queries);
    }

    public function callback(Request $request){
        $response = Http::post(config("oauth.request_token_url"), [
            'grant_type'    =>  'authorization_code',
            'client_id'     =>  config("oauth.client_id"),
            'client_secret' =>  config("oauth.client_secret"),
            'redirect_uri'  =>  config("oauth.callback_redirect_uri"),
            'code'          =>  $request->code,
        ]);

        $response = $response->json();

        // Use Access Token to get User infomation
        $accessToken = $response['access_token'];
        $user = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
        ])->get(config('oauth.request_user_url'));

        $user_tmp = $user->json()['user'];

        $user = array();
        $user['id'] = $user_tmp['id'];
        $user['name'] = $user_tmp['name'];
        $user['email'] = $user_tmp['email'];
        $user['password'] = \Hash::make(rand());
        $user['role']  = $user_tmp['roles'];
        //dd($user);
        $user = \App\Models\User::firstOrCreate(['email'=>$user['email']],$user);

        \Auth::login($user);

        $user->access_token = $accessToken;
        $user->save();

        if(count($user_tmp['roles'])>1){
            //return redirect('login/mju/select_role', 302)->with('user',$user->json());
            return redirect()->route('select_role', ['user'=>$user_tmp]);
        }
        //dd($response);
/*
        $token_parts = explode('.', $accessToken);
        $token_header = $token_parts[1];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        $token_id = $token_header_array['jti'];
*/
        //$user->access_token = (new PhpParser())->parse($accessToken)->getClaims()['jti']->getValue();

        $user->access_token = $accessToken;
        //dd($token_id);
        $user->save();

        return redirect('/');

    }

    public function selectRole(Request $request){
        //dd($request->user);
        return view('auth.select_role', ['user'=>$request->user]);
    }

    public function logout(Request $request){

        //dd('xx');
        //$response = Http::delete('https://oauth.digital.mju.ac.th/oauth/tokens/'.$request->user()->token_id);

        $access_token = \Auth::user()->access_token;
        //dd($access_token);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$access_token,
        ])->get('https://oauth.digital.mju.ac.th/api/logout');

        //dd($response->json());
        if(isset($response->json()['success']) && $response->json()['success']){
            \Auth::logout();
            return redirect('/');
        }else{
            \Auth::logout();

            return back();
        }


    }
}
