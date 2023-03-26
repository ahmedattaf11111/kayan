<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePharmacistFormRequest;
use App\Http\Requests\UpdatePharmacistFormRequest;
use App\Models\PharmacistForm;

class PharmacistFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //Dashboard endpoints
    public function store(StorePharmacistFormRequest $request)
    {
        $employee = PharmacistForm::create($request->validated());
        return $employee;
    }

    public function update(UpdatePharmacistFormRequest $request)
    {
        return PharmacistForm::find($request->id)->update($request->validated());
    }

    public function delete($id)
    {
        PharmacistForm::destroy($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        if (request()->page_size) {
            return PharmacistForm::where("name", "like", "%$text%")
                ->orderBy("id", "desc")->paginate(request()->page_size);
        }
        return PharmacistForm::get();
    }
}
