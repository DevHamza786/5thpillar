<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrochureLead extends Model
{
    protected $fillable = [
        'brochure_key',
        'name',
        'email',
        'phone',
        'address',
        'gender',
        'city',
    ];
}
