<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname','middlename','extensionname','idno','accesslevel','email', 'password','gender'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    function dedits(){
        return $this->hasMany('\App\Dedit');
    }
    
    public function usersPosition(){
        return $this->hasMany('\App\UsersPosition','idno','idno');
    }
    
}
