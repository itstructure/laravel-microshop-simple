<?php

namespace App\Services\Uploader\Classes;

use Illuminate\Support\MessageBag;

class Result
{
    /**
     * @var bool
     */
    private $successful;

    /**
     * @var MessageBag|null
     */
    private $errors;

    /**
     * @return Result
     */
    public function setSuccessful(): self
    {
        $this->successful = true;
        return $this;
    }

    /**
     * @return Result
     */
    public function setNoSuccessful(): self
    {
        $this->successful = false;
        return $this;
    }

    /**
     * @param MessageBag|null $errors
     * @return Result
     */
    public function setErrors(?MessageBag $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    /**
     * @return MessageBag|null
     */
    public function getErrors(): ?MessageBag
    {
        return $this->errors;
    }
}