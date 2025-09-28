<?php
namespace UserDiscounts\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserDiscount extends Pivot
{
    protected $table = 'user_discounts';
    protected $fillable = ['user_id','discount_id','usage_count','usage_limit'];
}
