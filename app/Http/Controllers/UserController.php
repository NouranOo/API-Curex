<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use validator;

class UserController extends Controller
{

    public $user;
    public $apiResponse;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserInterface $user, ApiResponse $apiResponse)
    {
        $this->user = $user;
        $this->apiResponse = $apiResponse;
    }

    /*
     * Auth section
     */
    public function Register(Request $request)
    {
        $rules = [
            'mobile' => 'required|unique:users|min:11', 
            'password' => 'required',
            'fullname' => 'required',
            'email' => 'required|unique:users',
            'gender_id' => 'required',
            'bithday' => 'required',
            'lat'=>'required',
            'long'=>'required',
            'ApiKey' => 'required',
        


        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }

        $data = $request->all();
        $result = $this->user->Register($data);
        return $result->send();
    }

    public function Login(Request $request)
    {
        $rules = [
            'mobile' => 'required',
            'password' => 'required',
            'ApiKey' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }
        $data = $request->all();
        $result = $this->user->Login($request->all());
        return $result->send();

    }
 
   
   
    public function ForgetPasswordSendRecoveryCode(Request $request)
    {
        $rules = [
           
            'mobile' => '',
            'ApiKey' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }
        $result = $this->user->ForgetPasswordSendRecoveryCode($request->all());
        return $result->send();

    }
    public function CheckRecoveryCode(Request $request)
    {
        $rules = [
            'mobile' => 'required',
            'recoverySmsCode' => 'required',
            'ApiKey' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }

        $data = $request->all();
        $result = $this->user->CheckRecoveryCode($data);
        return $result->send();
    }
    public function SetNewPassword(Request $request)
    {
        $rules = [
            'mobile' => 'required',
            'NewPassword' => 'required|min:8',
            // 'RecoveryCode' => 'required',
            'ApiKey' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }

        $data = $request->all();
        $result = $this->user->SetNewPassword($data);
        return $result->send();
    }
    public function getHome(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->getHome($data);
        return $result->send();
    }
    public function scanMyQr(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->scanMyQr($data);
        return $result->send();
    }
    public function showMyPin(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->showMyPin($data);
        return $result->send();
    }
    public function showAllPres(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->showAllPres($data);
        return $result->send();
    }
    public function getAllNotifications(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->getAllNotifications($data);
        return $result->send();
    }
    public function makeNotificationRead(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'Notification_id'=>'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->makeNotificationRead($data);
        return $result->send();
    }
    public function unReadNotifcation(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'Notification_id'=>'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->unReadNotifcation($data);
        return $result->send();
    }
    public function updatePhoto(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'photo' =>'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->except('photo');

        if ($request->hasFile('photo')) {

            $file = $request->file("photo");
            $filename = str_random(6) . '_' . time() . '_' . $file->getClientOriginalName();
            $path = 'ProjectFiles/UserPhotos';
            $file->move($path, $filename);
            $data['photo'] = $path . '/' . $filename;
        }
        $result = $this->user->updatePhoto($data);
        return $result->send();
    }
    public function updateMobile(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'mobile' =>'required|min:11',
            'password'=>'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->updateMobile($data);
        return $result->send();
    }
    public function updatePassword(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'oldPassword' =>'required',
            'newPassword'=>'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->updatePassword($data);
        return $result->send();
    }
    public function updateEmail(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'email' =>'required',
            'password'=>'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->updateEmail($data);
        return $result->send();
    }
    public function updateGender(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'gender_id' =>'required',
             
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->updateGender($data);
        return $result->send();
    }
    public function updateBirthday(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'bithday' =>'required',
             
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->updateBirthday($data);
        return $result->send();
    }
    public function updateAddress(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'lat' =>'required',
            'long' =>'required',
             
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->updateAddress($data);
        return $result->send();
    }
    public function DeleteMyAccount(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            
             
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->DeleteMyAccount($data);
        return $result->send();
    }
    public function contactUs(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required',
            
             
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
       
        $data = $request->all();
        $result = $this->user->contactUs($data);
        return $result->send();
    }
}
