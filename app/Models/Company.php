<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'email',
        'phone',
        'whatsapp',
        'website',
        'short_description',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    public function meta()
    {
        return $this->hasMany(CompanyMeta::class);
    }
}
