<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function store(StoreSliderRequest $request)
    {
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $request->merge(["external" => $request->external == "true" ? 1 : 0]);
        $employee = Slider::create($request->input());
        return $employee;
    }

    public function update(UpdateSliderRequest $request)
    {
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $request->merge(["external" => $request->external == "true" ? 1 : 0]);
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
            return Slider::where("title", "like", "%$text%")
                ->with("product")->orderBy("id", "desc")->paginate(request()->page_size);
        }
        return Slider::get();
    }

    public function getProducts()
    {
        return Product::get();
    }
    //Commons
    public function _update($sliderInput)
    {
        $slider = Slider::find($sliderInput["id"]);
        $oldImage = $slider->getImageName();
        $sliderInput["image"] = isset($sliderInput["image"]) ? $sliderInput["image"] : $oldImage;
        $slider->update($sliderInput);
        return ["old_image" => $oldImage, "updated_product" => $slider];
    }
    public function _delete($id)
    {
        $slider = Slider::find($id);
        $oldImage = null;
        if ($slider) {
            $oldImage = $slider->getImageName();
            $slider->delete();
        }
        return $oldImage;
    }
}
