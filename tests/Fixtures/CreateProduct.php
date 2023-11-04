<?php

namespace Yceruto\Messenger\Tests\Fixtures;

use Yceruto\Messenger\Model\Message;

/**
 * @psalm-immutable
 */
class CreateProduct implements Message
{
    public function __construct()
    {
    }
}
