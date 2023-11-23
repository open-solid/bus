<?php

namespace OpenSolid\Tests\Messenger\Fixtures;

#[AsMessageHandler]
class MyMessageHandler
{
    public function __invoke(MyMessage $message): MyMessage
    {
        return $message;
    }
}
