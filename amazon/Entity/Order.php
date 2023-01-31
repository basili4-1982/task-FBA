<?php

namespace Amazon\Entity;

use DateTime;
use JsonSerializable;

class Order implements JsonSerializable
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var User|null
     */
    private ?User $shipTo;
    /**
     * @var User
     */
    private User $shipFrom;
    /**
     * @var DateTime
     */
    private DateTime $shipDate;
    /**
     * @var Package
     */
    private Package $packages;
    /**
     * @var array
     */
    private array $channelDetails;

    /**
     * @param int $id
     * @param User $shipFrom
     * @param DateTime $shipDate
     * @param Package $packages
     * @param array $channelDetails
     * @param User|null $shipTo
     */
    public function __construct(int $id, User $shipFrom, DateTime $shipDate, Package $packages, array $channelDetails, ?User $shipTo = null)
    {
        $this->shipTo = $shipTo;
        $this->shipFrom = $shipFrom;
        $this->shipDate = $shipDate;
        $this->packages = $packages;
        $this->channelDetails = $channelDetails;
        $this->id = $id;
    }

    /**
     * @param User $buyer
     * @return void
     */
    public function setShipTo(User $buyer): void
    {
        $this->shipTo = $buyer;
    }

    /**
     * @return User
     */
    public function getShipTo(): User
    {
        return $this->shipTo;
    }

    /**
     * @return User
     */
    public function getShipFrom(): User
    {
        return $this->shipFrom;
    }

    /**
     * @return DateTime
     */
    public function getShipDate(): DateTime
    {
        return $this->shipDate;
    }

    /**
     * @return Package
     */
    public function getPackages(): Package
    {
        return $this->packages;
    }

    /**
     * @return array
     */
    public function getChannelDetails(): array
    {
        return $this->channelDetails;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public static function getInstanceFormArray(array $data): self
    {
        return new Order(
            $data['id'],
            $data['shipFrom'],
            $data['shipDate'],
            $data['packages'],
            $data['channelDetails'],
            $data['shipTo'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'shipTo' => $this->getShipTo(),
            'shipFrom' => $this->getShipFrom(),
            'shipDate' => $this->getShipDate()->format('Y-m-dTh:m:s'),
            'packges' => $this->getPackages(),
            'channelDetails' => $this->getChannelDetails(),
        ];
    }
}