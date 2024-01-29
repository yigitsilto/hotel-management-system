<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('payment_status', function (Builder $builder) {
            $builder->where('payment_status', 1);
        });
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guests()
    {
        return $this->hasMany(ReservationGuest::class);
    }
}
