<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Otp extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'otp', 'expires_at'];

    /**
     * Generate a new OTP for the given email.
     *
     * @param string $email
     * @return Otp
     */
    public static function generate($email)
    {
        // Generate a random OTP
        $otpCode = rand(100000, 999999);

        // Save OTP in the database
        return self::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);
    }

    /**
     * Verify the OTP for the given email.
     *
     * @param string $email
     * @param string $otp
     * @return bool
     */
    public static function verify($email, $otp)
    {
        $otpRecord = self::where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otpRecord) {
            // Delete the OTP after successful verification
            $otpRecord->delete();
            return true;
        }

        return false;
    }
}
