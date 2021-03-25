<?php

namespace KielD01\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class AdapterCommand
 * @package KielD01\Commands
 */
class AdapterCommand extends Command
{

    /**
     * Returns a stub
     *
     * @param string $name
     * @return string
     */
    protected function getStub(string $name): string
    {
        return file_get_contents(
            __DIR__ . DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'stub' .
            DIRECTORY_SEPARATOR . \sprintf('%s.stub', $name)
        );
    }
}
