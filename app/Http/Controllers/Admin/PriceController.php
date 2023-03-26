<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use App\Models\Product;
use App\Models\Supplier;

class PriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //Dashboard endpoints
    public function store(StorePriceRequest $request)
    {
        return Price::create($request->validated());
    }
    
    public function getSuppliers(){
        return Supplier::get();
    }

    public function getProducts(){
        return Product::get();
    }

    public function update(UpdatePriceRequest $request)
    {
        return Price::find($request->id)->update($request->validated());
    }

    public function delete($id)
    {
        Price::destroy($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        if (request()->page_size) {
            return Price::orderBy("id", "desc")->with(["product", "supplier"])
                ->paginate(request()->page_size);
        }
        return Price::get();
    }
}
