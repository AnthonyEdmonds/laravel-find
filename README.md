# laravel-find
Find models within your Laravel system with minimal overhead

// Thoughts
ServiceProvider and config file

Config file specifies the models that can be searched and the relevant permission which allows them to be searched

```php
[
    User::class => [
        'label' => 'Users by name, e-mail, and Role',
        'permission' => 'find_users',
    ],
];
```

Find helper then polls that list to establish which options are available for display and validation.

Each model to be searched needs to extend Findable class.
