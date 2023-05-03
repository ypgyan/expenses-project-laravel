<?php

namespace App\Services\Revenue;

use App\Models\Revenue;
use Illuminate\Support\Carbon;

class RevenuesService
{
    public function createRevenue(array $revenueData): Revenue
    {
        $revenue = new Revenue();
        $revenue->description = $revenueData['description'];
        $revenue->value = $revenueData['value'];
        $revenue->received_at = Carbon::createFromFormat('d-m-Y' , $revenueData['received_at']);
        $revenue->save();
        return $revenue;
    }
}
