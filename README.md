## Laravel Real-Time Notifications

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

Today, many tasks happen on the backend, and we sometimes need to inform the user about it right away. So there is a use-case for triggering an action from the server instead of the client. Think of messaging in a chat or notification messages that pop up on the top of your dashboard.

To achieve this, our client could ask the server every second if something new happened, or you could make use of long polling. But the best solution is to create a new communication channel through WebSockets which works in both ways.

## Installation Process
   We start by creating a new Laravel 8, 9 application. I always recommend using the Laravel Installer for this purpose.
   laravel new laravel-real-time-notifications
   
   

## Step-01: Installation Laravel Websockets
   composer require beyondcode/laravel-websockets
   
   
 
## Step-02: We also need a package by Pusher.
   composer require pusher/pusher-php-server
   
   
   
## Step-03: Next, adapt your .env file. We want the BROADCAST_DRIVER to be pusher.

   BROADCAST_DRIVER=pusher
   
   
  
## Step-04: And we need to set the Pusher credentials.

   PUSHER_APP_ID=12345
   PUSHER_APP_KEY=12345
   PUSHER_APP_SECRET=12345
   PUSHER_APP_CLUSTER=mt1
   
   

## Step-05: The Laravel Websockets package comes with a migration file for storing statistics and a config file we need to adapt. Let's publish them.

   php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
   
   
  
## Step-06: DB credentials in the .env file. Afterward, we can run the migration.

   php artisan migrate
   
   
 
## Step-07: We publish the config file of Laravel Websockets.

   php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
   
   
   
## Step-08: Now we are ready to start the WebSockets server.

   php artisan websockets:serve
   
To test that it is running, we can check the debugging dashboard under the endpoint /laravel-websockets. You can click connect to see if the dashboard can connect to the WebSockets server.

After clicking connect, you should see that the dashboard is subscribed to some debugging channels like private-websockets-dashboard-api-message. This will tell you that the server is set up correctly.

Broadcast Notifications From Our Laravel Application.
There are two ways we can send messages from our backend to the WebSockets server:

Laravel Events
Laravel Notifications



## Step-09: Let's create a new event with artisan.

   php artisan make:event RealTimeMessage
   
   
 
## Step-10: Here is what we need to change:

    <?php
    namespace App\Events;

    use Illuminate\Broadcasting\Channel;
    use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
    use Illuminate\Queue\SerializesModels;

    class RealTimeMessage implements ShouldBroadcast
    {
        use SerializesModels;

        public string $message;

        public function __construct(string $message)
        {
            $this->message = $message;
        }

        public function broadcastOn(): Channel
        {
            return new Channel('events');
        }
    }
    
    
    
 ## Step-11: Before we can try sending this event, please adapt your broadcasting.php config file to use the following options:

    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => false,
        'host' => '127.0.0.1',
        'port' => 6001,
        'scheme' => 'http'
    ],
    
    
    
 ## Step-12: You could also create a route and run the command there.

    event(new App\Events\RealTimeMessage('Hello World'));
    
    
    
 ## Step-13: Listen To Messages From Our Front-end
    npm install --save-dev laravel-echo pusher-js
    
    
    
 ## Step-14: resouces/js/bootstrap.js file of Laravel already contains a code snippet for creating a new instance of Laravel Echo we can use.
 
    import Echo from 'laravel-echo';

    window.Pusher = require('pusher-js');

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTLS: false,
        wsHost: window.location.hostname,
        wsPort: 6001,
    });
    
    
    
 ## Step-15: Now import the script. We are going to use Laravel's welcome view for our tutorial. So add the app.js file into your view
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        Echo.channel('events')
            .listen('RealTimeMessage', (e) => console.log('RealTimeMessage: ' + e.message));
    </script>




## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
 

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
