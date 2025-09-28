<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use UserDiscounts\Models\Discount;
use UserDiscounts\Services\DiscountService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiscountServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_apply_discount_respects_usage_cap()
    {
        $user = User::factory()->create();
        $discount = Discount::create(['name'=>'Test','type'=>'fixed','value'=>50,'active'=>true]);

        $service = new DiscountService();
        $service->assign($user, $discount);

        // Set usage limit 1
        $user->discounts()->updateExistingPivot($discount->id,['usage_limit'=>1]);

        $amount1 = $service->apply($user, $discount, 200);
        $this->assertEquals(150, $amount1);

        // Second apply should not apply
        $amount2 = $service->apply($user, $discount, 200);
        $this->assertEquals(200, $amount2);
    }
}
