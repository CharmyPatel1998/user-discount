<?php
namespace UserDiscounts\Services;

use UserDiscounts\Models\Discount;
use UserDiscounts\Models\UserDiscount;
use UserDiscounts\Models\DiscountAudit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use UserDiscounts\Events\DiscountAssigned;
use UserDiscounts\Events\DiscountRevoked;
use UserDiscounts\Events\DiscountApplied;

class DiscountService
{
    public function assign(User $user, Discount $discount)
    {
        $user->discounts()->syncWithoutDetaching([$discount->id]);
        event(new DiscountAssigned($user, $discount));
    }

    public function revoke(User $user, Discount $discount)
    {
        $user->discounts()->detach($discount->id);
        event(new DiscountRevoked($user, $discount));
    }

    public function eligibleFor(User $user, Discount $discount)
    {
        $userDiscount = $user->discounts()->where('discount_id',$discount->id)->first();
        if(!$userDiscount) return false;
        if(!$discount->isActive()) return false;
        if($userDiscount->pivot->usage_limit !== null &&
           $userDiscount->pivot->usage_count >= $userDiscount->pivot->usage_limit) return false;
        return true;
    }

    public function apply(User $user, Discount $discount, float $amount)
    {
        if(!$this->eligibleFor($user, $discount)) return $amount;

        return DB::transaction(function() use ($user, $discount, $amount) {
            $userDiscount = $user->discounts()->where('discount_id',$discount->id)->lockForUpdate()->first();
            $applied = match($discount->type) {
                'percentage' => min($amount * ($discount->value/100), config('userdiscounts.max_percentage_cap')/100 * $amount),
                'fixed' => $discount->value,
            };

            $rounding = config('userdiscounts.rounding');
            $applied = match($rounding) {
                'ceil' => ceil($applied),
                'floor' => floor($applied),
                default => round($applied)
            };

            // Increment usage safely
            $userDiscount->pivot->increment('usage_count');

            // Audit
            DiscountAudit::create([
                'user_id' => $user->id,
                'discount_id' => $discount->id,
                'applied_amount' => $applied
            ]);

            event(new DiscountApplied($user, $discount));

            return max(0, $amount - $applied);
        });
    }
}
