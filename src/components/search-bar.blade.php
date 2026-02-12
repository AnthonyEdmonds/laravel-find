@props([
    'finder',
])

<div>
    <div>
        <div>
            <form action="{{ $finder->route }}">
                <label for="search">Quick search</label>
                <input
                    name="search"
                    value="{{ old('search', $finder->currentSearch) }}"
                />
            </form>

            <p><small>You may search by:</small></p>
            <ul>
                @foreach($finder->searchable as $label)
                    <li>{{ $label }}</li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2>Filters</h2>
            <ul>
                @foreach($finder->filters as $item)
                    <li>
                        <a href="{{ $item->link }}">{{ $item->label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2>Statuses</h2>
            <ul>
                @foreach($finder->statuses as $item)
                    <li>
                        <a href="{{ $item->link }}">{{ $item->label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2>Sorts</h2>
            <ul>
                @foreach($finder->sorts as $item)
                    <li>
                        <a href="{{ $item->link }}">{{ $item->label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div>
        <p>
            <a href="{{ $finder->clearLink }}">Clear all applied filters, searches, and sorts</a>.
        </p>
    </div>
</div>