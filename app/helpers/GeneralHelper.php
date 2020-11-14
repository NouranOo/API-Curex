<?php
namespace App\Helpers;

 
use App\Models\User;
use App\Models\Notfication;
use Carbon\Carbon;
class GeneralHelper
{
    protected static $currentUser;
   

    public static function SetCurrentUser($apitoken)
    {
        self::$currentUser = User::where('ApiToken', $apitoken)->first();
        //  self::$currentUser->last_active= Carbon::parse(Carbon::now())->diffForHumans() ;
        self::$currentUser->save();

    }
   
    public static function getcurrentUser()
    {
        // self::$currentUser->last_active= Carbon::parse(Carbon::now())->diffForHumans() ;
                self::$currentUser->save();

        return self::$currentUser;
    }
    public static  function SetNotfication($title,$body,$type,$target_from,$target_to,$notify_to,$notify_target,$Anoynoumes=0)
    {
        $Notfiy = new Notfication();
        $Notfiy->title=$title;
        $Notfiy->body=$body;
        $Notfiy->type=$notify_to;
        $Notfiy->target_from=$target_from;
        $Notfiy->target_to=$target_to;
        $Notfiy->seen=0;
        $Notfiy->save();         
    }
    public static function verifyEmail($user){

        require '../vendor/autoload.php';
        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("hossamyahia1017@gmail.com", "Curex App");
        $email->setSubject("Welcome to Curex  Registerarion");
        $email->addTo($user->Email,$user->UserName);
        $email->addContent("text/plain", " Let's Verify Your Email");
        
         
        $email->addContent(
            "text/html",  view('mail')->with('user',$user)->render()
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
      
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
            }
    
 public static function RecoveryEmail($user){

        require '../vendor/autoload.php';
        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("hossamyahia1017@gmail.com", "Curex App");
        $email->setSubject("Welcome to Curex Registerarion");
        $email->addTo($user->Email,$user->UserName);
        $email->addContent("text/plain", " Let's Verify Your Email");
        
         
        $email->addContent(
            "text/html",  view('mailRecovery')->with('user',$user)->render()
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
      
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
            }
}
