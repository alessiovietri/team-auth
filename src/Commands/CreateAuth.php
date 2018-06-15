<?php

namespace AlexTigaer\TeamAuth\Commands;

use Illuminate\Console\Command;

class CreateAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team-auth:create
        {name               :   The name of the role to create}
        {--r=y              :   Choose if users can register with this role, or not [y/n]}
        {--controllers=y    :   Choose whether to create controllers, or not [y/n]}
        {--views=y          :   Choose whether to create views, or not [y/n]}
        {--model=y          :   Choose whether to create the model, or not [y/n]}
        {--config-auth=y    :   Choose whether to edit config\auth file, or not [y/n]}
        {--migrations=y     :   Choose whether to create migrations, or not [y/n]}
        {--m=n              :   Choose whether to run the command migrate, or not [y/n]}
        {--seeder=y         :   Choose whether to create the seeder, or not [y/n]}
        {--s=n              :   Choose whether to run the command db:seed, or not [y/n]}
        {--routes-web=y     :   Choose whether to edit routes\web file, or not [y/n]}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A simple multi-auth package for Laravel';

    /**
     * Package's base dir.
     *
     * @var string
     */
    protected $baseDir;

    /**
     * The word to be replaced.
     *
     * @var string
     */
    protected $word = 'ROLE';

    /**
     * The name of the role.
     *
     * @var string
     */
    protected $name;

    /**
     * Name's plural.
     *
     * @var string
     */
    protected $plural;

    /**
     * Slugged name.
     *
     * @var string
     */
    protected $slug;

    /**
     * Pluraliazed slugged name.
     *
     * @var string
     */
    protected $pluralSlug;

    /**
     * Set if users can register with this role.
     *
     * @var string
     */
    protected $registration;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->baseDir = base_path('vendor\\alextigaer\\team-auth\\src');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Save role's name
        $roleNameWords = preg_split('/(?=[A-Z])/', $this->argument('name'));
        $this->name = $this->argument('name');
        $this->slug = str_slug(implode('_', $roleNameWords), '_');

        // Pluralize role's name
        $roleNameWords[count($roleNameWords) - 1] = str_plural($roleNameWords[count($roleNameWords) - 1]);
        $this->plural = implode('', $roleNameWords);
        $this->pluralSlug = str_slug(strtolower(implode('_', $roleNameWords)), '_');

        // Save role's registration capability
        $this->registration = $this->option('r');

        // Check the name is not empty...
        if($this->name == "")
            // ...and show a message if it is
            $this->error('ERROR: role name missing');
        else{
            // Print repo name
            $this->info('-------------------------------');
            $this->info('- CREATE \''.$this->name.'\' AUTH -');
            $this->info('-------------------------------');
            $this->line('');

            // 1. Create controllers
            if($this->option('controllers') == 'y' || (config('team-auth.create_controllers') && $this->option('controllers') != 'n'))
                $this->create_controllers();

            // 2. Create views
            if($this->option('views') == 'y' || (config('team-auth.create_views') && $this->option('views') != 'n'))
                $this->create_views();

            // 3. Create model
            if($this->option('model') == 'y' || (config('team-auth.create_model') && $this->option('model') != 'n'))
                $this->create_model();

            // 4. Edit config\auth file
            if($this->option('config-auth') == 'y' || (config('team-auth.edit_config_auth') && $this->option('config-auth') != 'n'))
                $this->edit_auth_config();

            // 5. Create migrations
            if($this->option('migrations') == 'y' || (config('team-auth.create_migrations') && $this->option('migrations') != 'n'))
                $this->create_migrations();

            // 6. Create seeder
            if($this->option('seeder') == 'y' || (config('team-auth.create_seeder') && $this->option('seeder') != 'n'))
                $this->create_seeder();

            // 7. Edit routes\web file
            if($this->option('routes-web') == 'y' || (config('team-auth.edit_routes_web') && $this->option('routes-web') != 'n'))
                $this->edit_web_routes();

            $this->warn('NOTE: if you need, you can edit RedirectIfAuthenticated middleware as last operation');
        }
    }

    /**
     * Create controllers files
     *
     * @return void
     */
    public function create_controllers()
    {
        $this->line('- Creating \''.$this->name.'\' controllers...');

        // Edit and copy the package's repository files
        $controllersDir = app_path('Http\\Controllers');
        $controllersNameDir = $controllersDir.'\\'.$this->name;
        if(!file_exists($controllersNameDir))
            mkdir($controllersNameDir);
        $packageControllersDir = $this->baseDir.'\\Controllers';

        // Edit and copy forgot password controller file
        $packageForgotPasswordControllerFileName = 'ForgotPasswordController.php';
        $packageForgotPasswordControllerFile = $packageControllersDir.'\\'.$packageForgotPasswordControllerFileName;
        $forgotPasswordControllerFile = $controllersNameDir.'\\'.$packageForgotPasswordControllerFileName;
        $this->copy_file($packageForgotPasswordControllerFile, $forgotPasswordControllerFile, $this->word, $this->slug);
        $this->copy_file($forgotPasswordControllerFile, $forgotPasswordControllerFile, 'RNAMESPACE', $this->name);

        // Edit and copy login controller file
        $packageLoginControllerFileName = 'LoginController.php';
        $packageLoginControllerFile = $packageControllersDir.'\\'.$packageLoginControllerFileName;
        $loginControllerFile = $controllersNameDir.'\\'.$packageLoginControllerFileName;
        $this->copy_file($packageLoginControllerFile, $loginControllerFile, $this->word, $this->slug);
        $this->copy_file($loginControllerFile, $loginControllerFile, 'RNAMESPACE', $this->name);

        // Edit and copy reset password controller file
        $packageResetPasswordControllerFileName = 'ResetPasswordController.php';
        $packageResetPasswordControllerFile = $packageControllersDir.'\\'.$packageResetPasswordControllerFileName;
        $resetPasswordControllerFile = $controllersNameDir.'\\'.$packageResetPasswordControllerFileName;
        $this->copy_file($packageResetPasswordControllerFile, $resetPasswordControllerFile, $this->word, $this->slug);
        $this->copy_file($resetPasswordControllerFile, $resetPasswordControllerFile, 'RNAMESPACE', $this->name);

        // Edit and copy role controller file
        $packageRoleControllerFileName = $this->word.'Controller.php';
        $packageRoleControllerFile = $packageControllersDir.'\\'.$packageRoleControllerFileName;
        $roleControllerFile = $controllersNameDir.'\\'.str_replace($this->word, $this->name, $packageRoleControllerFileName);
        $this->copy_file($packageRoleControllerFile, $roleControllerFile, $this->word, $this->name);
        $this->copy_file($roleControllerFile, $roleControllerFile, strtolower($this->word), $this->slug);

        if($this->option('r') == 'y'){
            // Edit and copy register controller file
            $packageRegisterControllerFileName = 'RegisterController.php';
            $packageRegisterControllerFile = $packageControllersDir.'\\'.$packageRegisterControllerFileName;
            $registerControllerFile = $controllersNameDir.'\\'.$packageRegisterControllerFileName;
            $this->copy_file($packageRegisterControllerFile, $registerControllerFile, $this->word, $this->slug);
            $this->copy_file($registerControllerFile, $registerControllerFile, 'RNAMESPACE', $this->name);
        }

        // Show a confirmation message
        $this->info('> \''.$this->name.'\' controllers created!');
        $this->line('');
    }

    /**
     * Create views files
     *
     * @return void
     */
    public function create_views()
    {
        $this->line('- Creating \''.$this->name.'\' views...');

        // Edit and copy package's views files
        $viewsDir = resource_path('views');
        $layoutsDir = $viewsDir.'\\layouts';
        $viewsNameDir = $viewsDir.'\\'.$this->slug;
        $viewsNamePasswordsDir = $viewsNameDir.'\\passwords';
        if(!file_exists($layoutsDir)) {
            mkdir($layoutsDir);

            // Copy layout's blade file
            $packageAppLayout = $this->baseDir.'\\Views\\layout\\app.blade.php';
            $appLayout = $layoutsDir.'\\app.blade.php';
            $this->copy_file($packageAppLayout, $appLayout);
        }
        if(!file_exists($viewsNameDir))
            mkdir($viewsNameDir);
        if(!file_exists($viewsNamePasswordsDir))
            mkdir($viewsNamePasswordsDir);
        $packageViewsDir = $this->baseDir.'\\Views';

        // Edit and copy passwords\email blade file
        $packagePasswordsEmailBladeFileName = '\\passwords\\email.blade.php';
        $packagePasswordsEmailBladeFile = $packageViewsDir.'\\'.$packagePasswordsEmailBladeFileName;
        $passwordsEmailBladeFile = $viewsNameDir.'\\'.$packagePasswordsEmailBladeFileName;
        $this->copy_file($packagePasswordsEmailBladeFile, $passwordsEmailBladeFile, $this->word, $this->slug);

        // Edit and copy passwords\reset blade file
        $packagePasswordsResetBladeFileName = '\\passwords\\reset.blade.php';
        $packagePasswordsResetBladeFile = $packageViewsDir.'\\'.$packagePasswordsResetBladeFileName;
        $passwordsResetBladeFile = $viewsNameDir.'\\'.$packagePasswordsResetBladeFileName;
        $this->copy_file($packagePasswordsResetBladeFile, $passwordsResetBladeFile, $this->word, $this->slug);

        // Edit and copy dashboard blade file
        $packageDashboardBladeFileName = 'dashboard.blade.php';
        $packageDashboardBladeFile = $packageViewsDir.'\\'.$packageDashboardBladeFileName;
        $dashboardBladeFile = $viewsNameDir.'\\'.$packageDashboardBladeFileName;
        $this->copy_file($packageDashboardBladeFile, $dashboardBladeFile, $this->word, strtoupper($this->name));

        // Edit and copy login blade file
        $packageLoginBladeFileName = 'login.blade.php';
        $packageLoginBladeFile = $packageViewsDir.'\\'.$packageLoginBladeFileName;
        $loginBladeFile = $viewsNameDir.'\\'.$packageLoginBladeFileName;
        $this->copy_file($packageLoginBladeFile, $loginBladeFile, $this->word, $this->slug);

        if($this->option('r') == 'y'){
            // Edit and copy register blade file
            $packageRegisterBladeFileName = 'register.blade.php';
            $packageRegisterBladeFile = $packageViewsDir.'\\'.$packageRegisterBladeFileName;
            $registerBladeFile = $viewsNameDir.'\\'.$packageRegisterBladeFileName;
            $this->copy_file($packageRegisterBladeFile, $registerBladeFile, $this->word, $this->slug);
        }

        // Show a confirmation message
        $this->info('> \''.$this->name.'\' views created!');
        $this->line('');
    }

    /**
     * Create model file
     *
     * @return void
     */
    public function create_model()
    {
        $this->line('- Creating \''.$this->name.'\' model...');

        // Edit and copy package's model
        $packageModelDir = $this->baseDir.'\\Model';

        // Create models directory, if it doesn't exist
        if(!file_exists(config('team-auth.models_dir')))
            mkdir(config('team-auth.models_dir'));

        // Check if model directory has double slash at the end
        if(!substr(config('team-auth.models_dir'), strlen(config('team-auth.models_dir')) - strlen('\\')) == '\\')
            $modelsDir = config('team-auth.models_dir');
        else
            $modelsDir = substr(config('team-auth.models_dir'), 0, strlen(config('team-auth.models_dir')) - strlen('\\'));

        // Edit and copy model file
        $packageRoleModelFileName = $this->word.'.php';
        $packageRoleModelFile = $packageModelDir.'\\'.$packageRoleModelFileName;
        $roleModelFile = $modelsDir.'\\'.str_replace($this->word, $this->name, $packageRoleModelFileName);
        $this->copy_file($packageRoleModelFile, $roleModelFile, $this->word, $this->name);
        $this->copy_file($roleModelFile, $roleModelFile, 'GUARD', $this->pluralSlug);

        // Edit model namespace
        $needle = 'namespace App;';
        $string = "namespace ".$modelsDir.";";
        $this->add_string($roleModelFile, $needle, $string, true);

        // Show a confirmation message
        $this->info('> \''.$this->name.'\' model created!');
        $this->line('');
    }

    /**
     * Edit config\auth file
     *
     * @return void
     */
    public function edit_auth_config()
    {
        $this->line('- Editing config\\auth...');

        $file = config_path('auth.php');

        // Check if model directory has double slash at the end
        if(substr(config('team-auth.models_dir'), strlen(config('team-auth.models_dir')) - strlen('\\')) == '\\')
            $modelsDir = config('team-auth.models_dir');
        else
            $modelsDir = config('team-auth.models_dir').'\\';

        // Add the guards
        if(!array_key_exists($this->slug, config('auth.guards')) || !array_key_exists($this->slug.'-api', config('auth.guards'))){
            $needle = "'guards' => [";
            $string = "\n\t\t'".$this->slug."' => [\n\t\t\t'driver' => 'session',\n\t\t\t'provider' => '".$this->pluralSlug."',\n\t\t],\n";
            $string .= "\n\t\t'".$this->slug."-api' => [\n\t\t\t'driver' => 'token',\n\t\t\t'provider' => '".$this->pluralSlug."',\n\t\t],\n";
            $this->add_string($file, $needle, $string);
        }

        // Add the provider
        if(!array_key_exists($this->pluralSlug, config('auth.providers'))){
            $needle = "'providers' => [";
            $string = "\n\t\t'".$this->pluralSlug."' => [\n\t\t\t'driver' => 'eloquent',\n\t\t\t'model' => ".$modelsDir.$this->name."::class,\n\t\t],\n";
            $this->add_string($file, $needle, $string);
        }

        // Add the password reset
        if(!array_key_exists($this->pluralSlug, config('auth.passwords'))){
            $needle = "'passwords' => [";
            $string = "\n\t\t'".$this->pluralSlug."' => [\n\t\t\t'provider' => '".$this->pluralSlug."',\n\t\t\t'table' => '".$this->pluralSlug."_password_resets',\n\t\t\t'expire' => 60,\n\t\t],\n";
            $this->add_string($file, $needle, $string);
        }

        // Show a confirmation message
        $this->info('> config\\auth edited!');
        $this->line('');
    }

    /**
     * Create migration files
     *
     * @return void
     */
    public function create_migrations()
    {
        $this->line('- Creating \''.$this->name.'\' migrations...');

        // Build the correct name for the migrations
        $migrationFileName = 'create_'.$this->pluralSlug.'_table';
        $passwordsMigrationFileName = 'create_'.$this->pluralSlug.'_password_resets_table';

        // Call the command to create the migration file
        $this->callSilent('make:migration', [
            'name' => $migrationFileName
        ]);

        // Set the right columns inside the migration
        $migrations = scandir(database_path('migrations'), SCANDIR_SORT_DESCENDING);
        $roleMigrationFile = database_path('migrations\\'.$migrations[0]);
        $needle = '$table->increments(\'id\');';
        $string = "\n\t\t\t".'$table->string(\'name\');';
        $string .= "\n\t\t\t".'$table->string(\'email\')->unique();';
        $string .= "\n\t\t\t".'$table->rememberToken();';
        $this->add_string($roleMigrationFile, $needle, $string);

        // Call the command to create the migration file
        $this->callSilent('make:migration', [
            'name' => $passwordsMigrationFileName
        ]);

        // Set the right columns inside the migration
        $migrations = scandir(database_path('migrations'), SCANDIR_SORT_DESCENDING);
        $rolePasswordResetsMigrationFile = database_path('migrations\\'.$migrations[0]);
        $string = "\t\t\t".'$table->string(\'email\')->index();';
        $string .= "\n\t\t\t".'$table->string(\'token\');';
        $this->add_string($rolePasswordResetsMigrationFile, $needle, $string, true);
        $needle = '$table->timestamps()';
        $string = "\t\t\t".'$table->timestamp(\'created_at\')->nullable();';
        $this->add_string($rolePasswordResetsMigrationFile, $needle, $string, true);

        // Run the command migrate if the option is set
        if($this->option('m') == 'y'){
            $this->callSilent('migrate');

            // Show a confirmation message
            $this->info('> \''.$this->name.'\' migration created and run!');
            $this->line('');
        }
        else{
            // Show a confirmation message
            $this->info('> \''.$this->name.'\' migration created!');
            $this->line('');
        }
    }

    /**
     * Create seeder file
     *
     * @return void
     */
    public function create_seeder()
    {
        $this->line('- Creating \''.$this->name.'\' seeder...');

        // Edit and copy the package's seeder
        $packageSeederDir = $this->baseDir.'\\Seeder';

        // Check if model directory has double slash at the end
        if(!substr(config('team-auth.models_dir'), strlen(config('team-auth.models_dir')) - strlen('\\')) == '\\')
            $modelsDir = config('team-auth.models_dir');
        else
            $modelsDir = substr(config('team-auth.models_dir'), 0, strlen(config('team-auth.models_dir')) - strlen('\\'));

        // Edit and copy seeder file
        $packageRoleSeederFileName = $this->word.'Seeder.php';
        $packageRoleSeederFile = $packageSeederDir.'\\'.$packageRoleSeederFileName;
        $roleSeederFile = database_path('seeds\\'.str_replace($this->word, $this->name, $packageRoleSeederFileName));
        $this->copy_file($packageRoleSeederFile, $roleSeederFile, $this->word, $this->name);
        $this->copy_file($roleSeederFile, $roleSeederFile, 'MODELSDIR', $modelsDir);

        // Edit database seeder to call generated seeder
        $dbSeederFile = database_path('seeds\\DatabaseSeeder.php');
        $needle = "\t{";
        $string = "\n\t\t".'$this->call('.$this->name.'Seeder::class);';
        $this->add_string($dbSeederFile, $needle, $string);

        // Run the command db:seed if the option is set
        if($this->option('s') == 'y'){
            $this->callSilent('db:seed');

            // Show a confirmation message
            $this->info('> \''.$this->name.'\' seeder created and run!');
            $this->line('');
        }
        else{
            // Show a confirmation message
            $this->info('> \''.$this->name.'\' seeder created!');
            $this->line('');
        }
    }

    /**
     * Edit routes\web file
     *
     * @return void
     */
    public function edit_web_routes()
    {
        $this->line('- Editing routes\\web...');

        // Add role's routes inside the file
        $file = base_path('routes\\web.php');
        $routesWebContent = file_get_contents($file);
        $routesWebContent .= "\n".'Route::prefix(\''.$this->slug.'\')->group(function() {';
        $routesWebContent .= "\n\t// ".$this->name.' Authentication Routes...';
        $routesWebContent .= "\n\t".'$this->get(\'login\', \''.$this->name.'\LoginController@showLoginForm\')->name(\''.$this->slug.'-login\');';
        $routesWebContent .= "\n\t".'$this->post(\'login\', \''.$this->name.'\LoginController@login\');';
        $routesWebContent .= "\n\t".'$this->post(\'logout\', \''.$this->name.'\LoginController@logout\')->name(\''.$this->slug.'-logout\');';
        if($this->option('r') == 'y'){
            $routesWebContent .= "\n\n\t// ".$this->name.' Registration Routes...';
            $routesWebContent .= "\n\t".'$this->get(\'register\', \''.$this->name.'\RegisterController@showRegistrationForm\')->name(\''.$this->slug.'-register\');';
            $routesWebContent .= "\n\t".'$this->post(\'register\', \''.$this->name.'\RegisterController@register\');';
        }
        $routesWebContent .= "\n\n\t// ".$this->name.' Password Reset Routes...';
        $routesWebContent .= "\n\t".'$this->get(\'password/reset\', \''.$this->name.'\ForgotPasswordController@showLinkRequestForm\')->name(\''.$this->slug.'-password.request\');';
        $routesWebContent .= "\n\t".'$this->post(\'password/email\', \''.$this->name.'\ForgotPasswordController@sendResetLinkEmail\')->name(\''.$this->slug.'-password.email\');';
        $routesWebContent .= "\n\t".'$this->get(\'reset/{token}\', \''.$this->name.'\ResetPasswordController@showResetForm\')->name(\''.$this->slug.'-password.reset\');';
        $routesWebContent .= "\n\t".'$this->post(\'password/reset\', \''.$this->name.'\ResetPasswordController@reset\');';
        $routesWebContent .= "\n".'});'."\n";
        file_put_contents($file, $routesWebContent);

        // Show a confirmation message
        $this->info('> routes\\web edited!');
        $this->line('');
    }

    /**
     * Edit and copy files.
     *
     * @param $sourcePath
     * @param $destPath
     * @param $search
     * @param $replace
     * @return void
     */
    private function copy_file($sourcePath, $destPath, $search = '', $replace = '')
    {
        // Read source file
        $sourceFileContent = file_get_contents($sourcePath);

        // Replace all occurrences of the word in the file with the replace string
        if($search != '')
            $destFileContent = str_replace($search, $replace, $sourceFileContent);
        else
            $destFileContent = $sourceFileContent;

        // Write destination file
        file_put_contents($destPath, $destFileContent);
    }

    /**
     * Add a string to a file in a specified position.
     *
     * @param $file
     * @param $needle
     * @param $string
     * @param bool $overwrite
     * @param string $glue
     * @param bool $debug
     * @return void
     */
    private function add_string($file, $needle, $string, $overwrite = false, $glue = "\n", $debug = false)
    {
        if($debug){
            $this->info('File: '.$file."\n");
            $this->info('Needle: '.$needle."\n");
            $this->info('String: '.$string."\n");
            $this->info('Overwrite: '.($overwrite ? 'TRUE' : 'FALSE')."\n");
            $this->info('Glue: '.$glue."\n");
        }

        $fileContent = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($fileContent as $index => $value) {
            if($debug)
                $this->info($fileContent[$index].$glue);
            if(strpos($value, $needle) > -1){
                if($debug)
                    $this->info("> Found at ".$index."\n");
                if($overwrite)
                    $fileContent[$index] = $string;
                else
                    $fileContent[$index] .= $string;
                break;
            }
        }
        $fileContent = implode($glue, $fileContent);
        file_put_contents($file, $fileContent);
    }
}
