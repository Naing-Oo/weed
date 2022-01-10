<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Agent;
use App\Models\Influencer;

class Seller extends Model
{

    protected $table = 'sellers';

    protected $fillable = [
        'user_id',
        'sales_id',
        'role_name',
        'name',
        'email',
        'password',
        'created_at',
        'updated_at'
    ];


    
}
