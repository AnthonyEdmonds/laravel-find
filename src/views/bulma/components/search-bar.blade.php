@props([
    'finder',
])

<div class="mt-4">
    <div class="columns">
        <div class="column is-one-quarter">
            <h3>Quick search</h3>
            <form
                action="{{ $finder->searchLink }}"
                enctype="multipart/form-data"
                method="get"
            >
                @csrf
                @method('get')

                <div class="field">
                    <div class="control">
                        <input
                            class="input"
                            name="search"
                            value="{{ old('search', $finder->currentSearch) }}"
                        />
                    </div>

                    @error('search')
                        <p class="help is-danger">{{ $message }}</p>
                    @enderror

                    <p class="help">You may search by:</p>
                    <ul>
                        @foreach($finder->searchable as $label)
                            <li class="help">{{ $label }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="field">
                    <div class="control">
                        <button class="button is-primary">
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="column is-one-quarter">
            <h3>Filters</h3>
            <ul class="no-decoration">
                @foreach($finder->filters as $item)
                    <li>
                        <a href="{{ $item->link }}">{{ $item->label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="column is-one-quarter">
            <h3>Statuses</h3>
            <ul class="no-decoration">
                @foreach($finder->statuses as $item)
                    <li>
                        <a href="{{ $item->link }}">{{ $item->label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="column is-one-quarter">
            <h3>Sorts</h3>
            <ul class="no-decoration">
                @foreach($finder->sorts as $item)
                    <li>
                        <a href="{{ $item->link }}">{{ $item->label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <p>
            <a href="{{ $finder->clear->link }}">{{ $finder->clear->label }}</a>
        </p>
    </div>
</div>
