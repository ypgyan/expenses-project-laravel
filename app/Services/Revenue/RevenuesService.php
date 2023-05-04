<?php

namespace App\Services\Revenue;

use App\Models\Revenue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class RevenuesService
{
    public function createRevenue(array $revenueData): Revenue
    {
        $revenue = new Revenue();
        $revenue->description = $revenueData['description'];
        $revenue->value = $revenueData['value'];
        $revenue->received_at = Carbon::createFromFormat('d-m-Y' , $revenueData['received_at'])->format('Y-m-d');
        $revenue->save();
        return $revenue;
    }

    public function getAll(): Collection
    {
        return Revenue::all();
    }

    public function getRevenue(string $id)
    {
        return Revenue::findOrFail($id);
    }
}
