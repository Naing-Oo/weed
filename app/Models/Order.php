<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Seller;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'seller_id',
        'parents_name',
        'phone',
        'email',
        'student_name',
        'age',
        'class_level',
        'school_name',
        'province_name',
        'transaction_id',
        'currency',
        'amount', 
        'order_number',
        'invoice_no',
        'order_date',
        'order_month',
        'order_year',
        'status',
    ];

    public function order_items(){

        return $this->hasMany(OrderItem::class, 'id', 'order_id');
    }

    public function seller(){
        
        return $this->hasOne(Seller::class, 'sales_id', 'seller_id');
    }

    public function commissionAmt(){
        
        return $this->hasOne(Seller::class, 'sales_id', 'seller_id');
    }
}
