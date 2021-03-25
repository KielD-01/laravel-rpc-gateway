<?php
/**
 * Request Method can be one of the following:
 *
 * - any
 * - get
 * - post
 * - delete
 * - put
 * - patch
 *
 * Case does not matter as on parse it would be converted into lower-case via \mb_strtolower($requestMethod)
 *
 * Route adaption template should be matched as described below:
 *
 * '{actionOrUrl.[requestMethod]}' => [Adapter::class, 'adapterMethod', isRunnable/isDiscoverable/isActive (true, false)]
 */

use KielD01\Adapters\RPCAdapter;

return [
    /** @uses RPCAdapter::testMethod() */
    'rpc.gateway::test_method.get' => [RPCAdapter::class, 'testMethod', true]
];