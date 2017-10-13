<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    //
    protected $fillable = [
        'street', 'user_id', 'postcode', 'town','country', 'contact_email', 'move_in'
    ];
}
