<?php

namespace WpAi\EleLLM\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\FileViewFinder;

class LlmPromptsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'llm-prompts'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('llm-prompts.php'),
        ]);

        $this->app->bind('view.finder', function ($app) {

            $paths = array_merge($app['config']['view.paths'], [config('llm-prompts.path')]);

            return new FileViewFinder($app['files'], $paths);
        });

        $this->loadViewsFrom(config('llm-prompts.path'), 'llmprompts');
    }
}
