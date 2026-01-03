<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'family_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
    public function moneyRequestsSent()
    {
        return $this->hasMany(MoneyRequest::class, 'requester_id');
    }

    public function moneyRequestsReceived()
    {
        return $this->hasMany(MoneyRequest::class, 'approver_id');
    }
    public function scopeSameFamily($query)
    {
        if ($this->family_id) {
            return $query->where('family_id', $this->family_id);
        }
        return $query->where('id', $this->id);
    }

    public function familyMembers()
    {
        return $this->family_id 
            ? User::where('family_id', $this->family_id)->get() 
            : collect([$this]);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
