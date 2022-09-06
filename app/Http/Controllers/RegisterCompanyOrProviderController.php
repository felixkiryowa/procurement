<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\SecretQuestion;
use App\Models\ProviderOrCompany;
use App\Models\User;
use App\Notifications\SendSecretCodeNotification;
use App\Traits\LogActivityTrait;



class RegisterCompanyOrProviderController extends Controller
{

   use LogActivityTrait;
    
    public function index() {
        return Inertia::render('RegisterAsCompanyOrProvider/RegisterAsCompanyOrProvider', [
          'secret_questions' => SecretQuestion::select('id', 'name')->get()
        ]);
     }


     public function sendSecretCodeToEmail(Request $request)
     {
         $provider_or_company = ProviderOrCompany::create([
           'email' => $request->email,
           'secret_code_sent' => $this->generateUniqueCode()
         ]);


         $message = 'Your secret code to validate your email is '.$provider_or_company->secret_code_sent;


         ProviderOrCompany::find($provider_or_company->id)->notify(new SendSecretCodeNotification($message));


         return response()->json(['success' => true, 'provider_or_company_id' => $provider_or_company->id,
          'secret_code_sent' => $provider_or_company->secret_code_sent,
          'message' => 'An Email has been sent for you to verify your email address'], 200);

     }

     public function generateUniqueCode()
     {
        $code = random_int(100000, 999999);
        
         return $code;
     }


     public function createProviderOrCompany(Request $request) {

        $role = Role::select('id', 'name')->where('id', $request->account_type)->first();
        
        User::create([
          'account_type_id' => $request->account_type,
          'organisationName' => $request->organisationName,
          'procurementCategory' => json_encode($request->procurementCategory),
          'briefDescription' => $request->briefDescription,
          'userName' => $request->userName,
          'password' => Hash::make($request->password),
          'email' => $request->email,
          'companyPhoneNumber' => $request->companyPhoneNumber,
          'alternativeCompanyPhoneNumber' => $request->alternativeCompanyPhoneNumber,
          'secretQuestion' => $request->secretQuestion,
          'secretAnswer' => $request->secretAnswer,
          'challengeAnswer' => $request->challengeAnswer,
          'country' => $request->country,
          'registrationNumber' => $request->registrationNumber,
          'taxId' => $request->taxId,
          'codeSentToEmail' => $request->codeSentToEmail,
          'firstName' => $request->firstName,
          'lastName' => $request->lastName,
          'address' => $request->address,
          'city' => $request->city,
          'originCountry' => $request->originCountry,
          'region' => $request->region,
          'zip_code' => $request->zipCode,
          'user_role' => $role->name
        ]);

        return response()->json(['success' => true,
        'message' => 'Successfully Created An Account'], 200);
     }
}
