<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $code = rand(100000, 999999);

        Mail::raw("Your verification code is: $code", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Verification Code');
        });

        return response()->json([
            'message' => 'Verification code sent successfully.',
            'code' => $code // For testing purposes, include this in the response. In production, store it securely.
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|integer',
        ]);

        // Retrieve the code from the session or database
        $storedCode = $request->session()->get('verification_code');

        if ($request->code == $storedCode) {
            return response()->json(['message' => 'Code verified successfully.']);
        }

        return response()->json(['message' => 'Invalid code.'], 400);
    }

}
