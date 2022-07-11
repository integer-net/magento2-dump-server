# Magento 2 Var-Dumper Server

This module adds  a new command to start a dump-server for use with the symfony var-dumper component to magento 2.

This only works for Http requests to magento. (no cli commands, cronjobs, etc)

## Starting the dump server

The dump server can be started using the command `bin/magento server:dump`.
There is also the alias `bin/magento dump-server` available.

## Configuration
The dump-server can be configured in the env.php:

```php
<?php
return [
    // ...
    'dev_dump_server' => [
        'enabled' => true,
        'address' => '127.0.0.1:9912', 
    ],
```

It is also possible to only specify the address instead of an array:

```php
<?php
return [
    // ...
    'dev_dump_server' => '127.0.0.1:9912',
```

If no configuration is set, the default is `enabled = true` and `address = 127.0.0.1:9912`.
