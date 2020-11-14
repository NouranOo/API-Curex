<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class Pres_item extends Model  
{
    use Notifiable;
     protected $table="pres_items";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pres_id','medicine_title','amount','time','use_untill'
        
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
          
    ];
    protected $casts = [
        'pres_id'=>'int',
        'amount'=>'int',
    ];
    
    public function Prescription(){
        return $this->belongTo('App\Models\Prescription','pres_id');
    }



}
