<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DealRequest;
use App\Models\Deal;
use App\Models\DealPrice;
use App\Models\Product;
use App\Models\Supplier;

class DealPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function saveDeal(DealRequest $request)
    {
        $dealSetting = Deal::first();
        if ($dealSetting) {
            $dealSetting->update(["end_at" => $request->end_at]);
        } else {
            $dealSetting = Deal::create(["end_at" => $request->end_at]);
        }
        DealPrice::truncate();
        foreach ($request->deals as $deal) {
            $deal["deal_id"] = $dealSetting->id;
            DealPrice::create($deal);
        }
    }
    public function getDeal()
    {
        $dealSetting = Deal::first();
        return ["end_at" => $dealSetting ? $dealSetting->end_at : null, "deals" => DealPrice::get()];
    }
    public function getProducts()
    {
        return Product::get();
    }
    public function getSuppliers()
    {
        return Supplier::get();
    }
}
