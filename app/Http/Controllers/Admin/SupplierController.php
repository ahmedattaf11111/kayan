<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //Dashboard endpoints
    public function store(StoreSupplierRequest $request)
    {
        return Supplier::create($request->validated());
    }

    public function update(UpdateSupplierRequest $request)
    {
        return Supplier::find($request->id)->update($request->validated());
    }

    public function delete($id)
    {
        Supplier::destroy($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        if (request()->page_size) {
            return Supplier::where("name", "like", "%$text%")
                ->orderBy("id", "desc")->paginate(request()->page_size);
        }
        return Supplier::get();
    }
}
