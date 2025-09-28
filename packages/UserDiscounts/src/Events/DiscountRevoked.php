<?php

namespace UserDiscounts\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\User;
use UserDiscounts\Models\Discount;

class DiscountRevoked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Discount $discount;

    public function __construct(User $user, Discount $discount)
    {
        $this->user = $user;
        $this->discount = $discount;
    }
}
