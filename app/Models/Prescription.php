<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class Prescription extends Model  
{
    use Notifiable;
     protected $table="prescriptions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','doctor_id'
        
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
          
    ];
    protected $casts = [
        'user_id'=>'int',
        'doctor_id'=>'int',
       

    ];
    public function User(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function Pres_items(){
        return $this->hasMany('App\Models\Pres_item','pres_id');
    }
    public function Doctor(){
        return $this->belongsTo('App\Models\User','doctor_id');
    }



}
