<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    /**
     * Update Profile page display
    */
    public function editProfile(): View
    {
        $admin = Auth::user();

        return view('pages.encoder.admin.profile', compact('admin'));
    }

    /**
     * Update Profile
    */
    public function updateProfile(AdminProfileUpdateRequest $request)
    {
        $admin = Auth::user();

        $admin->password = Hash::make($request->input('password'));

        $admin->save();

        return response()->json([
            'response' => Response::HTTP_OK,
            'message' => "Saved successfully"
        ]);
    }
}
