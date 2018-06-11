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
        {--r=y              :   Choose if users can register with this role, or not [y/n]}';

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
        $this->name = $this->argument('name');
        $this->plural = str_plural($this->name);
        $this->slug = str_slug($this->name);

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
            $this->create_controllers();

            // 2. Create views
            $this->create_views();

            // 3. Create model
            $this->create_model();

            // 4. Edit config\auth file
            $this->edit_auth_config();

            // 5. Create migrations
//            $this->create_migrations();

            // 6. Create seeder
//            $this->create_seeder();

            // 7. Edit routes\web file
//            $this->edit_web_routes();

            $this->warning('If you need, you can edit RedirectIfAuthenticated middleware as last operation');
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
        $forgotPasswordControllerFile = $controllersDir.'\\'.$packageForgotPasswordControllerFileName;
        $this->copy_file($packageForgotPasswordControllerFile, $forgotPasswordControllerFile, $this->word, $this->slug);

        // Edit and copy login controller file
        $packageLoginControllerFileName = 'LoginController.php';
        $packageLoginControllerFile = $packageControllersDir.'\\'.$packageLoginControllerFileName;
        $loginControllerFile = $controllersDir.'\\'.$packageLoginControllerFileName;
        $this->copy_file($packageLoginControllerFile, $loginControllerFile, $this->word, $this->slug);

        // Edit and copy reset password controller file
        $packageResetPasswordControllerFileName = 'ResetPasswordController.php';
        $packageResetPasswordControllerFile = $packageControllersDir.'\\'.$packageResetPasswordControllerFileName;
        $resetPasswordControllerFile = $controllersDir.'\\'.$packageResetPasswordControllerFileName;
        $this->copy_file($packageResetPasswordControllerFile, $resetPasswordControllerFile, $this->word, $this->slug);

        // Edit and copy role controller file
        $packageRoleControllerFileName = $this->word.'Controller.php';
        $packageRoleControllerFile = $packageControllersDir.'\\'.$packageRoleControllerFileName;
        $roleControllerFile = $controllersDir.'\\'.str_replace($this->word, $this->name, $packageRoleControllerFileName);
        $this->copy_file($packageRoleControllerFile, $roleControllerFile, $this->word, $this->slug);

        if($this->option('r') == 'y'){
            // Edit and copy register controller file
            $packageRegisterControllerFileName = 'RegisterController.php';
            $packageRegisterControllerFile = $packageControllersDir.'\\'.$packageRegisterControllerFileName;
            $registerControllerFile = $controllersDir.'\\'.$packageRegisterControllerFileName;
            $this->copy_file($packageRegisterControllerFile, $registerControllerFile, $this->word, $this->slug);
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

        // Edit and copy the package's repository files
        $viewsDir = resource_path('views');
        $viewsNameDir = $viewsDir.'\\'.$this->slug;
        $viewsNamePasswordsDir = $viewsNameDir.'\\passwords';
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

        // Edit and copy the package's model
        $packageModelDir = $this->baseDir.'\\Model';

        // Edit and copy model file
        $packageRoleModelFileName = $this->word.'.php';
        $packageRoleModelFile = $packageModelDir.'\\'.$packageRoleModelFileName;
        $roleModelFile = app_path(str_replace($this->word, $this->name, $packageRoleModelFileName));
        $this->copy_file($packageRoleModelFile, $roleModelFile, $this->word, $this->name);
        $this->copy_file($roleModelFile, $roleModelFile, 'GUARD', $this->slug);

        // Show a confirmation message
        $this->info('> \''.$this->name.'\' model created!');
        $this->line('');
    }

    public function edit_auth_config()
    {
        $this->line('- Editing config\\auth...');

        // Add the guards
        $authGuard = [];
        $authGuard[$this->slug] = [];
        $authGuard[$this->slug]['driver'] = "'session'";
        $authGuard[$this->slug]['provider'] = "'".str_slug($this->plural)."'";
        $authGuard[$this->slug.'-api'] = [];
        $authGuard[$this->slug.'-api']['driver'] = 'token';
        $authGuard[$this->slug.'-api']['provider'] = "'".str_slug($this->plural)."'";
        config(['auth.guards' => config('app.guards') + $authGuard]);

        // Add the provider
        $authProvider = [];
        $authProvider[str_slug($this->plural)] = [];
        $authProvider[str_slug($this->plural)]['driver'] = 'eloquent';
        $authProvider[str_slug($this->plural)]['model'] = "App\\".$this->name."::class";
        config(['auth.providers' => config('app.providers') + $authProvider]);

        // Show a confirmation message
        $this->info('> config\\auth edited!');
        $this->line('');
    }

    /**
     * Edit and copy auth files.
     *
     * @param $sourcePath
     * @param $destPath
     * @param $search
     * @param $replace
     * @return void
     */
    private function copy_file($sourcePath, $destPath, $search, $replace)
    {
        // Read source file
        $sourceFileContent = file_get_contents($sourcePath);

        // Replace all occurrences of the word in the file with the replace string
        $destFileContent = str_replace($search, $replace, $sourceFileContent);

        // Write destination file
        file_put_contents($destPath, $destFileContent);
    }
}
