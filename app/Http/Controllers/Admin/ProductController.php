<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\PharmacistForm;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //Dashboard endpoints
    public function store(StoreProductRequest $request)
    {
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $employee = Product::create($request->input());
        return $employee;
    }

    public function update(UpdateProductRequest $request)
    {
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->_update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_product"];
    }

    public function delete($id)
    {
        $oldImage = $this->_delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        if (request()->page_size) {
            return Product::where("name", "like", "%$text%")
                ->with("category")
                ->with("pharmacistForm")
                ->with("company")
                ->orderBy("id", "desc")->paginate(request()->page_size);
        }
        return Product::get();
    }
    public function getCategories(){
        return Category::get();
    }
    
    public function getCompanies(){
        return Company::get();
    }

    public function getPharmacistForms(){
        return PharmacistForm::get();
    }
    //Commons    
    public function _update($productInput)
    {
        $product = Product::find($productInput["id"]);
        $oldImage = $product->getImageName();
        $productInput["image"] = isset($productInput["image"]) ? $productInput["image"] : $oldImage;
        $product->update($productInput);
        return ["old_image" => $oldImage, "updated_product" => $product];
    }
    public function _delete($id)
    {
        $product = Product::find($id);
        $oldImage = null;
        if ($product) {
            $oldImage = $product->getImageName();
            $product->delete();
        }
        return $oldImage;
    }
}
