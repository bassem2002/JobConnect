<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'image_url'];

    public function jobOffers()
    {
        return $this->hasMany(JobOffer::class);
    }
}
