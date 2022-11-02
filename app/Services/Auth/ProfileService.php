<?php

namespace App\Services\Auth;

use App\Constants\OrderStatus;
use App\Repositories\Auth\ProfileRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    private $profileRepository;
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
    public function updateProfile($userId, $input)
    {
        $this->profileRepository->updateProfile(
            $userId,
            [
                "phone" => $input["phone"]
            ],
            [
                "address" => $input["address"],
                "city_id" => $input["city_id"],
                "area_id" => $input["area_id"],
            ]
        );
    }
    public function updateImage($userId, $image)
    {
        $newImage = $image->store("");
        $oldImage = $this->profileRepository->updateImage($userId, $newImage);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
        return ["image" => $newImage];
    }
    public function deleteImage($userId)
    {
        $oldImage = $this->profileRepository->updateImage($userId, null);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    public function getProfileStatistics($authUserId)
    {
        $totalOrders = $this->profileRepository->getTotalOrders($authUserId, null);
        $totalPendingOrders = $this->profileRepository->getTotalOrders($authUserId, OrderStatus::PENDING);
        $totalCompletedOrders = $this->profileRepository->getTotalOrders($authUserId, OrderStatus::COMPLETED);
        return [
            "total_orders" => $totalOrders,
            "total_pending_orders" => $totalPendingOrders,
            "total_completed_orders" => $totalCompletedOrders,
        ];
    }
    public function getProfileOrders($authUserId, $pageSize, $from, $to)
    {
        $orders = $this->profileRepository->getProfileOrders($authUserId, $pageSize, $from, $to);
        return $this->mapOrders($orders);
    }
    //Commons
    private function mapOrders(&$orders)
    {
        $orders->transform(function ($order) {
            $orderId = $order->id;
            $order->products_list = $order->products->map(function ($product) use ($orderId) {
                $product->carts_list = $product->carts->filter(function ($cart) use ($orderId) {
                    return $cart->order_id == $orderId;
                });
                unset($product->carts);
                return $product;
            });
            unset($order->products);
            return $order;
        });
        return $orders;
    }
}
