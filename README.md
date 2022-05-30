# laravel-find
Find models within your Laravel system with minimal overhead

* Laravel 9
* PHP 8

## Installation

Add this library using `composer require anthonyedmonds/laravel-find`.

The service provider will be automatically registered.

Export the config file using `php artisan vendor:publish --provider="AnthonyEdmonds\LaravelFind\FindServiceProvider"`

## Configuration

There are a small number of options that need to be configured prior to using the library.

```php
return [
    /* The key and label for searching all models; set as false to disable */
    'anything-key' => 'any',
    'anything-label' => 'Anything',
    
    /* A table that is guaranteed to exist */
    'base-table' => 'users',
    
    /* Which models can be searched for, and its display label */
    'models' => [
        \App\Models\User::class => 'Users by name, e-mail, and role',
    ],
];
```

### anything-key / anything-label

Users are able to find Models across any that they have access to.

The key and label are controlled using these keys.

Set the key to `false` to disable searching across Models.

### base-table

Searching across all models requires a base query upon which all others are joined.

Set this value to the name of a table which is guaranteed to exist in the system, such as the 'users' table.

### models

This list controls which Models can be found using this library.

Provide an array of class names and display labels to be used.

## Usage

This library is split into two parts, a `Findable` trait which is used on the Models to be found, and a `Find` static class which is used to get results.

### Findable

Each Model listed in the `models` config key must use the `Findable` trait. This will add a number of abstract static methods which must be implemented.

```php
class Author extends Model
{
    use Findable;

    public static function canBeFoundBy(?Model $user): bool
    {
        return true;
    }
    
    protected static function findLabel(): string
    {
        return 'name';
    }

    protected static function findDescription(): string
    {
        return '"An author"';
    }

    protected static function findLink(): string
    {
        return route('authors.show', '~id');
    }

    protected static function findFilters(Builder $query, string $term): Builder
    {
        return $query->where('name', 'LIKE', "%$term%");
    }
}
```

#### canBeFoundBy

Used to determine whether the current User (or null, if not signed in) can find a type of Model.

This logic will show or hide the entire Model type based on a `true` or `false` return.

The Find helper will throw an `AuthorizationException` if a User attempts to find a Model they are not allowed to. 

#### findLabel

The primary identifier for the result, which can be:

* A column name, such as 'title'
* A static description, such as '"Book"'
* A sentence with placeholders, such as '~title by ~author'

#### findDescription

A brief description of the result, which can be:

* A column name, such as 'description'
* A static description, such as '"A collection of pages"'
* A sentence with placeholders, such as '~genre ~page_count'

#### findLink

The URL where the result can be found, which can be:

* A column name, such as 'url'
* A static link, such as '"https://my-site.com/users"'
* A sentence with placeholders, such as 'https://my-site.com/books/~book_id/~id'

#### findFilters

The filters to be applied to the search, such as `->where('name', '=', $term)`.

As this library uses Laravel's QueryBuilder, you can use related Models to find results by performing a `leftJoin` with a matching `where` statement:

```php
protected static function findFilters(Builder $query, string $term, ?Model $user = null): Builder
{
    return $query
        ->leftJoin('books', 'books.id', '=', 'chapters.book_id')
        ->where('chapters.title', 'LIKE', "%$term%")
        ->orWhere('books.title', 'LIKE', "%$term%");
}
```

You may utilise the `$user` parameter to further refine the search to the current User.

### Find

Once the `Findable` trait has been implemented on each Model you may use the `Find` helper to get results.

Most implementations will need to provide two pages to end users:

* Start
* Results

The start page should provide a text-box for the find term, and a drop-down to select which type of thing to find.

The results page should show a list of whatever was found.

#### Find::find

Calling `find()` will construct and return a QueryBuilder statement which is ready to be executed.

The first parameter is the term to use during the find, and the second parameter is the Model type to find.

A Model class name should be provided, or the `anything-key` for a wide find.

As it is a standard query, you may extend the query as required, such as adding an `order()`.

Run the query using whichever QueryBuilder method you require, such as `paginate()` or `get()`.

```php
$query = Find::find('my-term', User::class);
$query->orderBy('users.name');
$query->paginate(10);
```

This will return a standard `Collection` object containing the results.

Each result will have a `label`, `description`, and `link` key.

#### Find::types

Calling `types()` will return a list of Models which the current User can find.

This array is keyed by the Model class name with its display label as the value.

This list can be used to populate a dropdown when starting a find to narrow the types of Models to locate.

## Roadmap

* Controller, FormRequest, routes?
