<?php

namespace App\Services\Order;

use App\Repositories\Order\OrderRepository;

class OrderService
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
  
}
