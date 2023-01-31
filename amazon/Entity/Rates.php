<?php

namespace Amazon\Entity;

use Amazon\Entity\Rate;

class Rates
{
    public string $requestToken = '';
    /**
     * @var Rate[]
     */
    public array $rates = [];

    public array $ineligibleRates = [];

    /**
     * @param string $requestToken
     * @param Rate[] $rates
     * @param array $ineligibleRates
     */
    public function __construct(string $requestToken, array $rates, array $ineligibleRates)
    {
        $this->requestToken = $requestToken;
        $this->rates = $rates;
        $this->ineligibleRates = $ineligibleRates;
    }
}