<?php

namespace App;

use amazon\AmazonApiInterface;
use Amazon\Entity\DocumentSpecification;
use Amazon\Entity\Rate;
use Amazon\Entity\User;
use App\Exceptions\NotEnoughData;
use App\interfaces\BuyerInterface;
use App\interfaces\ShippingServiceInterface;
use FBA\Entity\Order;

class ShippingService implements ShippingServiceInterface
{
    private AmazonApiInterface $api;

    public function __construct(AmazonApiInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @param Order $order
     * @param BuyerInterface $buyer
     * @return string
     */
    public function ship(Order $order, BuyerInterface $buyer): string
    {
        $amazonOrder = \Amazon\Entity\Order::getInstanceFormArray($order->getData());
        return $this->shipAmazon($amazonOrder, $this->castToAmazonUser($buyer));
    }

    private function castToAmazonUser(BuyerInterface $buyer): User
    {
        $user = new User();
        $user->name = $buyer->getName();
        $user->addressLine1 = $buyer->getAddressLine1();
        $user->phoneNumber = $buyer->getPhoneNumber();
        $user->email = $buyer->getEmail();
        $user->countryCode = $buyer->getCountryCode();
        $user->stateOrRegion = $buyer->getRegion();
        $user->postalCode = $buyer->getCountryCode();
        return $user;
    }

    protected function shipAmazon(\Amazon\Entity\Order $order, User $user): string
    {
        $rates = $this->api->getRates($order);
        $requestToken = $rates->requestToken;
        $rate = $this->chooseRate($rates->rates);
        if ($rate->requiresAdditionalInputs) {
            $additionalInputs = $this->api->getAdditionalInputs($requestToken, $rate->id);
            if (count($additionalInputs) > 0) {
                throw new NotEnoughData("Please, set additional inputs to order:", 1, $additionalInputs);
            }
        }
        $purchasedShipment = $this->api->purchaseShipment($requestToken, $rate->id, new DocumentSpecification());
        return $purchasedShipment['packageDocumentDetails']['trackingId'] ?? '';
    }

    private function chooseRate(array $rates): Rate
    {
        return $rates[0];
    }
}