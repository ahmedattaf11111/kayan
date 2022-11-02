<?php

namespace App\Repositories\Auth;

use App\Constants\OrderStatus;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class ProfileRepository
{
    public function updateProfile($userId, $userInput, $clientInput)
    {
        $user = User::find($userId);
        $user->update($userInput);
        Client::where("user_id", $user->id)->update($clientInput);
    }
    public function updateImage($userId, $imageName)
    {
        $user = User::find($userId);
        $oldImage = $user->image;
        $user->image = $imageName;
        $user->save();
        return $oldImage;
    }
    public function getTotalOrders($authUserId, $orderStatus)
    {
        return Order::where("user_id", $authUserId)
            ->when($orderStatus, function ($query) use ($orderStatus) {
                $query->where("order_status", $orderStatus);
            })->where("order_status", "<>", OrderStatus::CART)->count();
    }
    public function getProfileOrders($authUserId, $pageSize, $from, $to)
    {
        return Order::where("user_id", $authUserId)
            ->where("order_status", "<>", OrderStatus::CART)
            ->whereHas("products.carts")
            ->with(["products" => function ($query) {
                $query->with("carts")->distinct();
            }])
            ->when($from, function ($query) use ($from) {
                $query->where("created_at", ">=", $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate("created_at", "<=", $to);
            })
            ->paginate($pageSize);
    }
}
