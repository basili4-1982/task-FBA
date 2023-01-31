<?php

namespace Amazon;


use Amazon\Entity\DocumentSpecification;
use Amazon\Entity\Order;
use Amazon\Entity\Rate;
use Amazon\Entity\Rates;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use RuntimeException;

class AmazonApi implements AmazonApiInterface
{
    public const RATE_URL = 'RATE_URL';
    public const TIMEOUT = 'TIMEOUT';
    public const BUSINESS_ID = 'BUSINESS_ID';

    public const ADDITIONAL_INPUTS = 'ADDITIONAL_INPUTS';

    private Client $client;
    private string $baseUrl;
    private array $options = [];
    private array $defaultOpt;

    public function __construct(Client $client, string $baseUrl, array $options = [])
    {
        $this->defaultOpt = [
            self::RATE_URL => '/rates',
            self::ADDITIONAL_INPUTS => '/additionalInputs/schema',
            self::TIMEOUT => 30,
            self::BUSINESS_ID => '',
        ];

        $this->client = $client;
        $this->baseUrl = $baseUrl;

        foreach ($this->defaultOpt as $key => $opt) {
            $this->options[$key] = $options[$key] ?? $this->defaultOpt[$key];
        }
    }

    /**
     * @throws GuzzleException
     */
    public function getRates(Order $order): Rates
    {
        $url = $this->getOpt(self::RATE_URL);

        $resp = $this->client->request('post', $this->baseUrl . $url, [
            RequestOptions::HEADERS => [
                'x-amzn-shipping-business-id', $this->getOpt(self::BUSINESS_ID)
            ],
            RequestOptions::TIMEOUT => $this->getOpt(self::TIMEOUT),
            RequestOptions::JSON => json_encode($order)
        ]);

        if ($resp->getStatusCode() != 200) {
            throw new RuntimeException($resp->getBody()->getContents());
        }

        $response = json_decode($resp->getBody()->getContents(), true);
        $rates = [];
        $data = $response['payload']['rates'] ?? [];
        foreach ($data as $item) {
            $rate = Rate::getInstanceFromArray($item);
            $rates[] = $rate;
        }

        return new Rates($response['requestToken'], $rates, []);
    }

    /**
     * @throws GuzzleException
     */
    public function getAdditionalInputs(string $requestToken, string $rateId): array
    {
        $url = $this->getOpt(self::ADDITIONAL_INPUTS);

        $resp = $this->client->request('get', $this->baseUrl . $url . "?" . http_build_query([
                'requestToken' => $requestToken,
                'rateId' => $rateId
            ]), [
            RequestOptions::HEADERS => [
                'x-amzn-shipping-business-id', $this->getOpt(self::BUSINESS_ID)
            ],
            RequestOptions::TIMEOUT => $this->getOpt(self::TIMEOUT),
        ]);

        if ($resp->getStatusCode() != 200) {
            throw new RuntimeException($resp->getBody()->getContents());
        }

        $content = $resp->getBody()->getContents();
        $data = json_decode($content, true);
        return $data['payload']['properties'] ?? [];
    }

    /**
     * @throws GuzzleException
     */
    public function purchaseShipment(string $requestToken, string $rateId, DocumentSpecification $documentSpecification): array
    {
        $resp = $this->client->request('post', $this->baseUrl, [
            RequestOptions::HEADERS => [
                'x-amzn-shipping-business-id', $this->getOpt(self::BUSINESS_ID)
            ],
            RequestOptions::TIMEOUT => $this->getOpt(self::TIMEOUT),
            RequestOptions::JSON => [
                'requestToken' => $requestToken,
                'rateId' => $rateId,
                'requestedDocumentSpecification' => $documentSpecification,
            ]
        ]);

        if ($resp->getStatusCode() != 200) {
            throw new RuntimeException($resp->getBody()->getContents());
        }

        $content = $resp->getBody()->getContents();
        $data = json_decode($content, true);
        return $data['payload'] ?? [];
    }


    /**
     * @param string $key
     * @return mixed
     */
    private function getOpt(string $key)
    {
        return $this->options[$key] ?? null;
    }
}