<?php

namespace Modules\Core\Providers;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Console\Commands\CastMakeCommand;
use Modules\Core\Console\Commands\ChannelMakeCommand;
use Modules\Core\Console\Commands\ComponentMakeCommand;
use Modules\Core\Console\Commands\ConsoleMakeCommand;
use Modules\Core\Console\Commands\ControllerMakeCommand;
use Modules\Core\Console\Commands\EventMakeCommand;
use Modules\Core\Console\Commands\ExceptionMakeCommand;
use Modules\Core\Console\Commands\FactoryMakeCommand;
use Modules\Core\Console\Commands\JobMakeCommand;
use Modules\Core\Console\Commands\ListenerMakeCommand;
use Modules\Core\Console\Commands\MailMakeCommand;
use Modules\Core\Console\Commands\MiddlewareMakeCommand;
use Modules\Core\Console\Commands\MigrateMakeCommand;
use Modules\Core\Console\Commands\ModelMakeCommand;
use Modules\Core\Console\Commands\ModuleMakeCommand;
use Modules\Core\Console\Commands\NotificationMakeCommand;
use Modules\Core\Console\Commands\ObserverMakeCommand;
use Modules\Core\Console\Commands\PolicyMakeCommand;
use Modules\Core\Console\Commands\ProviderMakeCommand;
use Modules\Core\Console\Commands\RequestMakeCommand;
use Modules\Core\Console\Commands\ResourceMakeCommand;
use Modules\Core\Console\Commands\RuleMakeCommand;
use Modules\Core\Console\Commands\ScopeMakeCommand;
use Modules\Core\Console\Commands\SeederMakeCommand;
use Modules\Core\Console\Commands\TestMakeCommand;
use Modules\Core\Console\Commands\TraitMakeCommand;

class ConsoleServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = true;

    /**
     * The available commands
     *
     * @var array<int, class-string>
     */
    protected array $commands = [
        CastMakeCommand::class,
        ScopeMakeCommand::class,
        ChannelMakeCommand::class,
        ConsoleMakeCommand::class,
        ComponentMakeCommand::class,
        EventMakeCommand::class,
        ExceptionMakeCommand::class,
        JobMakeCommand::class,
        ListenerMakeCommand::class,
        ControllerMakeCommand::class,
        ModelMakeCommand::class,
        NotificationMakeCommand::class,
        ObserverMakeCommand::class,
        PolicyMakeCommand::class,
        ProviderMakeCommand::class,
        RequestMakeCommand::class,
        ResourceMakeCommand::class,
        RuleMakeCommand::class,
        TraitMakeCommand::class,
        TestMakeCommand::class,
        MigrateMakeCommand::class,
        MiddlewareMakeCommand::class,
        MailMakeCommand::class,
        SeederMakeCommand::class,
        FactoryMakeCommand::class,
        ModuleMakeCommand::class,
    ];

    /**
     * Register the commands.
     */
    public function register(): void
    {
        $this->registerMigrator();

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * @return array<int, class-string>
     */
    public function provides(): array
    {
        return $this->commands;
    }

    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator(): void
    {
        $this->app->when(MigrationCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return $app->basePath('stubs');
            });

        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.

        // $this->app->singleton('migrator', function ($app) {
        //     $repository = $app['migration.repository'];
        //     return new Migrator($repository, $app['db'], $app['files'], $app['events']);
        // });

        // $this->app->singleton('modules.migration.creator', function ($app) {
        //     return new MigrationCreator($app['files'], $app->basePath('stubs'));
        // });

        // $this->app->singleton('modules.command.migrate.make', function ($app) {
        //     // Once we have the migration creator registered, we will create the command
        //     // and inject the creator. The creator is responsible for the actual file
        //     // creation of the migrations, and may be extended by these developers.
        //     $creator = $app['modules.migration.creator'];
        //
        //     $composer = $app['composer'];
        //
        //     return new MigrateMakeCommand($creator, $composer);
        // });
    }
}
