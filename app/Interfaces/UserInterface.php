<?php

namespace App\Interfaces;

interface UserInterface
{

    public function Register($data);
    public function Login($data);
    public function ForgetPasswordSendRecoveryCode($data);
    public function CheckRecoveryCode($data);
    public function SetNewPassword($data);
    public function getHome($data);
    public function scanMyQr($data);
    public function showMyPin($data);
    public function showAllPres($data);
    public function getAllNotifications($data);
    public function makeNotificationRead($data);
    public function unReadNotifcation($data);
    public function updatePhoto($data);
    public function updateMobile($data);
    public function updatePassword($data);
    public function updateEmail($data);
    public function updateGender($data);
    public function updateBirthday($data);
    public function updateAddress($data);
    public function DeleteMyAccount($data);
    public function contactUs($data);
    
    
    
    
    



}
