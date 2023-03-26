<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //Dashboard endpoints
    public function store(StoreCategoryRequest $request)
    {
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $employee = Category::create($request->input());
        return $employee;
    }

    public function update(UpdateCategoryRequest $request)
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
        return $updateResult["updated_category"];
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
            return Category::where("name", "like", "%$text%")
                ->orderBy("id", "desc")->paginate(request()->page_size);
        }
        return Category::get();
    }
    //Commons    
    public function _update($categoryInput)
    {
        $category = Category::find($categoryInput["id"]);
        $oldImage = $category->getImageName();
        $category->name = $categoryInput["name"];
        $category->image = isset($categoryInput["image"]) ? $categoryInput["image"] : $oldImage;
        $category->save();
        return ["old_image" => $oldImage, "updated_category" => $category];
    }
    public function _delete($id)
    {
        $category = Category::find($id);
        $oldImage = null;
        if ($category) {
            $oldImage = $category->getImageName();
            $category->delete();
        }
        return $oldImage;
    }
}
