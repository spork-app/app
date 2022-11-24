<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spork\Core\Models\FeatureList;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'socialite_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        parent::booted();

        self::creating(function (User $user) {
            $user->profile_photo = 'https://www.gravatar.com/avatar/'.md5(Str::lower($user->email)).'?s=200';
        });
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'user.'.$this->id;
    }

    public function updateProfilePhoto($photo)
    {
        tap($this->profile_photo_path, function ($previous) use ($photo) {
            $this->forceFill([
                'profile_photo' => $photo->storePublicly(
                    'profile-photos', ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                \Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    protected function profilePhotoDisk()
    {
        return 'public';
    }

    public function features()
    {
        return $this->hasMany(FeatureList::class);
    }
}
