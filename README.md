# **TeamAuth**

---

A simple multi-auth package for Laravel 5.*

# **Table of Contents**

- [Installation](#installation)
- [Basic usage](#basic-usage)
- [Options](#options)
	- [--r](#registration)
	- [--controllers](#controllers)
	- [--views](#views)
	- [--model](#model)
	- [--config-auth](#config-auth)
	- [--migrations](#migrations)
	- [--m](#migrate)
	- [--seeder](#seeder)
	- [--s](#db-seed)
	- [--routes-web](#routes-web)
- [Usage examples](#usage-examples)
- [Contributing](#contributing)
- [License](#license)


## **Installation**

```sh
composer require alextigaer/team-auth
```

## **Basic usage**

To create a new role, just run the command:

```sh
php artisan team-auth:create RoleName
```

This command will:
- Create 5 controllers inside the App\Http\Controllers\RoleName directory:
    - ForgotPasswordController
    - LoginController
    - RegisterController
    - ResetPasswordController
    - RoleNameController
- Create 5 (+ 1 if the layouts\app.blade.php file is missing) views inside the resources\views\role_name directory:
    - passwords\email.blade.php
    - passwords\reset.blade.php
    - dashboard.blade.php
    - login.blade.php
    - register.blade.php
- Create RoleName's model inside models' directory
- Edit config\auth.php file to add the guard, the provider and the password resets for the role
- Create 2 migrations:
    - create_role_names_table
    - create_role_names_password_resets_table
- Create RoleNameSeeder
- Edit routes\web.php file to add the routes for the role

## **Options**

If you want, you can use these options to customize the creation:

### --r (default=y) <a name="registration"></a>

This option allows to choose if the users will be able to register to this role. Choosing the 'n' value, the 
RegisterController file and the register blade file won't be created and the register routes won't be added to the
routes\web file.

Possible values: y (default), n

Description:
```sh
{--r=y : Choose if users can register to this role, or not [y/n]}
```

Usage:
```sh
// Create the role excluding the possibility to register
php artisan team-auth:create RoleName --r=n
```

### --controllers (default=y) <a name="controllers"></a>

This option allows to choose if the controllers should be created.

Possible values: y (default), n

Description:
```sh
{--controllers=y : Choose whether to create controllers, or not [y/n]}
```

Usage:
```sh
// Create the role without creating the controllers
php artisan team-auth:create RoleName --controllers=n
```

### --views (default=y) <a name="views"></a>

This option allows to choose if the views should be created.

Possible values: y (default), n

Description:
```sh
{--views=y : Choose whether to create views, or not [y/n]}
```

Usage:
```sh
// Create the role without creating the views
php artisan team-auth:create RoleName --views=n
```

### --model (default=y) <a name="model"></a>

This option allows to choose if the model should be created.

Possible values: y (default), n

Description:
```sh
{--model=y : Choose whether to create model, or not [y/n]}
```

Usage:
```sh
// Create the role without creating the model
php artisan team-auth:create RoleName --model=n
```

### --config-auth (default=y) <a name="config-auth"></a>

This option allows to choose if the config\auth file should be edited.

Possible values: y (default), n

Description:
```sh
{--config-auth=y : Choose whether to edit config\auth file, or not [y/n]}
```

Usage:
```sh
// Create the role without editing the config\auth file
php artisan team-auth:create RoleName --config-auth=n
```

### --migrations (default=y) <a name="migrations"></a>

This option allows to choose if the migrations should be created.

Possible values: y (default), n

Description:
```sh
{--migrations=y : Choose whether to create migrations, or not [y/n]}
```

Usage:
```sh
// Create the role without creating the migrations
php artisan team-auth:create RoleName --migrations=n
```

### --m (default=n) <a name="migrate"></a>

This option allows to choose if the migrate should be run.

Possible values: y, n (default)

Description:
```sh
{--m=y : Choose whether to run the command migrate, or not [y/n]}
```

Usage:
```sh
// Create the role running the migrate command
php artisan team-auth:create RoleName --m=y
```

### --seeder (default=y) <a name="seeder"></a>

This option allows to choose if the seeder should be created.

Possible values: y (default), n

Description:
```sh
{--seeder=y : Choose whether to create seeder, or not [y/n]}
```

Usage:
```sh
// Create the role without creating the seeder
php artisan team-auth:create RoleName --seeder=n
```

### --s (default=n) <a name="db-seed"></a>

This option allows to choose if the db:seed should be run.

Possible values: y, n (default)

Description:
```sh
{--s=y : Choose whether to run the command db:seed, or not [y/n]}
```

Usage:
```sh
// Create the role running the db:seed command
php artisan team-auth:create RoleName --s=y
```

### --routes-web (default=y) <a name="routes-web"></a>

This option allows to choose if the routes\web file should be edited.

Possible values: y (default), n

Description:
```sh
{--routes-web=y : Choose whether to edit routes\web file, or not [y/n]}
```

Usage:
```sh
// Create the role without editing the routes\web file
php artisan team-auth:create RoleName --routes-web=n
```

## **Usage examples**

1) Create a role called 'Admin' without creating the seeder, but running the migrations:
```sh
php artisan team-auth:create Admin --m=y --seeder=n
```

2) Create a role called 'Mod' preventing the users to register to this role:
```sh
php artisan team-auth:create Mod --r=n
```

3) Create a role called 'SuperUser' creating only the model:
```sh
php artisan team-auth:create SuperUser --controllers=n --views=n --config-auth=n --migrations=n --seeder=n --routes-web=n
```

## **Contributing**

Feel free to suggest anything! Use pulls or contact me :)

## **License**

TeamAuth is licensed under the MIT license. Made with love, let's share it! :)
