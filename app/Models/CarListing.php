<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarListing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'year',
        'make',
        'model',
        'trim',
        'body_type',
        'fuel_type',
        'transmission',
        'odometer',
        'color',
        'vin',
        'registration_number',
        'registration_expiry',
        'user_id',
        'status', // available, sold, pending
        'featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'year' => 'integer',
        'odometer' => 'integer',
        'registration_expiry' => 'date',
        'featured' => 'boolean',
    ];

    /**
     * Get the user that owns the car listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the images for the car listing.
     */
    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    /**
     * Get the features for the car listing.
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }

    /**
     * Get the inquiries for the car listing.
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Scope a query to only include available listings.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include featured listings.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}