<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    /**
     * Disable timestamps because the migration only creates the requested columns.
     *
     * @var bool
     */
    public $timestamps = false;
}
