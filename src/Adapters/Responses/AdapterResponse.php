<?php

namespace KielD01\Adapters\Responses;

/**
 * Class AdapterResponse
 * @package App\Packages\TechGenerationAdapters\Adapters\Responses
 * @property mixed data
 * @property int code
 */
class AdapterResponse
{
    public function __construct($data = null, int $code = 200)
    {
        $this->data = $data;
        $this->code = $code;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}
