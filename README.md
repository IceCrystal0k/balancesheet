## About Balance Sheet Project

Balance SHeet is a web application used to manage both the monthly balance and daily balance of a house. It is based on Laravel framework v. 8.54 and metronic template v. 8,
Modules that it contains:

-   Dashboard :
-   Account

    -   Profile : profile preview
    -   Settings : profile update / account delete

-   Administration

    -   Users

-   Balance sheet
    -   Targets
    -   Monthly Balance
    -   Daily Balance
    -   Statistics

## Requiremens

-   [Download and install composer](https://getcomposer.org/download/)
-   [Download and install node js, a version equal or bigger to v14.15.5](https://nodejs.org/en/download/releases/)

## Installation

-   Create a database with watever name
-   Edit .env file and set the database connection and MAIL connection

Run the following commands in console (in the folder where the app is installed):

-   **`composer install`** (install php modules defined in composer.json)
-   **`npm install`** (install node modules defined in package.json)
-   **`php artisan migrate`** (creates the tables structure)
-   **`npm run dev`** (compile and copy all resource files to public folder)
-   **`php artisan storage:link`** (create a link between folder /storage and /public/storage)
-   **`php artisan optimize`** (clear laravel cache)

## About Laravel

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

Customizing Registration
The user validation and creation process may be customized by modifying the App\Actions\Fortify\CreateNewUser action that was generated when you installed Laravel Fortify.

## Metronic

Admin Dashboard Theme

[Main site](https://keenthemes.com/metronic/)

## Used libraries & info

-   **[CodeCheef - roles and permission](https://www.codecheef.org/article/user-roles-and-permissions-tutorial-in-laravel-without-packages)**
-   **[Alemoh - laravel 8 auth with fortify.](https://alemsbaja.hashnode.dev/complete-laravel-8-authentication-using-laravel-fortify-and-bootstrap-4-part-1)**
-   **[Alemoh - laravel 8 auth with fortify - github.](https://github.com/RaphAlemoh/laravel8_fortify_with_bootstrap)**
-   **[Intervation image](https://image.intervention.io/v2)**
-   **[Laravel socialite](https://laravel.com/docs/9.x/socialite)**
-   **[Laravel sanctum](https://laravel.com/docs/9.x/sanctum)**
-   **[Laravel dompdf](https://github.com/barryvdh/laravel-dompdf)**
-   **[Laravel breadcrumbs](https://packagist.org/packages/diglactic/laravel-breadcrumbs)**
-   **[Laravel datatables](https://github.com/yajra/laravel-datatables)**
-   **[Laravel DebugBar](https://github.com/barryvdh/laravel-debugbar)**
