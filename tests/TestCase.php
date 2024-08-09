<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use WpAi\EleLLM\EleLLM;

abstract class TestCase extends BaseTestCase
{
    protected EleLLM $llm;

    protected function setUp(): void
    {
        parent::setUp();
        require __DIR__.'/../vendor/autoload.php';

        $this->llm = new EleLLM(
            promptFileDir: __DIR__.'/prompts',
            promptCacheDir: __DIR__.'/../cache',
        );
    }
}
