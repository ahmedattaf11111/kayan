<?php

namespace App\Services\Order;

use App\Repositories\Order\PaymentRepository;
use App\Utils\Controllers\MyFatoorahUtil;

class PaymentService
{
    use MyFatoorahUtil;
    private $paymentService;

    public function __construct(PaymentRepository $paymentService)
    {
        $this->paymentRepository = $paymentService;
    }

    public function cashPayment($userId)
    {
        $this->paymentRepository->cashPayment($userId);
    }
    public function onlinePayment($paymentInput)
    {
        $this->paymentRepository->onlinePayment($paymentInput);
    }
    public function getCartStatusOrder($paymentInput)
    {
        return $this->paymentRepository->getCartStatusOrder($paymentInput);
    }
}
