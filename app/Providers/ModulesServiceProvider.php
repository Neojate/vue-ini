<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    const MODULE = '/Modules/';

    protected $files;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (is_dir(app_path().self::MODULE)) {

            $modules = config('modules.enable') ?: array_map('class_basename', $this->files->directories(app_path().self::MODULE));

            foreach ($modules as $module) {

                if (!$this->app->routesAreCached()) {

                    $route_files = [
                        app_path().self::MODULE.$module.'/routes.php',
                        app_path().self::MODULE.$module.'/routes/web.php',
                        app_path().self::MODULE.$module.'/routes/api.php',
                    ];

                    foreach ($route_files as $route_file)
                        if ($this->files->exists($route_file))
                            include $route_file;
                }

                $helper     = app_path().self::MODULE.$module.'/helper.php';
                $views      = app_path().self::MODULE.$module.'/Views';
                $trans      = app_path().self::MODULE.$module.'/Translations';
                $commands   = app_path().self::MODULE.$module.'/Console/Commands';

                if ($this->files->exists($helper))
                    include_once $helper;

                if ($this->files->isDirectory($views))
                    $this->loadViewsFrom($views, $module);

                if ($this->files->isDirectory($trans))
                    $this->loadTranslationsFrom($trans, $module);

                if ($this->app->runningInConsole() && $this->files->isDirectory($commands)) {
                    $files = $this->files->files($commands);
                    foreach ($files as $file) {
                        $command = 'App\Modules\\'.$module.'\Console\Commands\\'.$file->getBasename('.php');
                        $this->commands($command);
                    }
                }
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->files = new Filesystem;
    }
}
