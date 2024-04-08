<?php

namespace Modes\Framework\Http\Exceptions;

class MethodNotAllowedException extends HttpException
{
    public array $allowedMethods = [];

    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, array $allowedMethods = [])
    {
        $this->allowedMethods = $allowedMethods;
        parent::__construct($message, $code, $previous);
    }
}