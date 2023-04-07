<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function saveToken(Request $request)
    {
        $user = User::findOrFail(16);
        $user->device_token = $request->token;
        $user->save();
        return response()->json(['token saved successfully.',$user]);
    }
    
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        //return ok("",$firebaseToken);

        $SERVER_API_KEY = 'AAAAL42TA20:APA91bFLryBxBnkNObm2q-6VqpP7s0DPWEljYl0gpP2rsn8aBb8V_JlkbaNwFP83OSaymwC0_af9f8ZWtYgTd7gGZKbi7mSu0WALglaKhD6zEV3nBYfkL738rdebONBJBAQe44aFPzRZ';

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => $request->title,
                    "body" => $request->body,
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            dd($response);
    }
}
