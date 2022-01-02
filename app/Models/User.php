<?php

namespace App\Models;

use Spork\Planning\Models\Status;
use Spork\Planning\Models\Task;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Telescope\Avatar;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
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

        self::created(function ($user) {
            $user->statuses()->create([
                'title' => 'To Do',
                'slug' => 'to-do',
                'order' => 1,
            ]);
            $user->statuses()->create([
                'title' => 'Working On/In Progress',
                'slug' => 'in-progress',
                'order' => 2,
            ]);
            $user->statuses()->create([
                'title' => 'Done',
                'slug' => 'done',
                'order' => 5,
            ]);
            $user->statuses()->create([
                'title' => 'Ideas',
                'slug' => 'ideas',
                'order' => 0,
            ]);
        });
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'creator_id')->orderBy('order');
    }

    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'creator_id')->orderBy('order');
    }

    public function tasksAssigned()
    {
        return $this->hasMany(Task::class, 'assignee_id')->orderBy('order');
    }

    public function statuses()
    {
        return $this->hasMany(Status::class)->orderBy('order');
    }

    public function features()
    {
        return $this->hasMany(FeatureList::class);
    }

    public function finance()
    {
        return $this->features()->where('feature', FeatureList::FEATURE_FINANCE);
    }
}
