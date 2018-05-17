# Univiçosa Laravel Payment Client

| **Laravel**  |  **laravel-payment-client** |
|------|------|
| 5.4  | ^v1.0.0  |
| 5.5  | ^v1.0.0  |
| 5.6  | ^v1.0.0  |

## Install

Installation using composer:

```
composer require univicosa/laravel-payment-client
```

And add the service provider in `config/app.php`:

```
Modules\OpenId\Providers\OpenIdServiceProvider::class
```

Publish the package's configuration file by running:

```
php artisan vendor:publish --tag=payment
```

The file `config/openid.php` will be generated.

Define the system in your `.env` setting the var `PAYMENT_SERVER`

**PS:** Your system need to be authorized by the Payment Administration Service