<?php

namespace KielD01\Console\Commands;

/**
 * Class MakeAdapterCommand
 * @package App\Packages\TechGenerationAdapters\Console\Commands
 * @property string signature
 * @property string description
 */
class MakeAdapterCommand extends AdapterCommand
{
    protected $signature = 'make:adapters:adapter ' .
    '{adapter : Name for the Adapter}';

    protected $description = 'Creates an Adapter as a Proxy for Routes to interact with V2 API';

    public function handle()
    {
        $adapter = \sprintf('%sAdapter', $this->argument('adapter'));
        $this->info(\sprintf('Preparing to create a `%s` class', $adapter));

        $result = $this->generateStub($adapter);

        !$result ?
            $this->output->error(\sprintf('Failed to create `%s` adapter. Adapter already exists.', $adapter)) :
            $this->output->success(\sprintf('Adapter `%s` has been created successfully', $adapter));
    }

    /**
     * Generates a valid stub and creates an Adapter
     *
     * @param string $className
     * @return bool
     */
    private function generateStub(string $className): bool
    {
        $adaptersDir = app_path(
            'Adapters' . DIRECTORY_SEPARATOR
        );

        if (!mkdir($adaptersDir, 0755, true) && !is_dir($adaptersDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $adaptersDir));
        }

        $filePath = \sprintf('%s%s.php', $adaptersDir, $className);

        if (is_file($filePath)) {
            return false;
        }

        $rawStub = $this->getStub('adapter');
        $stub = preg_replace('/\{class\}/', $className, $rawStub);

        file_put_contents($filePath, $stub);

        return true;
    }
}
