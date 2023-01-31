<?php

namespace App\interfaces;

use FBA\Entity\Order;

interface ShippingServiceInterface
{
    public function ship(Order $order, BuyerInterface $buyer): string;
}