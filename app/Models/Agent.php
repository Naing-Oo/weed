<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Models\SubAgent;
use App\Models\Influencer;

class Agent extends Model
{
    use HasRoles;

    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'sales_id',
        'first_name',
        'last_name',
        'full_name',
        'address',
        'email',
        'phone',
        'commission_rate',
        'remark'    
    ];

    protected $dates = [
        'join_date',
        'resign_date'
    ];

    public function getJoinDateAttribute($value){
        return date('m/d/Y', strtotime($value));
    }
    
    public function getResignDateAttribute($value){
        return date('m/d/Y', strtotime($value));
    }

    public function subAgents(){
        return $this->hasMany(SubAgent::class, 'agent_id', 'id');
    }

    public function influencers(){
        return $this->hasMany(Influencer::class);
    }

   
}
