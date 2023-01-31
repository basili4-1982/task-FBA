<?php

namespace App\Exceptions;


use RuntimeException;
use Throwable;

class NotEnoughData extends RuntimeException
{
    private array $data;

    public function __construct($message = "", $code = 0, array $data = [], Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function getData(): array
    {
        return $this->data;
    }
}