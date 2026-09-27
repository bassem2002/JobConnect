<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'requirements',
        'expiration_date',
        'location',
        'type',
        'category',
        'education_level',
        'experience_years',
        'salary',
        'languages',
        'keywords',
        'vacancies',
        'status',
    ];

    public function offerCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
