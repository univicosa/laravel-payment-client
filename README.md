# Univiçosa Laravel Payment Client

`univicosa/laravel-payment-client` is a Laravel package which created to integrate the Payment server to ours Laravel project's that requires payments requests.

## Install

Installation using composer:

```
composer require univicosa/laravel-payment-client
```

For Laravel versions < 5.5 add the service provider in `config/app.php`:

```
Modules\OpenId\Providers\OpenIdServiceProvider::class
```

Define the system in your `.env` setting the enviroiment variables: 

`SYSTEM_ID` generated by Admin server for your system
`SYSTEM_PASSWORD` generated by Admin server for your system
`PAYMENT_SERVER` defined according to enviroiment you are using

To personalize the config, publish the package's configuration file by running:

```
php artisan vendor:publish --tag=payment
```

The file `config/openid.php` will be generated.

**PS:** Your system need to be authorized by the Payment Administration Service and uses the OpenId Client to authenticate the users that request the payments.

## Creating a new account

For do payments requests the client need to use a valid Beneficiary. For create one on demand just implement the `Payment::createBeneficiary()` method following the rules:

**Request body**

```json
{
  "name" : "bail|required|string|min:5",
  "system" : "bail|required|string|size:24",
  "account" : "bail|required|string",
  "valid_until" : "bail|required|date|after:now"
}
```

**Content explanation**

```php
'name'        => 'The name that describes your beneficiary in the report list',
'system'      => 'The id that the Payment server admin provides to you',
'account'     => 'The bank account that Payment server admin provides to you',
'valid_until' => 'The final date your account will accepts payments requests'
```

## Payment requests

To request a payment to the server via API client you should instantiate one of the avaliable types and pass to the driver via `\Payment::send()` Facade. The driver will identify the instance of your payment and send to the appropriated endpiont.

The request accepts N items by request, turning possible a client use a cart of items in one unique payment instance.

The Payment request should follow the rules for all Payment instances. All types available will extends:

**Request body**

```json
{
  "payer": {
    "name" : "bail|required|string|min:3",
    "email" : "bail|required|string|min:5",
    "address" : "bail|required|string|min:3",
    "district" : "bail|required|string|min:2",
    "number" : "bail|required|string|min:1",
    "cep" : "bail|required|string|min:8|max:9|cep",
    "state" : "bail|required|string|min:2",
    "city" : "bail|required|string|min:3",
    "cpf" : "bail|required|string|size:11|cpf"
  },
  "value" : "bail|required|numeric|min:0",
  "operator" : "bail|required|string",
  "items" : [
      {
        "name" : "bail|required|string|min:3",
        "amount" : "bail|required|numeric|min:0",
        "discount_amount" : "bail|required|numeric|min:0",
        "final_value" : "bail|required|numeric|min:0",
        "discounts" : [
          {
            "*" : "bail|filled|array|min:1"
          }
        ],
        "beneficiary" : "bail|required|string|size:24",
        "details" : [
          {
            "item" : "bail|required|string|min:3",
            "value" : "bail|required|numeric|min:0"
          }  
        ]
      }
    ]
}
```

**Content explanation**

```php
'operator' => 'the financier operator of your transaction. EX: SICOOB, Cielo'
```

## Payment types availiable

All the following types will extends the default rules of Payment class.

### Boleto

All current payments via boleto accepts only the SICOOB operator and the return methods are operated manually. The notification of payment are maded after that and can wait 72 hour for the bank return.

**Request body**

```json
{
  "descriptions" : [
    {
      "description" : "bail|required|string|min:5"
    }
  ],
  "deadline" : "bail|required|integer|min:0"
}
```

**Content explanation**

```php
'descriptions' => 'add the descriptions to boleto's document body, max 4'
```

**PS:** if the deadline recognizes the final date as a not util day, the end date will be the next monday.

### Credit card

All current payments via credit card accpts the brands Visa, Mastercard, American Express, ELO, Diners and Amex.

**Request body**

```json
{
  "credit_card" : {
    "token" : "bail|required"
  },
  "installments" : "bail|required|integer|min:1|max:6"
}
```

### Free

The free payment is a instance that receives isents requests generated with 100% discount (vouchers included) and just extends the default rules and need to receive the 'value' key as a zero.

**Content explanation**

```php
'value' => 'bail|required|numeric|min:0|max:0'
```

### Presential

The presential payment are maided by authorized users under a admin panel and accepts payment in cash (type => 'money') or via card machine (type => 'credit_card' and type => 'debit_card') and excluded the following parent rules: 'payer.address', 'payer.district', 'payer.cep', 'payer.state' and 'payer.city'.

**Request body**

```json
{
  "presential" : {
    "type" : "bail|required|string",
    "installments" : "bail|required|integer|min:1",
    "token" : "bail|required",
    "responsible" : {
      "name" : "bail|required|string|min:3",
      "email" : "bail|required|string|email",
      "cpf" : "bail|required|string|size:11|cpf"
    }
  }
}
```

## Payment status

Each payment type has a especific line of status updates, but their all will respects the following rules:

Payments payed or scheduled receive a 'payed' status.
Payments canceled, refunded or closed receive a 'canceled' status.
Payments aborted or denied receive a 'denied' status.
All the other possibles status are translated to a 'waiting' status.

The status 'payed', 'canceled' and 'denied' are endpoints and not receives new notifications after updated.

## Payment push notification

The driver implements a route `/api/payment` that receives the push notifications with the change of payments status from the Payment server. The notification contains the payment_id and the updated status.

The method and controller that will treat the return need to be defined in the published `config/payment.php` file.

## Payment refund

It's possible to refund a payment using the Facade `\Payment::cancel()`. The method requires the payment ID as a parameter and will call the cancel process in the Payment server.

Payments maid by credit card will generate a refund directly in the Invoice of the client. 

Payments made by boleto and paid will generate a refund in name of the payer and the process need to be treated internally, by the financial operator, according of the internal rules of IES.

## _Facades_

```php
@method \Payment::createBeneficiary(Beneficiary $beneficiary): array
@api POST '/api/{version}/beneficiary'

@return array with the response of Post action
```
```php
@method \Payment::send(\JsonSerializable $payment): array
@api POST '/api/{version}/{paymentType}'

@return array with the response of Post action
```

```php
@method \Payment::getPayer(): array
@api GET '/api/{version}/user'

@return array with data of loged user
```

```php
@method \Payment::cancel(string $type, string $id): array
@api DELETE '/api/{version}/{PaymentType}/{paymentId}'

@return array with the response of Delete action
```

```php
@method \Payment::cancelItem(string $type, string $id, array $data, bool $cancel): array
@api DELETE '/api/{version}/{PaymentType}/item/{paymentId}'

@return array with the response of Delete action
```