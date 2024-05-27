<?php

namespace App\Http\Controllers\Admin;

use App\Constants\OrderStatus;
use App\Constants\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        if (request()->page_size) {
            return Order::with("user")
                ->when(request()->order_status, function ($q) {
                    $q->where("order_status", request()->order_status);
                })
                ->where(function ($q) {
                    $q->when(request()->order_status, function ($q) {
                        $q->where("order_status", request()->order_status);
                    })->when(request()->payment_status, function ($q) {
                        $q->where("payment_status", request()->payment_status);
                    })->when(request()->payment_method, function ($q) {
                        $q->where("payment_method", request()->payment_method);
                    });
                })
                ->where(function ($q) use ($text) {
                    $q->whereRelation("user", "name", "like", "%$text%")
                        ->orWhere("invoice_id", $text)
                        ->orWhere("transaction_id", $text);
                })
                ->where("order_status", "!=", "Cart")->orderBy("id", "desc")
                ->paginate(request()->page_size);
        }
        return Order::get();
    }

    public function markStatusAsCompleted($id)
    {
        Order::find($id)->update([
            "order_status" => OrderStatus::COMPLETED,
            "payment_status" => PaymentStatus::PAID,
        ]);
    }
}
