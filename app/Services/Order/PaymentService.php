<?php

namespace App\Services\Order;

use App\Repositories\Order\PaymentRepository;
use App\Utils\Controllers\MyFatoorahUtil;
use Carbon\Carbon;

class PaymentService
{
    use MyFatoorahUtil;
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function cashPayment($userId, $orderInput)
    {
        $this->paymentRepository->cashPayment($userId);
        $this->saveOrderForm($userId, $orderInput);
    }
    public function saveOrderForm($userId, $orderInput)
    {
        $this->paymentRepository->saveOrderForm($userId, $orderInput);
    }
    public function onlinePayment($paymentInput)
    {
        $paymentInput["created_at"] = Carbon::now();
        $this->paymentRepository->onlinePayment($paymentInput);
    }

    public function getCartStatusOrder($userId)
    {
        return $this->paymentRepository->getCartStatusOrder($userId, false);
    }

    public function getTotalPrice($userId)
    {
        return $this->paymentRepository->getTotalPrice($userId);
    }
  
}
