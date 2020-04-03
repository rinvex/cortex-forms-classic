<?php

declare(strict_types=1);

namespace Cortex\Forms\Providers;

use Cortex\Forms\Models\Form;
use Illuminate\Routing\Router;
use Rinvex\Forms\Models\FormResponse;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\View\Compilers\BladeCompiler;
use Cortex\Forms\Console\Commands\SeedCommand;
use Cortex\Forms\Console\Commands\InstallCommand;
use Cortex\Forms\Console\Commands\MigrateCommand;
use Cortex\Forms\Console\Commands\PublishCommand;
use Cortex\Forms\Console\Commands\RollbackCommand;
use Illuminate\Database\Eloquent\Relations\Relation;

class FormsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        SeedCommand::class => 'command.cortex.forms.seed',
        InstallCommand::class => 'command.cortex.forms.install',
        MigrateCommand::class => 'command.cortex.forms.migrate',
        PublishCommand::class => 'command.cortex.forms.publish',
        RollbackCommand::class => 'command.cortex.forms.rollback',
    ];

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'cortex.forms');

        $this->bindBladeCompiler();

        // Bind eloquent models to IoC container
        $this->app['config']['rinvex.forms.models.form'] === Form::class
        || $this->app->alias('rinvex.forms.form', Form::class);

        $this->app['config']['rinvex.forms.models.form_response'] === FormResponse::class
        || $this->app->alias('rinvex.forms.form_response', FormResponse::class);

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router, Dispatcher $dispatcher): void
    {
        // Bind route models and constrains
        $router->pattern('form', '[a-zA-Z0-9-_]+');
        $router->pattern('form_response', '[a-zA-Z0-9-_]+');
        $router->model('form', config('rinvex.forms.models.form'));
        $router->model('form_response', config('rinvex.forms.models.form_response'));

        // Map relations
        Relation::morphMap([
            'form' => config('rinvex.forms.models.form'),
            'form_response' => config('rinvex.forms.models.form_response'),
        ]);

        // Load resources
        $this->loadRoutesFrom(__DIR__.'/../../routes/web/adminarea.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web/frontarea.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/forms');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/forms');
        ! $this->autoloadMigrations('cortex/forms') || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->app->runningInConsole() || $dispatcher->listen('accessarea.ready', function ($accessarea) {
            ! file_exists($menus = __DIR__."/../../routes/menus/{$accessarea}.php") || require $menus;
            ! file_exists($breadcrumbs = __DIR__."/../../routes/breadcrumbs/{$accessarea}.php") || require $breadcrumbs;
        });

        // Publish Resources
        $this->publishesLang('cortex/forms', true);
        $this->publishesViews('cortex/forms', true);
        $this->publishesConfig('cortex/forms', true);
        $this->publishesMigrations('cortex/forms', true);
    }

    /**
     * Bind blade compiler.
     *
     * @return void
     */
    protected function bindBladeCompiler(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            // @form('contact-us')
            $bladeCompiler->directive('form', function ($slug = null) {
                $slug = trim($slug, " \t\n\r\0\x0B'\"");

                return $slug ? "<?php echo view('cortex/forms::frontarea.pages.embed-internal', ['form' => app('rinvex.forms.form')->where('slug', '{$slug}')->first()]); ?>"
                    : trans('cortex/forms::message.invalid_form');
            });
        });
    }
}
