<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\addMediaCollection;
use Spatie\MediaLibrary\HasMedia\addMediaConversion;
use Spatie\MediaLibrary\Models\Media;

class User extends Authenticatable implements HasMedia,JWTSubject
{
    use Notifiable, HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'profile_for',
        'marital_status',
        'aadhaar',
        'password',
        'blood_group',
        'dob',
        'birth_place',
        'birth_time',
        'height',
        'manglik',
        'qualification',
        'occupation',
        'income',
        'work_place',
        'gotra',
        'caste',
        'gotra_nanihal',
        'organisation_name',
        'city',
        'state',
        'district',
        'mobile',
        'pin',
        'otp',
        'verify_token',
        'working',
        'lang'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verify_token'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setGenderAttribute($value)
    {
        return $this->attributes['gender'] = strtoupper($value);
    }


    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }


    protected function getArrayableAttributes()
    {

        foreach ($this->attributes as $key => $value) {
           
            if (is_null($value)) {
                $this->attributes[$key] = '';
            }
        }

        return $this->getArrayableItems($this->attributes);
    }

 protected function getAvatarThumbAttribute($value)
 {

            if($value == "" || is_null($value)){
                return  url('assets/images/users/default-user.png');
            }
          return $value; 
 }

 protected function getAvatarMediumAttribute($value)
 {

            if($value == "" || is_null($value)){
                return  url('assets/images/users/default-user-medium.png');
            }
         return $value;   
 }
 
 protected function getAvatarBigAttribute($value)
 {

            if($value == "" || is_null($value)){
                return  url('assets/images/users/default-user-medium.png');
            }
         return $value;   
 }

    public function registerMediaCollections()
    {
        
        $this
            ->addMediaCollection('gallary')
           // ->acceptsFile(function (File $file) {
             //   return $file->mimeType === 'image/jpeg';
           // })
            ->registerMediaConversions(function (Media $media) {

                $this
                    ->addMediaConversion('big')
                    ->width(720)
                    ->height(1280);
                $this
                    ->addMediaConversion('medium')
                    ->width(480)
                    ->height(854);
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);

            });


            $this->addMediaCollection('identity')
               ->registerMediaConversions(function (Media $media) {

                $this
                    ->addMediaConversion('big')
                    ->width(1280)
                    ->height(1280);
            });

    }
    
public function shortlist()
    {
        return $this->hasMany('App\Shortlist');
    }

}