<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        // TODO: Implement validation for email address

        try {
            $generated_code = mt_rand(100000, 999999);

            $code = PasswordResetCode::create([
                'email' => $request->email,
                'code' => $generated_code
            ]);

            Mail::to($request->user)->queue();

            return response()->json([
                'message' => trans('messages.password_reset.code_sent')
            ], 200);
        } catch (\Throwable $th) {

        }
    }
}
