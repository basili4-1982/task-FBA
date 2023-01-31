<?php

namespace Amazon;

use Amazon\Entity\DocumentSpecification;
use Amazon\Entity\Order;
use Amazon\Entity\Rates;

interface AmazonApiInterface
{
    public function getRates(Order $order): Rates;

    public function getAdditionalInputs(string $requestToken, string $rateId): array;

    public function purchaseShipment(string $requestToken, string $rateId, DocumentSpecification $documentSpecification): array;
}