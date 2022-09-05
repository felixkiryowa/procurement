<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changepasswordview()
    {

        $userID = Auth::user()->id;
        $user = User::where('id',  $userID )->first();

        return Inertia::render('Users/ChangePassword', [
            'user' => $user
        ]);
    }

    public function store(Request $request)

    {


        $validator = Validator::make($request->all(), [
            'new_password' => ['required'],

            'new_confirm_password' => ['same:new_password'],


        ]);

        if ($validator->fails()) {
            return response()->json(['isvalid'=>false,'errors'=>$validator->messages()]);
        }



        User::find(Auth::user()->id)->update(['password'=> Hash::make($request->new_password)]);


        return response()->json(['success' => true,
          'message' => 'Password Changed Successfully'], 200);

    }

}
