<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Seller;

class LinkTransaction extends Model
{
    // use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'sales_id',
        'seller_name',
        'product_id',
        'product_name',
        'product_link',
        'created_by',        
        'updated_by',
        'created_at',        
        'updated_at'        
    ];

    public function seller(){
        return $this->hasOne(Seller::class, 'sales_id');
    }


}
