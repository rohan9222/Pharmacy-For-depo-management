<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use App\Models\FieldOfficerTeam;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile',
        'address',
        'balance',
        'role',
        'route',
        'category',
        'password',
        'product_target',
        'sales_target',
        'tse_id',
        'zse_id',
        'manager_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Define the search scope
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('user_id', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('mobile', 'like', '%' . $search . '%')
            ->orWhere('balance', 'like', '%' . $search . '%')
            ->orWhere('address', 'like', '%' . $search . '%');
    }

    // User.php
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'zse_id');
    }

    public function fieldOfficer()
    {
        return $this->belongsTo(User::class, 'tse_id');
    }

    public function customers()
    {
        return $this->hasMany(User::class, 'manager_id')
            ->orWhere('zse_id', $this->id)
            ->orWhere('tse_id', $this->id);
    }

    public function targetReports()
    {
        return $this->hasMany(TargetReport::class);
    }

    public function invoiceData(){
        return $this->hasMany(Invoice::class , 'customer_id');
    }

}
