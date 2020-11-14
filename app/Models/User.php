<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class User extends Model  
{
    use Notifiable;
     protected $table="users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile','password','fullname','email','gender','bithday','lat',
        'long','pin','photo','qr','recoverySmsCode','ApiToken','Token'
        
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
            
    ];
    protected $casts = [
        'mobile'=>'int',
        'lat'=>'float',
        'long'=>'float',
        'pin'=>'int',
    
    ];
    public function Notifications(){
        return $this->hasMany('App\Models\Notfication','target_to');
    }

    public function Prescriptions(){
        return $this->hasMany('App\Models\Prescription','user_id');
    }




}
