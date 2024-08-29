<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name','email','password','phone','certificate','profile_picture','two_factor_code','verify_code',];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed',];
    protected $dates = ['updated_at', 'created_at', 'email_verified_at', 'two_factor_expires_at',];




    /***********************************************/
    public function generateCode()
    {
        $characters = '0123456789ABCDEYZab0123456789cdefghijk0123456789';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    /***********************************************/

    public  function resetVerificationCode()
    {
        $this->email_verified_at = now();
        $this->save();
    }

    /*************************************************/
    public function resendVerificationCode()
    {
        $verificationCode = $this->generateVerificationCode();
        $minutesRemaining = 3;
        $this->notify(new VereficationCodeNotification($verificationCode, $minutesRemaining));
    }

    /**
     * Generate 6 digits MFA code for the User
     */
    public function generateEmailCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->verify_code = rand(100000, 999999);
        $this->email_verified_at = now()->addMinutes(10);
        $this->save();
    }

    /**
     * Generate 6 digits MFA code for the User
     */
    public function generateTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }

    /**
     * Reset the MFA code generated earlier
     */
    public function resetTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->two_factor_code = '';
        $this->two_factor_expires_at = now();
        $this->save();
    }
}


