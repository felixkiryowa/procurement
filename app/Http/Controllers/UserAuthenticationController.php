<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Traits\LogActivityTrait;
use App\Models\User;
use App\Models\Role;

class UserAuthenticationController extends Controller
{
    use LogActivityTrait;


    public function loginUser(Request $request) {
      try {

            $validator = Validator::make($request->all(), [
             'email' => 'required|email',
             'password' => 'required'
            ]);
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            $user = User::select('id', 'firstName', 'lastName', 'account_type_id',
            'email', 'last_time_login', 'status')
            ->where('email', $request->email)->first();

            if($user->status === 0) {
                return redirect()->to('/')->with('auth_error', 'User Account Deactivated');
            }else {
                if (Auth::attempt($credentials)) {

                    $role = Role::select('id', 'name')->where('id', $user->account_type_id)->first();



                    Auth::login($user);
                    $this->destroyPreviousSession($user);

                    $user->last_time_login = date("Y-m-d h:i:s");
                    $user->save();
                    $details = $user->firstName . ' '.$user->lastName. ' has logged in';
                    $this->addToLog($request->ip(), $details);

                    $user_redirects = collect([
                        'Provider' => '/company/bids',
                        'Company' => '/manage/company/users',
                        'Procurement Officer' => '/procurement/officer',
                        'Super Systems Administrator' => '/manage/companies'
                    ]);

                    return redirect($user_redirects->get($role->name, '/registered/companies'));
                }else {
                    return redirect()->to('/')->with('auth_error', 'Invalid Email Or Password');
                }
            }

      }catch(\Exception $e) {
         Log::channel('daily')->error('Log message' , array('message' => $e->getMessage(), 'type' => 'Handling the errors'));
      }
    }

    public function destroyPreviousSession(User $user): void
    {
        $previous_session = $user->last_session_id;
        if ($previous_session) {
            \Session::getHandler()->destroy($previous_session);
        }
        $user->last_session_id = \Session::getId();
        $user->save();
    }

    //Funtion to logout users
    public function logOutUser(Request $request)
    {
        try {

            $loggedInUser = User::findOrFail(Auth::user()->id);
            $loggedInUser->save();

            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['success' => true, 'message' => 'Successfully Logged Out A User'], 200);
        }catch(\Exception $e) {
            Log::channel('daily')->error('Log message', array('message' => $e->getMessage(), 'type' => 'Handling the errors'));
        }
    }


    public function activateUser($id) {

        $user = User::select('id', 'status')->where('id', $id)->first();
        $user->status = 1;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Successfully Activated User'], 200);

    }


    public function deActivateUser($id) {
        $user = User::select('id', 'status')->where('id', $id)->first();
        $user->status = 0;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Successfully DeActivated User'], 200);

    }
}
