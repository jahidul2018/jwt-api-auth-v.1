<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Cubet Techno Labs](https://cubettech.com)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[Many](https://www.many.co.uk)**
-   **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
-   **[DevSquad](https://devsquad.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[OP.GG](https://op.gg)**
-   **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
-   **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## application setup

## Step 1: Install Laravel 10

composer create-project laravel/laravel example-app

## Step 1.1: Install The Package

composer require php-open-source-saver/jwt-auth

## Step 2: Publish The Config File

php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"

## Step 3: Generate Secret Key

php artisan jwt:secret

## Step 4: Configure config/auth.php

Since we only use the Laravel app for API only, we can change the authentication default to api

'defaults' => [
'guard' => 'api', // change this to api
'passwords' => 'users',
],

Next, add the api we already defined at the top to the guards

'guards' => [
'web' => [
'driver' => 'session',
'provider' => 'users',
],

    // add below code. Here we insert additional guards named api, which the driver is jwt and the provider is users (the users provider already defined below this code in the original file, pointing to User model)
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],

],

## Step 5: Add Blacklist Exception

Add this to our .env so we can logout our users.

    JWT_SHOW_BLACKLIST_EXCEPTION=true

# Ok that’s it for the PHP-Open-Source-Saver/jwt-auth installation and it’s configuration.

## Configure the .env file

Point your Laravel app to the correct database. If you set the DB_DATABASE to laravel_api_jwt then don’t forget to create a MySQL database named laravel_api_jwt as well.

    DB_DATABASE=laravel_api_jwt

## Migrate the Database Migration

No need to edit the default migration if the users table migration already contained email, password, and email. Use the following command to migrate:

-> php artisan migrate

## Preparing User Model

    <?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; // add this

class User extends Authenticatable implements JWTSubject // implement the JWTSubject
{
use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // add two methods below

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}

Make sure you implement the JWTSubject and add the two methods from JWT Auth.

## Preparing Controllers

We will create examples of auth-needed and no-auth-needed endpoints.

## Step 1: Create the Auth Controller

We will need to generate the controller with the following command.

    php artisan make:controller Api/AuthController

You will see it generated inside app/Http/Controllers/Api folder. Once it’s created let’s fill it with the following codes.

## Step 2: Create the User Controller

Generate the controller with the following command.

    php artisan make:controller Api/UserController

This controller will only contain a single method me to retrieve authenticated user data. Insert the following codes in the UserController located in the app\Http\Controllers\Api folder.

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;  // add the User model

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function me() 
    {
        // use auth()->user() to get authenticated user data

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User fetched successfully!',
            ],
            'data' => [
                'user' => auth()->user(),
            ],
        ]);
    }
}

## Configure Routing
    Register all the action to our API route in routes/api.php

        <?php

        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Route;
        use App\Http\Controllers\Api\UserController;
        use App\Http\Controllers\Api\AuthController;

        /*
        |--------------------------------------------------------------------------
        | API Routes
        |--------------------------------------------------------------------------
        |
        | Here is where you can register API routes for your application. These
        | routes are loaded by the RouteServiceProvider within a group which
        | is assigned the "api" middleware group. Enjoy building your API!
        |
        */

        // Public accessible API
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        // Authenticated only API
        // We use auth api here as a middleware so only authenticated user who can access the endpoint
        // We use group so we can apply middleware auth api to all the routes within the group
        Route::middleware('auth:api')->group(function() {
            Route::get('/me', [UserController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    // end 

That’s it, next we can test the API.

## Serve
We can run the API using artisan serve

php artisan serve

Now our API can be accessed at the 127.0.0.1:8000/api.

## Test the API

useing postman application for the Api testing.
