<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    public const TYPE_INQUIRY = 'inquiry';

    public const TYPE_COMPLAINT = 'complaint';

    public const TYPE_ONLINE_COMPLAINT = 'online_complaint';

    public const TYPE_ACCOUNT_DELETION = 'account_deletion';

    protected $fillable = [
        'form_type',
        'name',
        'email',
        'phone',
        'city',
        'message',
    ];
}
