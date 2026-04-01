<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public function sections()
{
    return $this->hasMany(UserSection::class, 'u_id', 'id');
}
//     public function sections()
// {
//     return $this->belongsToMany(
//         Section::class,
//         'u_section',
//         'u_id',
//         'section'
//     );
// }
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // protected $table = 'tb_user';

    protected $fillable = [
        'id',
        'username',
        'fullname',
        'name',
        'lastname',
        'email',
        'role',
        'hwh_branch',
        'position',
        'section',
        'password',
        'u_pwd',
        'last_login',
        'active_status',
        'avatar',
        'level',
        'employee_id',
        'job_position_id',
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
}
