<?php

namespace KielD01\Adapters;

/**
 * Class TestRpcAdapter
 * @package KielD01\Adapters
 */
class TestRpcAdapter extends RPCAdapter
{

    /**
     * Test method for route
     */
    public function testMethod(): void
    {
        $this->setResponse([
            'rpc' => true,
            'is_adapted' => true
        ]);
    }

}