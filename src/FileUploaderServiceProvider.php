<?php
    namespace mkn\LaravelFileUploader;

    use Illuminate\Support\ServiceProvider;

    class FileUploaderServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        }
        public function register()
        {
        }
    }
