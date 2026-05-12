<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HajjPlanLead extends Model
{
    protected $fillable = [
        'plan_type',
        'name',
        'email',
        'phone',
        'address',
        'message',
        'age',
        'hajj_year',
    ];
}
