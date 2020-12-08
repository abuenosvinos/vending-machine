<?php

declare(strict_types = 1);

namespace App\Shared\Domain\Bus\Event;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

interface EventHandler extends MessageSubscriberInterface
{
}
