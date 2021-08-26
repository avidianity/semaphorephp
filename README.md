# SemaphorePHP

A wrapper for accessing the Semaphore SMS API

Note: This only works on nodejs, using this on the browser will not work.

## Table of Contents

- [SemaphorePHP](#semaphorephp)
  - [Table of Contents](#table-of-contents)
  - [Installation](#installation)
  - [Basic Usage](#basic-usage)
  - [License](#license)

## Installation

```sh
composer require avidian/semaphore
```

## Basic Usage

Sending Messages

```php

use Avidian\Semaphore\Client;

$client = new Client('your api key', [/* options */]);

$response = $client->send('09991234567', 'your message');

// multiple recipients
$recipients = ['09991234567', '09997654321'];
$response = $client->send($recipients, 'your message');
```

```json
[
  {
    "message_id": 1234567,
    "user_id": 99556,
    "user": "user@your.org",
    "account_id": 90290,
    "account": "Your Account Name",
    "recipient": "09991234567",
    "message": "The message you sent",
    "sender_name": "SEMAPHORE",
    "network": "Globe",
    "status": "Queued",
    "type": "Single",
    "source": "Api",
    "created_at": "2016-01-01 00:01:01",
    "updated_at": "2016-01-01 00:01:01"
  }
]
```

Retrieving Messages

```php
$response = $client->messages([ 'limit' => 100, 'page' => 1 ]);
```

Supported filters for retrieving messages

```php
$options = [
    'limit' => 100,
    'page' => 1,
    'sendername' => 'SEMAPHORE',
    'startDate' => '2016-01-01',
    'endDate' => '2016-02-01',
    'network' => 'globe',
    'status' => 'success',
];
```

Retrieving account information

```php
$response = $client->account();
```

```json
{
  "account_id": 12345,
  "account_name": "Your Organization",
  "status": "Active",
  "credit_balance": 5000
}
```

Retrieve users

```javascript
$response = $client->users();
```

```json
[
  {
    "user_id": 12345,
    "email": "owner@your.org",
    "role": "Owner"
  },
  {
    "user_id": 54321,
    "email": "someguy@your.org",
    "role": "User"
  }
]
```

Sender names

```php
$response = $client->senderNames();
```

```json
[
  {
    "name":"Semaphore",
    "status":"Active",
    "created":"2016-01-01 00:00:01"
  },
  {
    "name":"Kickstart",
    "status":"Active",
    "created":"2016-01-01 00:00:01""
  }
]
```

Transactions

```php
$response = $client->transactions();
````

## License

This library is open-sourced software licensed under the [MIT license](LICENSE.md).
