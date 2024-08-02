<?php

namespace OpenSolid\Tests\Bus\Fixtures;

#[AsMessageHandler]
class MyMessageHandler
{
    public function __invoke(MyMessage $message): MyMessage
    {
        return $message;
    }
}
