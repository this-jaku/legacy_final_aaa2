<?php

namespace App\Application;

abstract class ServiceCore
{
    /** @var string */
    protected $logDirectory;

    /** @var string */
    private $transactionId;

    public function __construct(string $logDirectory)
    {
        $this->logDirectory = $logDirectory;
    }

    protected function getTransactionId(): string
    {
        if (!$this->transactionId) {
            $this->transactionId = uniqid();
        }

        return $this->transactionId;
    }

    abstract public function info(string $logMessage): void;

    abstract public function error(string $logMessage): void;
}
