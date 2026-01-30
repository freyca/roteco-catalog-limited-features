<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Repositories\Payment\BankTransferPaymentRepository;
use App\Repositories\Payment\PaymentRepositoryInterface;
use Illuminate\Http\Request;

final readonly class Payment
{
    private PaymentRepositoryInterface $repository;

    public function __construct(private Order $order)
    {
        $this->repository = resolve(BankTransferPaymentRepository::class);
    }

    public function payPurchase(): mixed
    {
        return $this->repository->payPurchase($this->order);
    }

    public function isGatewayOkWithPayment(Request $request): bool
    {
        return $this->repository->isGatewayOkWithPayment($this->order, $request);
    }
}
