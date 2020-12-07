<?php

namespace App\UI\Command\Operation;

use LogicException;

class OperationNotAvailableException extends LogicException
{
    public function __construct($message = 'Operation not available')
    {
        parent::__construct($message);
    }
}