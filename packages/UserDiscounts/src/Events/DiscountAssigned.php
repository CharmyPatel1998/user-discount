<?php
namespace UserDiscounts\Events;

use Illuminate\Queue\SerializesModels;
use UserDiscounts\Models\Discount;
use App\Models\User;

class DiscountAssigned
{
    use SerializesModels;

    public $user;
    public $discount;

    public function __construct(User $user, Discount $discount)
    {
        $this->user = $user;
        $this->discount = $discount;
    }
}
