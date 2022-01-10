<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Agent;
use App\Models\Influencer;

class SubAgent extends Model
{
    // use HasFactory;

    use SoftDeletes;
    protected $fillable = [
        'agent_id',
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

    public function agent(){
        return $this->hasOne(Agent::class, 'id', 'agent_id');
    }

    public function influencers(){
        return $this->hasMany(Influencer::class, 'sub_agent_id', 'id');
    }
}
