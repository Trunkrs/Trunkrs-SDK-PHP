<?php

namespace Trunkrs\SDK\Exception;

class NotSupportedException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}