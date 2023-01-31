<?php

namespace Amazon\Entity;

class Rate
{
    public string $id;
    public string $carrierId;
    public string $carrierName;
    public string $serviceId;
    public string $serviceName;
    public string $totalCharge;
    public string $promise;
    public array $supportedDocumentSpecifications;
    public bool $requiresAdditionalInputs;

    public static function getInstanceFromArray(array $data): self
    {
        $r = new Rate();
        $r->id = $data['rateId'] ?? '';
        $r->carrierId = $data['carrierId'] ?? '';
        $r->carrierName = $data['carrierName'] ?? '';
        $r->serviceId = $data['serviceId'] ?? '';
        $r->serviceName = $data['serviceName'] ?? '';
        $r->totalCharge = $data['totalCharge'] ?? '';
        $r->promise = $data['promise'] ?? '';
        $r->supportedDocumentSpecifications = $data['supportedDocumentSpecifications'] ?? [];
        $r->requiresAdditionalInputs = $data['requiresAdditionalInputs'] ?? false;

        return $r;
    }
}