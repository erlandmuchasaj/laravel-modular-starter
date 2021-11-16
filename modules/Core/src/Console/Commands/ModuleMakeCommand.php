<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ModuleMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create blueprint for a new module';

    /**
     * The console command name.
     *
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->generateModuleStructure();
    }

    /**
     * Generate the entire structure of a module
     * @return void
     */
    public function generateModuleStructure(): void
    {
        $moduleName = $this->getModuleInput();

        if ($this->files->exists("modules/{$moduleName}")) {
            $this->error("Module: {$moduleName}, already exists!");
            return;
        }

        // dd($this->files->isDirectory(dirname("modules/{$moduleName}")));
        //
        // if (! $this->files->isDirectory(dirname($path))) {
        //     $this->files->makeDirectory(dirname($path), 0755, true);
        // }


        /**
         * Create Module Folder Structures
         */
        $this->files->makeDirectory("modules/{$moduleName}");
        $this->files->makeDirectory("modules/{$moduleName}/bootstrap");
        $this->files->makeDirectory("modules/{$moduleName}/config");
        $this->files->makeDirectory("modules/{$moduleName}/database");
        $this->files->makeDirectory("modules/{$moduleName}/database/factories");
        $this->files->makeDirectory("modules/{$moduleName}/database/migrations");
        $this->files->makeDirectory("modules/{$moduleName}/database/seeders");
        $this->files->makeDirectory("modules/{$moduleName}/resources");
        $this->files->makeDirectory("modules/{$moduleName}/resources/lang");
        $this->files->makeDirectory("modules/{$moduleName}/resources/lang/en");
        $this->files->makeDirectory("modules/{$moduleName}/resources/views");
        $this->files->makeDirectory("modules/{$moduleName}/resources/views/components");
        $this->files->makeDirectory("modules/{$moduleName}/resources/views/errors");
        $this->files->makeDirectory("modules/{$moduleName}/resources/views/layouts");
        $this->files->makeDirectory("modules/{$moduleName}/resources/views/layouts/includes");
        $this->files->makeDirectory("modules/{$moduleName}/resources/views/pages");
        $this->files->makeDirectory("modules/{$moduleName}/resources/views/partials");
        $this->files->makeDirectory("modules/{$moduleName}/routes");
        $this->files->makeDirectory("modules/{$moduleName}/tests");

        $this->files->makeDirectory("modules/{$moduleName}/src");
        $this->files->makeDirectory("modules/{$moduleName}/src/Console");
        $this->files->makeDirectory("modules/{$moduleName}/src/Console/Commands");
        $this->files->makeDirectory("modules/{$moduleName}/src/Enums");
        $this->files->makeDirectory("modules/{$moduleName}/src/Events");
        $this->files->makeDirectory("modules/{$moduleName}/src/Exceptions");
        $this->files->makeDirectory("modules/{$moduleName}/src/Http");
        $this->files->makeDirectory("modules/{$moduleName}/src/Http/Controllers");
        $this->files->makeDirectory("modules/{$moduleName}/src/Http/Controllers/Api");
        $this->files->makeDirectory("modules/{$moduleName}/src/Http/Middleware");
        $this->files->makeDirectory("modules/{$moduleName}/src/Http/Middleware/Api");
        $this->files->makeDirectory("modules/{$moduleName}/src/Http/Requests");
        $this->files->makeDirectory("modules/{$moduleName}/src/Http/Resources");
        $this->files->makeDirectory("modules/{$moduleName}/src/Http/ViewComposers");
        $this->files->makeDirectory("modules/{$moduleName}/src/Jobs");
        $this->files->makeDirectory("modules/{$moduleName}/src/Listeners");
        $this->files->makeDirectory("modules/{$moduleName}/src/Models");
        $this->files->makeDirectory("modules/{$moduleName}/src/Notifications");
        $this->files->makeDirectory("modules/{$moduleName}/src/Observers");
        $this->files->makeDirectory("modules/{$moduleName}/src/Policies");
        $this->files->makeDirectory("modules/{$moduleName}/src/Providers");
        $this->files->makeDirectory("modules/{$moduleName}/src/Repositories");
        $this->files->makeDirectory("modules/{$moduleName}/src/Rules");
        $this->files->makeDirectory("modules/{$moduleName}/src/Services");
        $this->files->makeDirectory("modules/{$moduleName}/src/Traits");
        $this->files->makeDirectory("modules/{$moduleName}/src/Validators");

        /**
         * Add .gitkeep files in folders in order to keep them in repositories
         * @note if we do not add .gitkeep the folder won't be pushed on repository.
         */

        $this->files->put("modules/{$moduleName}/bootstrap/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/config/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/config/config.php", "<?php \n\n/*\n * You can place your custom module configuration in here.\n */\n \nreturn [\n\n];\n");

        $this->files->put("modules/{$moduleName}/database/factories/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/database/migrations/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/database/seeders/.gitkeep", "");

        $this->files->put("modules/{$moduleName}/resources/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/lang/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/lang/en/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/lang/en/messages.php", "<?php \n\n/*\n * You can place your custom module messages in here.\n */\n \nreturn [\n\n];\n");
        $this->files->put("modules/{$moduleName}/resources/views/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/components/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/errors/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/layouts/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/layouts/includes/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/pages/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/partials/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/routes/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/routes/api.php", "<?php \n\n/*\n * You can place your custom module routes for api.\n */");
        $this->files->put("modules/{$moduleName}/routes/web.php", "<?php \n\n/*\n * You can place your custom module routes for web.\n */");
        $this->files->put("modules/{$moduleName}/src/helpers.php", "<?php \n\n ");


    }


    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getModuleInput(): string
    {
        return Str::of(strval($this->argument('module')))->trim()->studly();
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['module', InputArgument::REQUIRED, 'Module name'],
        ];
    }
}
