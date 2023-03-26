<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //Dashboard endpoints
    public function store(StoreCompanyRequest $request)
    {
        $employee = Company::create($request->input());
        return $employee;
    }

    public function update(UpdateCompanyRequest $request)
    {
        return Company::find($request->id)->update($request->validated());
    }

    public function delete($id)
    {
        Company::destroy($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        if (request()->page_size) {
            return Company::where("name", "like", "%$text%")
                ->orderBy("id", "desc")->paginate(request()->page_size);
        }
        return Company::get();
    }
}
