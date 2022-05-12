<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    // get password change page
    public function getChangePassword()
    {
        return view('auth.passwords.change');
    }

    // make a process for pw change
    public function storeChangePw(Request $request)
    {
        if (!(Hash::check($request->get('oldPw'), Auth::user()->password))) {
            return redirect()->back()->with("error", "Your current password does not matches with the password.");
        }

        if (strcmp($request->get('oldPw'), $request->get('newPw')) == 0) {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }

        if (strcmp($request->get('newPw'), $request->get('cfmPw')) != 0) {
            return redirect()->back()->with("error", "New Password and Confirm Password cannot be same as your current password.");
        }

        $validatedData = $request->validate([
            'oldPw' => 'required',
            'newPw' => 'required|string|min:8',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('newPw'));
        $user->save();

        return redirect()->back()->with("success","Password successfully changed!");
    }
}

// public function changePasswordPost(Request $request) {
//     if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
//         // The passwords matches
//         return redirect()->back()->with("error","Your current password does not matches with the password.");
//     }

//     if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
//         // Current password and new password same
//         return redirect()->back()->with("error","New Password cannot be same as your current password.");
//     }

//     $validatedData = $request->validate([
//         'current-password' => 'required',
//         'new-password' => 'required|string|min:8|confirmed',
//     ]);

//     //Change Password
//     $user = Auth::user();
//     $user->password = bcrypt($request->get('new-password'));
//     $user->save();

//     return redirect()->back()->with("success","Password successfully changed!");
// }
