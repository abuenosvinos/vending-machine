<?php

namespace App\UI\Command\Operation;

use LogicException;

class OperationNotAvailableException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Operation not available');
    }
}