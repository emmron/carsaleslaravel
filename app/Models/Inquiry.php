<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_listing_id',
        'user_id',
        'message',
        'status', // new, read, replied, closed
        'name',
        'email',
        'phone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the car listing that the inquiry is for.
     */
    public function carListing()
    {
        return $this->belongsTo(CarListing::class);
    }

    /**
     * Get the user that made the inquiry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the messages for the inquiry.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}