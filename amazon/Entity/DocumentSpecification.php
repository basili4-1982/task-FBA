<?php

namespace Amazon\Entity;

class DocumentSpecification
{
    public string $format;
    public array $size;
    public string $dpi;
    public string $pageLayout;
    public string $needFileJoining;
    public array $requestedDocumentTypes;

    public function __construct()
    {
        $this->format = 'PNG';
        $this->size = [
            'width' => 4,
            'length' => 6,
            'unit' => 'INCH',
        ];
        $this->dpi = 300;
        $this->pageLayout = "DEFAULT";
        $this->needFileJoining = false;
        $this->requestedDocumentTypes = ["LABEL"];
    }
}