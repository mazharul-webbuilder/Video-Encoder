<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginReqeust;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    /**
     * Show Admin Login page
    */
    public function login(): View
    {
       return view('pages.encoder.auth.login');
    }

    /**
     * Auth-check
    */
    public function authCheck(LoginReqeust $reqeust)
    {
        try {
            $credentials = $reqeust->validated();

            $admin = Auth::attempt($credentials);

            if ($admin) {
                return \response()->json([
                    'response' => Response::HTTP_OK,
                    'route' => route('dashboard')
                ]);
            } else{
                return response()->json([
                    "response" => 401,
                    'message' => "Invalid Credentials"
                ]);
            }

        } catch (\Exception $exception) {
            Log::error("Login exception" . $exception->getMessage());
        }
    }


    /**
     * Logout And Redirect to Login page
    */
    public function logout()
    {
        try {
            Auth::logout();

            return redirect()->route('admin.login');

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
