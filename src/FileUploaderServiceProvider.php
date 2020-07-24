<?php
    namespace mkn\LaravelFileUploader;

    use Illuminate\Support\ServiceProvider;

    class FileUploaderServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations');
            $this->publishes([
                __DIR__.'/database/migrations' => base_path('database/migrations'),
                __DIR__.'FileUpload.php' => base_path('app'),
            ]);
        }
        public function register()
        {
        }
    }
