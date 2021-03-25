<?php

namespace KielD01\Clients;

use Graze\GuzzleHttp\JsonRpc\Client as JsonRpcClient;
use Graze\GuzzleHttp\JsonRpc\Message\ResponseInterface;
use Illuminate\Support\Collection;
use JsonException;
use function array_merge;
use function mb_strtolower;
use function sprintf;

/**
 * Class RPCClient
 * @package App\Packages\TechGenerationAdapters\Clients
 * @property string|Collection environment
 * @property array configs
 * @property JsonRpcClient jsonRpcClient
 */
class RpcClient
{

    /** @var JsonRpcClient */
    protected JsonRpcClient $jsonRpcClient;

    /** @var string|Collection */
    protected $environment;

    public function __construct()
    {
        $this->configs = collect(config('adapters', $this->getDefaultConfigs()));
        $environment = $this->environment ?? config('app.env');

        $this->setEnvironment($environment);
        $this->setJsonRpcClient();
    }

    /**
     * Returns a default set of the configs, if they does not exists on the config/adapters.php
     *
     * @return array
     */
    private function getDefaultConfigs(): array
    {
        return [
            'default_middleware_groups' => null,
            'middleware_groups' => ['web'],
            'default_environment' => 'local',
            'use_environmental_proxy' => env('ADAPTERS_ENV_ENABLED', false),
            'environment' => mb_strtolower(env('ADAPTERS_ENV', env('APP_ENV', 'local'))),
            'proxy' => [
                'schema' => env('ADAPTERS_DEFAULT_SCHEMA', 'https'),
                'uri' => env('ADAPTERS_DEFAULT_URI', 'httpbin.org'),
            ],
            'client' => [
                'headers' => [],
                'verify' => false,
            ],
            'environments' => [
                'local' => [
                    'proxy' => [
                        'schema' => 'https',
                        'uri' => 'httpbin.org',
                    ],
                    'client' => [
                        'headers' => [
                            'content-type' => 'application/json',
                            'accept' => 'application/json'
                        ],
                        'verify' => false,
                    ],
                ]
            ]
        ];
    }

    /**
     * Sets an environment from configs
     *
     * @param string|null $environment
     * @return RpcClient
     */
    private function setEnvironment(string $environment = null): RpcClient
    {
        $defaultAdapterProxy = array_merge(
            ['proxy' => config('adapters.proxy')],
            ['client' => config('adapters.client')]
        );

        if (config('adapters.use_environmental_proxy')) {
            if (!$environment) {
                $environment = config('adapters.default_environment');
            }

            $environment = mb_strtolower($environment);

            $defaultEnvironment = config(
                sprintf(
                    'adapters.environments.%s',
                    config('adapters.default_environment')
                ),
                $defaultAdapterProxy
            );

            $this->environment = config(
                sprintf('adapters.environments.%s', $environment),
                $defaultEnvironment
            );

            return $this;
        }

        $this->environment = $defaultAdapterProxy;

        return $this;
    }

    /**
     * Creates an instance of an JsonRpcClient
     *
     * @return RpcClient
     */
    private function setJsonRpcClient(): RpcClient
    {
        $uri = sprintf(
            '%s://%s',
            $this->environment['proxy']['schema'],
            $this->environment['proxy']['uri'],
        );

        $this->jsonRpcClient = JsonRpcClient::factory(
            $uri
        );

        return $this;
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws JsonException
     */
    protected function transformResponse(ResponseInterface $response)
    {
        return json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
