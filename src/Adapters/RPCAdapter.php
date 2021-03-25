<?php

namespace KielD01\Adapters;

use Illuminate\Http\Request;
use KielD01\Responses\AdapterResponse;

/**
 * Class RPCAdapter
 * @package App\Packages\TechGenerationAdapters\Adapters
 * @property mixed data
 * @property int code
 */
abstract class RPCAdapter
{
    protected Request $request;

    /**
     * Returns an adapted response
     *
     * @return AdapterResponse
     */
    public function getResponse(): AdapterResponse
    {
        return new AdapterResponse($this->getData(), $this->getCode());
    }

    /**
     * Sets needed response, which must be send back via API as a response
     *
     * @param $data
     * @param int $code
     */
    protected function setResponse($data, int $code = 200): void
    {
        $this->setData($data);
        $this->setCode($code);
    }

    /**
     * Internal Data setter
     *
     * @param $data
     * @return RPCAdapter
     */
    private function setData($data): RPCAdapter
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Internal Code (status code) setter
     *
     * @param int $code
     * @return RPCAdapter
     */
    private function setCode(int $code = 200): RPCAdapter
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Returns the Data, which has been set
     *
     * @return mixed
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * Returns a Code (status code), which has been set before
     *
     * @return int
     */
    protected function getCode(): int
    {
        return $this->code;
    }

    /**
     * Sets the Request
     *
     * @param Request $request
     * @return RPCAdapter
     */
    public function setRequest(Request $request): RPCAdapter
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Returns a Request
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

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
