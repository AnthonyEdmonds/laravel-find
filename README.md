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
        'users' => \App\Models\User::class,
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

Each Model type to be found should be keyed with a plural label, with the Model class as the value.

## Usage

This library is split into two parts, a `Findable` trait which is used on the Models to be found, and a `Find` static class which is used to get results.

### Findable

Each Model listed in the `models` config key must use the `Findable` trait. This will add a number of abstract static methods which must be implemented.

```php
class Author extends Model
{
    use Findable;

    public static function findDisplayLabel(): string
    {
        return 'Authors by name and book title';
    }

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

#### findDisplayLabel

The display label for the Model type, such as "Authors by name and book title".

Used primarily for generally identifying the Model, preferably with context around which terms users can find by.

#### canBeFoundBy

Used to determine whether the current user (or null, if not signed in) can find a type of Model.

This logic will show or hide the entire Model type based on a `true` or `false` return.

The Find helper will throw an `AuthorizationException` if a user attempts to find a Model they are not allowed to. 

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

You may utilise the `$user` parameter to further refine the search to the current user.

### Find

Once the `Findable` trait has been implemented on each Model you may use the `Find` helper to get results.

Most implementations will need to provide two pages to end users:

* Start
* Results

The start page should provide a text-box for the find term, and a drop-down to select which type of thing to find.

The results page should show a list of whatever was found.

#### Find::find

Calling `find($term, $type)` will construct and return a QueryBuilder statement which is ready to be executed.

* `$term` is the value to use during the find, which is passed into each Model's `find()` method
* `$type` is the key for the specific Model type in the `models` config, or the `anything-key` to find across all allowed Models

As it is a standard query, you may extend the query as required, such as adding an `order()`.

Run the query using whichever QueryBuilder method you require, such as `paginate()` or `get()`.

```php
$query = Find::find('my-term', 'users');
$query->orderBy('users.name');
$query->paginate(10);
```

This will return a standard `Collection` object containing the results.

Each result will have a `label`, `description`, and `link` attribute.

#### Find::types

Calling `types()` will return a list of Models which the current user can find.

This list can be used to populate a dropdown when starting a find to narrow the types of Models to locate, and identify the Models that the user is allowed to find.

The returned array is keyed using the keys from the config `models`.

By default, the value is each Model's `findDisplayLabel()`. Passing `true` to the method will instead return the Model class. 

### FindRequest

A Laravel FormRequest is provided for convenience called `FindRequest`.

Simple use it on the controller methods for handling searches:

```php
public function results(FindRequest $request): View
{
    // Show results...
}
```

## Roadmap

* Routes macro?
