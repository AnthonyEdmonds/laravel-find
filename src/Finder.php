<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

abstract class Finder
{
    /**
     * Default constants
     * --------------
     * Set the default value for each find part
     */

    public const string DEFAULT_FILTER = 'all';

    public const string DEFAULT_STATUS = 'all';

    public const string DEFAULT_SORT = 'newest';

    /**
     * Key constants
     * ----------
     * Customise the session keys for each find part
     */

    public const string KEY_FILTER = 'filter';

    public const string KEY_SEARCH = 'search';

    public const string KEY_SORT = 'sort';

    public const string KEY_STATUS = 'status';

    /**
     * Properties
     * ----------
     * The loaded find parts and authenticated model
     */

    public string $currentFilter = '';

    public string $currentSearch = '';

    public string $currentSort = '';

    public string $currentStatus = '';

    public ?Authenticatable $user = null;

    /** Loads the current find parts and any authenticated model */
    final public function __construct()
    {
        $this->validate();

        $this->user = Auth::user();
        $this->currentFilter = static::loadTerm(static::KEY_FILTER, static::DEFAULT_FILTER);
        $this->currentSearch = static::loadTerm(static::KEY_SEARCH, '');
        $this->currentSort = static::loadTerm(static::KEY_SORT, static::DEFAULT_SORT);
        $this->currentStatus = static::loadTerm(static::KEY_STATUS, static::DEFAULT_STATUS);
    }

    /** Retrieves the current term from the request, session, or default */
    public function loadTerm(string $key, string $default): string
    {
        $value = Request::has($key) === true
            ? Request::get($key) ?? $default
            : Session::get($key) ?? $default;

        Session::put($key, $value);

        return $value;
    }

    /**
     * Validation
     * ----------
     * Make sure people are only using the filters, searches, and sorts they should be
     */

    /**
     * Specify the FormRequest to use for validating Finder inputs
     * @returns class-string<FormRequest>
     */
    public function formRequest(): string
    {
        return FinderFormRequest::class;
    }

    /** Validate request inputs */
    public function validate(): void
    {
        $formRequestClass = $this->formRequest();
        $formRequest = new $formRequestClass([
            static::KEY_FILTER => request()->input(static::KEY_FILTER),
            static::KEY_SEARCH => request()->input(static::KEY_SEARCH),
            static::KEY_SORT => request()->input(static::KEY_SORT),
            static::KEY_STATUS => request()->input(static::KEY_STATUS),
        ]);
        $formRequest->finder = $this;

        $formRequest->validate(
            $formRequest->rules(),
        );
    }

    /**
     * Finding
     * -------
     * The core of how you use your Finder class
     * Add `YourFinder` to the `with` parameter of your call to `view`
     * view('my-view')->with('finder', YourFinder::find())
     * Pull the results into a table, then use the provided search bar template or make your own
     * <x-your-table :caption="$finder->caption" :data="$finder->results" />
     * <x-laravel-finder::search-bar :finder="$finder" />
     */

    /** Provides the results and everything a blade needs to use a Finder */
    public static function find(): FinderOutput
    {
        $finder = new static();

        return new FinderOutput(
            $finder->caption(),
            $finder->search(),
            $finder->currentFilter,
            $finder->currentSearch,
            $finder->currentSort,
            $finder->currentStatus,
            $finder->makeFilterItems(),
            $finder->listSearchable(),
            $finder->makeSortItems(),
            $finder->makeStatusItems(),
            $finder->makeLink(null, null, null, null),
            new FinderLink(
                $finder->clearLabel(),
                $finder->clearLink(),
            ),
        );
    }

    /** Find some models utilising the current search, filter, status, and order */
    abstract public function search(): ResourceCollection;

    /**
     * Table captioning
     * ----------------
     * These methods allow you to create a user-friendly description of the current search, filters, and sorting
     * Each method should return a string, such as "by newest" or "open"
     * The strings are then combined using placeholders on the filter label, for example:
     * "New ~status requests ~sort" which might become "New open requests by newest"
     */

    /** Provide a filter description which can include the available placeholders */
    abstract public function filterLabel(string $currentFilter): string;

    /** A description of the current status filter */
    abstract public function statusLabel(string $currentStatus): string;

    /** A description of the current sort order */
    abstract public function sortLabel(string $currentSort): string;

    /** Generates a human friendly caption for the table of results */
    public function caption(): string
    {
        return Str::of(
            $this->filterLabel($this->currentFilter),
        )
            ->replace(
                [
                    '~sort',
                    '~status',
                ],
                [
                    $this->sortLabel($this->currentSort),
                    $this->statusLabel($this->currentStatus),
                ],
            )
            ->ucfirst();
    }

    /**
     * Lists
     * -----
     * These methods provide the labels for each filter, status, and sort on the UI
     */

    /** Provide a list of filter options in $key => $label format */
    abstract public function listFilters(): array;

    /** Provide a list of searchable columns in $label format */
    abstract public function listSearchable(): array;

    /** Provide a list of sort options in $key => $label format */
    abstract public function listSorts(): array;

    /** Provide a list of status options in $key => $label format */
    abstract public function listStatuses(): array;

    /**
     * Clear
     * -----
     * These methods customise the "clear filters" button
     */

    public function clearLabel(): string
    {
        return 'Clear all applied filters, searches, and sorts';
    }

    public function clearLink(): string
    {
        return $this->makeLink(
            static::DEFAULT_FILTER,
            static::DEFAULT_STATUS,
            static::DEFAULT_SORT,
            '',
        );
    }

    /**
     * Items
     * -----
     * These methods create link items for use on the UI
     */

    /** The name of the route which this Finder links to, such as "orders.index" */
    abstract public function route(): string;

    /** Makes a FindLink object based on the current filter */
    protected function makeFilterItems(): array
    {
        $filters = $this->listFilters();
        $items = [];

        foreach ($filters as $key => $label) {
            if ($key === $this->currentFilter) {
                continue;
            }

            $items[] = new FinderLink(
                $label,
                $this->makeLink($key, null, null, null),
            );
        }

        return $items;
    }

    /** Makes a FindLink object based on the current sort */
    protected function makeSortItems(): array
    {
        $sorts = $this->listSorts();
        $items = [];

        foreach ($sorts as $key => $label) {
            if ($key === $this->currentSort) {
                continue;
            }

            $items[] = new FinderLink(
                $label,
                $this->makeLink(null, null, $key, null),
            );
        }

        return $items;
    }

    /** Makes a FindLink object based on the current status */
    protected function makeStatusItems(): array
    {
        $states = $this->listStatuses();
        $items = [];

        foreach ($states as $key => $label) {
            if ($key === $this->currentStatus) {
                continue;
            }

            $items[] = new FinderLink(
                $label,
                $this->makeLink(null, $key, null, null),
            );
        }

        return $items;
    }

    /** Makes a link to apply the selected filter, status, or sort change */
    protected function makeLink(
        ?string $filter,
        ?string $status,
        ?string $sort,
        ?string $search,
    ): string {
        return URL::route(
            $this->route(),
            [
                static::KEY_FILTER => $filter ?? $this->currentFilter,
                static::KEY_SEARCH => $search ?? $this->currentSearch,
                static::KEY_SORT => $sort ?? $this->currentSort,
                static::KEY_STATUS => $status ?? $this->currentStatus,
            ],
        );
    }
}
