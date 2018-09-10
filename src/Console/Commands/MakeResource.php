<?php

namespace AdamJedlicka\Luna\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class MakeResource extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'luna:resource {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes new resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    public function getStub()
    {
        return __DIR__ . '/../../../resources/stubs/Resource.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . config('luna.directory') . '\\Resources';
    }
}
