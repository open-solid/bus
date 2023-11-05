<?php

namespace Yceruto\Messenger\Tests\Fixtures;

#[AsMessageHandler]
class MyMessageHandler
{
    public function __invoke(MyMessage $message): MyMessage
    {
        return $message;
    }
}
