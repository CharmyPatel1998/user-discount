<?php
namespace UserDiscounts\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['name','type','value','active','expires_at'];

    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'user_discounts')
            ->withPivot('usage_count','usage_limit')->withTimestamps();
    }

    public function isActive() {
        return $this->active && (!$this->expires_at || $this->expires_at->isFuture());
    }
}
