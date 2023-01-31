<?php

namespace App\interfaces;

interface BuyerInterface
{
    public function getAddressLine1(): string;

    public function setAddressLine1(string $addressLine1): void;

    public function getAddressLine2(): ?string;

    public function setAddressLine2(?string $addressLine2): void;

    public function getPostalCode(): string;

    public function setPostalCode(string $postalCode): void;

    public function getCity(): string;

    /**
     * @param string $city
     */
    public function setCity(string $city): void;

    public function getRegion(): string;

    public function setRegion(string $region): void;

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getPhoneNumber(): string;

    public function setPhoneNumber(string $phoneNumber): void;

    public function getCountryCode(): string;

    public function setCountryCode(string $countryCode): void;
}