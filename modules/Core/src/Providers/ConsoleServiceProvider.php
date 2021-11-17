<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\MigrationCreator;

use Modules\Core\Console\Commands\{CastMakeCommand,
    ChannelMakeCommand,
    ComponentMakeCommand,
    ConsoleMakeCommand,
    EventMakeCommand,
    ExceptionMakeCommand,
    JobMakeCommand,
    ListenerMakeCommand,
    ControllerMakeCommand,
    MailMakeCommand,
    MiddlewareMakeCommand,
    MigrateMakeCommand,
    ModelMakeCommand,
    ModuleMakeCommand,
    NotificationMakeCommand,
    ObserverMakeCommand,
    PolicyMakeCommand,
    ProviderMakeCommand,
    RequestMakeCommand,
    ResourceMakeCommand,
    RuleMakeCommand,
    SeederMakeCommand,
    TraitMakeCommand,
    TestMakeCommand};


class ConsoleServiceProvider extends ServiceProvider
{
    protected bool $defer = false;

    /**
     * The available commands
     *
     * @var array
     */
    protected array $commands = [
        CastMakeCommand::class,
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
        ModuleMakeCommand::class,
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->registerMigrator();

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        // $this->commands[] = 'migrator';
        // $this->commands[] = 'modules.migration.creator';
        // $this->commands[] = 'modules.command.migrate.make';
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
