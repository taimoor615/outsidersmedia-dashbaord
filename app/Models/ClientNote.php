<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNote extends Model
{
    protected $fillable = ['client_id', 'note'];
}
