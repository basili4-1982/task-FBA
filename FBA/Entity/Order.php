<?php

namespace FBA\Entity;

use Amazon\Entity\Package;

class Order extends AbstractOrder
{
    public const PRODUCT = 'products';
    const PACKAGES = 'packages';
    const SHIP_DATE = 'shipDate';
    const SHIP_FROM = 'shipFrom';
    const SHIP_TO = 'shipTo';

    public function setProducts(array $items)
    {
        $this->data[self::PRODUCT] = $items;
    }

    public function getProducts(): array
    {
        return $this->data[self::PRODUCT] ?? [];
    }

    public function setPackage(Package $package)
    {
        $this->data[self::PACKAGES] = $package;
    }

    public function getPackage(): Package
    {
        return $this->data[self::PACKAGES] ?? new Package();
    }

    public function setDateCreate(\DateTime $d)
    {
        $this->data[self::SHIP_DATE] = $d;
    }

    public function getDateCreate(): \DateTime
    {
        return $this->data[self::SHIP_DATE];
    }

    public function setShipFrom(Buyer $u)
    {
        $this->data[self::SHIP_FROM] = $u;
    }


    public function getShipFrom()
    {
        return $this->data[self::SHIP_FROM] ?? new Buyer();
    }

    public function setShipTo(?Buyer $u): ?Buyer
    {
        return $this->data[self::SHIP_TO] = $u;
    }

    public function getShipTo(): ?Buyer
    {
        return $this->data[self::SHIP_TO] ?? null;
    }

    protected function loadOrderData(int $id): array
    {
        /**
         *  $data['id'],
         * $data['shipFrom'],
         * $data['shipDate'],
         * $data['packages'],
         * $data['channelDetails'],
         * $data['shipTo'] ?? null,
         */

        return [
            'id' => $id,
            self::PRODUCT => $this->getProducts(),
            self::PACKAGES => $this->getPackage(),
            self::SHIP_DATE => $this->getDateCreate()->format('Y-m-dTh:m:s'),
            self::SHIP_FROM => $this->getShipFrom(),
            self::SHIP_TO => $this->getShipTo(),
        ];
    }

    public function getData(): array
    {
        return $this->data ?? [];
    }
}