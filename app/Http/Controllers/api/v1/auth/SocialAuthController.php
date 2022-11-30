<?php

namespace App\Http\Controllers\api\v1\auth;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function App\CPU\translate;

class SocialAuthController extends Controller
{
    public function social_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'unique_id' => 'required',
            'email' => 'required',
            'medium' => 'required|in:google,facebook',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $client = new Client();
        $token = $request['token'];
        $email = $request['email'];
        $unique_id = $request['unique_id'];

        try {
            if ($request['medium'] == 'google') {
                $res = $client->request('GET', 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $token);
                $data = json_decode($res->getBody()->getContents(), true);
            } elseif ($request['medium'] == 'facebook') {
                $res = $client->request('GET', 'https://graph.facebook.com/' . $unique_id . '?access_token=' . $token . '&&fields=name,email');
                $data = json_decode($res->getBody()->getContents(), true);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => 'wrong credential.']);
        }

        if (strcmp($email, $data['email']) === 0) {
            $name = explode(' ', $data['name']);
            if (count($name) > 1) {
                $fast_name = implode(" ", array_slice($name, 0, -1));
                $last_name = end($name);
            } else {
                $fast_name = implode(" ", $name);
                $last_name = '';
            }
            $user = User::where('email', $email)->first();
            if (isset($user) == false) {
                $user = User::create([
                    'f_name' => $fast_name,
                    'l_name' => $last_name,
                    'email' => $email,
                    'phone' => '',
                    'password' => bcrypt($data['id']),
                    'is_active' => 1,
                    'login_medium' => $request['medium'],
                    'social_id' => $data['id'],
                    'is_phone_verified' => 0,
                    'is_email_verified' => 1,
                    'temporary_token' => Str::random(40)
                ]);
            } else {
                $user->temporary_token = Str::random(40);
                $user->save();
            }

            $token = self::login_process_passport($user, $user->email, $user->social_id);
            if ($token != null) {
                return response()->json(['token' => $token]);
            }
            return response()->json(['error_message' => translate('Customer_not_found_or_Account_has_been_suspended')]);
        }

        return response()->json(['error' => translate('email_does_not_match')]);
    }

    public static function login_process_passport($user, $email, $password)
    {
        $data = [
            'email' => $email,
            'password' => $password
        ];

        if (isset($user) && $user->is_active && auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
        } else {
            $token = null;
        }

        return $token;
    }

}
