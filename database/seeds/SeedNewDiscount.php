<?php

use Illuminate\Database\Seeder;

class SeedNewDiscount extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $discounts = App\CtrDiscount::groupBy('series')->get();
        
        foreach($discounts as $discount){
            $newDiscounts = new \App\DiscountSeries();
            $newDiscounts->discountseries = $discount->series;
            $newDiscounts->description = $discount->description;
            $newDiscounts->save();
        }
    }
}
