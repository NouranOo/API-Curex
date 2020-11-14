<?php
namespace App\Repositories;

use App\Helpers\ApiResponse;
use App\helpers\FCMHelper;
use App\Helpers\GeneralHelper;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Models\Contact_us;
use App\Models\Prescription;
use App\Models\Notfication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\UserSavedPlace;

class UserRepository implements UserInterface
{

    public $apiResponse;
    public $generalhelper;
    public function __construct(GeneralHelper $generalhelper, ApiResponse $apiResponse)
    {
        $this->generalhelper = $generalhelper;

        $this->apiResponse = $apiResponse;

    }
    /** auth section
     *
     */
    public function Register($data)
    {

        try {
           
            
            $data['ApiToken'] = base64_encode(str_random(40));
            $data['pin']=mt_rand(100, 999);;
            $data['password']= app('hash')->make($data['password']);
            $user = new User();
            $user->mobile = $data['mobile'];
            $user->password = $data['password'];
            $user->fullname= $data['fullname'];
            $user->email= $data['email'];
            $user->gender_id= $data['gender_id'];
            $user->bithday= $data['bithday'];
            $user->lat= $data['lat'];
            $user->long= $data['long'];
            $user->ApiToken= $data['ApiToken'];
            $user->pin= $data['pin'];
            $user->userType_id= 1;

            //qr
            $user->save();
            $newUser = User::where('id',$user->id)->first();
           
           

            
        } catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

        // $verify = GeneralHelper::verifyEmail($user);
        return $this->apiResponse->setSuccess('registerd successfully')->setData($newUser);

    }

    public function Login($data)
    {
        try {
        
            $user = User::where('mobile', $data['mobile'])->first();

            if ($user) {
                $check = Hash::check($data['password'], $user->password);
                if ($check) {
                    
                        try {
                            $user->update(['ApiToken' => base64_encode(str_random(40))]);
                            $user->save();                        
                        } catch (\Illuminate\Database\QueryException $ex) {
                            return $this->apiResponse->setError($ex->getMessage())->setData();
                        }
                        return $this->apiResponse->setSuccess('loged in successfully')->setData($user);

                    }else {
                    return $this->apiResponse->setError("Password not Correct!")->setData();
                }

            } else {
                return $this->apiResponse->setError("Your Mobile not found!")->setData();
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
   
  
    

    public function ForgetPasswordSendRecoveryCode($data)
    {
        try{
            $user = User::where('mobile', $data['mobile'])->first();
        
            if ($user) {
                try {
                    // $data['RecoveryCode'] = base64_encode(str_random(2));
                    $data['recoverySmsCode'] = base64_encode(str_random(6));
                    $user->RecoverySmsCode = $data['recoverySmsCode'];
                    // $user->RecoveryCode = $data['RecoveryCode'];
                    $user->save();
                    // config 
                    // $message = new VodafoneAdapter([
                    //     'accountId' => '101007990',
                    //     'password' => 'Vodafone.1',
                    //     'secretKey' => 'A329B545CCAA4CE292681BCE6A2DE755',
                    //     'senderName' => 'Curex',
                    // ]);
                    
                    // $data = $message->send([
                    //     'to' => $user->Mobile,
                    //     'text' => 'it\'s your Recovery code is '. $data['recoverySmsCode'] ,
                    // ]);

                } catch (\Illuminate\Database\QueryException $ex) {
                    return $this->apiResponse->setError($ex->getMessage())->setData();
                }
                // $verify = GeneralHelper::RecoveryEmail($user);
                return $this->apiResponse->setSuccess("Check Your Phone For RecoverySmsCode")->setData($user);
            } else {
                return $this->apiResponse->setError("Your email not found!")->setData();
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
    }

    public function CheckRecoveryCode($data)
    {
        try{
            $user = User::where('mobile', $data['mobile'])->where('recoverySmsCode', $data['recoverySmsCode'])->first();
            if ($user) {

                return $this->apiResponse->setSuccess("Correct SmsCode")->setVerify("true");

            } else {
                return $this->apiResponse->setError("Check Your Mail or SmsCode Again ")->setVerify("false");

            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }


    }
    public function SetNewPassword($data)
    {
        try{
       
            $user = User::where('mobile', $data['mobile'])->first();

            if ($user) {
                $user = User::find($user->id);
                $data['NewPassword'] = app('hash')->make($data['NewPassword']);
                $user->password = $data['NewPassword'];
                $user->save();
                return $this->apiResponse->setSuccess("Password Changed Successfuly")->setVerify("true");

            } else {
                return $this->apiResponse->setError("Email not found ")->setVerify("false");

            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
    public function getHome($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $Prescriptions = Prescription::where('user_id',$user->id)->with(['Doctor','Pres_items'])->paginate(3);
            if($Prescriptions){
                return $this->apiResponse->setSuccess("Fetch Home Successfuly")->setData( $Prescriptions);
            }else{
                return $this->apiResponse->setError("Not found Prescriptions ")->setVerify("false");
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
    public function scanMyQr($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $qr = $user->qr;
            if($user){
                return $this->apiResponse->setSuccess("Fetch Qr Successfuly")->setData( ['MyQr'=>$qr] );
            }else{
                return $this->apiResponse->setError("Not found qr ")->setVerify("false");
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
    public function showMyPin($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $pin = $user->pin;
            if($user){
                return $this->apiResponse->setSuccess("Fetch Qr Successfuly")->setData( ['MyPin'=>$pin ]);
            }else{
                return $this->apiResponse->setError("Not found pin ")->setVerify("false");
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
    public function showAllPres($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $Prescriptions = Prescription::where('user_id',$user->id)->with(['Doctor','Pres_items'])->get();
            if($Prescriptions){
                return $this->apiResponse->setSuccess("Fetch Prescriptions Successfuly")->setData( $Prescriptions);
            }else{
                return $this->apiResponse->setError("Not found Prescriptions ")->setVerify("false");
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
    public function getAllNotifications($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $nots = Notfication::where('target_to',$user->id)->get();
            if($nots){
                return $this->apiResponse->setSuccess("Fetch Notifications Successfuly")->setData( $nots);
            }else{
                return $this->apiResponse->setError("Not found Prescriptions ")->setVerify("false");
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
    public function makeNotificationRead($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $not = Notfication::where('id',$data['Notification_id'])->first();
            if($not){
                $not->seen = 1;
                $not->save();
                return $this->apiResponse->setSuccess("Make Notification Read Successfuly")->setData( $not);
            }else{
                return $this->apiResponse->setError("Not found Notification ")->setVerify("false");
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
    public function unReadNotifcation($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $not = Notfication::where('id',$data['Notification_id'])->first();
            if($not){
                $not->seen = 0;
                $not->save();
                return $this->apiResponse->setSuccess("UnRead Notification  Successfuly")->setData( $not);
            }else{
                return $this->apiResponse->setError("Not found Notification ")->setVerify("false");
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
    public function updatePhoto($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $user->photo = $data['photo'];
            $user->save();
           
            
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Updated Photo Successfuly")->setData($user);
    }
    public function updateMobile($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
           
            if ($user) {
                $check = Hash::check($data['password'], $user->password);
                if ($check) {
                    $user->mobile = $data['mobile'];
                    $user->save();                     
                        
                }else {
                return $this->apiResponse->setError("Password not Correct!")->setData();
                }

            } else {
                return $this->apiResponse->setError("Your Mobile not found!")->setData();
            }
            
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Updated mobile Successfuly")->setData($user);
    }
    public function updatePassword($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $check = Hash::check($data['oldPassword'], $user->password);
            if ($check) {
                $data['newPassword']= app('hash')->make($data['newPassword']);
                $user->password = $data['newPassword'];
                $user->save();                     
                    
            }else {
            return $this->apiResponse->setError("Password not Correct!")->setData();
            }
   
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Updated Password Successfuly")->setData($user);
    }
    
    public function updateEmail($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $check = Hash::check($data['password'], $user->password);
            if ($check) {
               
                $user->email = $data['email'];
                $user->save();                     
                    
            }else {
            return $this->apiResponse->setError("Password not Correct!")->setData();
            }
   
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Updated Email Successfuly")->setData($user);
    }
    public function updateGender($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();

            $user->gender_id = $data['gender_id'];
            $user->save();                     

        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Updated Gender Successfuly")->setData($user);
    }  
    public function updateBirthday($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();

            $user->bithday = $data['bithday'];
            $user->save();                     

        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Updated Bithday Successfuly")->setData($user);
    }  
    public function updateAddress($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();

            $user->lat = $data['lat'];
            $user->long = $data['long'];
            $user->save();                     

        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Updated Address Successfuly")->setData($user);
    }  
    public function DeleteMyAccount($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();

            $user->delete();                  

        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Deleted User Successfuly")->setData();
    } 
    public function contactUs($data)
    {
        try{
            $user = GeneralHelper::getcurrentUser();
            $contactUs =  new Contact_us();        
            $contactUs->name = $data['name'];
            $contactUs->phone = $data['phone'];
            $contactUs->subject = $data['subject'];
            $contactUs->message = $data['message'];
            $contactUs->fromUser_id = $user->id;
            $contactUs->save();
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Contacted Us Successfuly")->setData( $contactUs);
    }  
}

