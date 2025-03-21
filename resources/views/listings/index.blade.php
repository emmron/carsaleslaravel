<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Cars') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('listings.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Search -->
                            <div>
                                <x-input-label for="search" :value="__('Search')" />
                                <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" placeholder="Search by keyword..." />
                            </div>

                            <!-- Make -->
                            <div>
                                <x-input-label for="make" :value="__('Make')" />
                                <select id="make" name="make" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All Makes</option>
                                    @foreach($makes as $make)
                                        <option value="{{ $make }}" {{ request('make') == $make ? 'selected' : '' }}>{{ $make }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Body Type -->
                            <div>
                                <x-input-label for="body_type" :value="__('Body Type')" />
                                <select id="body_type" name="body_type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All Body Types</option>
                                    @foreach($bodyTypes as $bodyType)
                                        <option value="{{ $bodyType }}" {{ request('body_type') == $bodyType ? 'selected' : '' }}>{{ $bodyType }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Price Range -->
                            <div>
                                <x-input-label :value="__('Price Range')" />
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <x-text-input type="number" name="min_price" :value="request('min_price')" placeholder="Min Price" class="block mt-1 w-full" />
                                    </div>
                                    <div>
                                        <x-text-input type="number" name="max_price" :value="request('max_price')" placeholder="Max Price" class="block mt-1 w-full" />
                                    </div>
                                </div>
                            </div>

                            <!-- Year Range -->
                            <div>
                                <x-input-label :value="__('Year Range')" />
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <x-text-input type="number" name="min_year" :value="request('min_year')" placeholder="Min Year" class="block mt-1 w-full" />
                                    </div>
                                    <div>
                                        <x-text-input type="number" name="max_year" :value="request('max_year')" placeholder="Max Year" class="block mt-1 w-full" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Sort Options -->
                            <div>
                                <x-input-label for="sort_by" :value="__('Sort By')" />
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <select id="sort_by" name="sort_by" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Listed</option>
                                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                                            <option value="year" {{ request('sort_by') == 'year' ? 'selected' : '' }}>Year</option>
                                            <option value="odometer" {{ request('sort_by') == 'odometer' ? 'selected' : '' }}>Odometer</option>
                                        </select>
                                    </div>
                                    <div>
                                        <select id="sort_order" name="sort_order" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <x-primary-button type="submit" class="w-full justify-center py-3">
                                    {{ __('Search') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Car Listings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">{{ $listings->total() }} vehicles found</h3>
                        @auth
                            <x-primary-button-link href="{{ route('listings.create') }}">
                                {{ __('List Your Car') }}
                            </x-primary-button-link>
                        @else
                            <x-primary-button-link href="{{ route('login') }}">
                                {{ __('Login to List Your Car') }}
                            </x-primary-button-link>
                        @endauth
                    </div>

                    @if(count($listings) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($listings as $listing)
                                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 transition-transform hover:scale-105">
                                    <a href="{{ route('listings.show', $listing) }}" class="block">
                                        <div class="relative h-48 bg-gray-200">
                                            @if($listing->images->where('is_primary', true)->first())
                                                <img src="{{ asset('storage/' . $listing->images->where('is_primary', true)->first()->image_path) }}"
                                                    alt="{{ $listing->title }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full">
                                                    <p class="text-gray-500">No image available</p>
                                                </div>
                                            @endif

                                            @if($listing->featured)
                                                <div class="absolute top-0 left-0 bg-yellow-500 text-white px-2 py-1 text-xs font-bold">
                                                    FEATURED
                                                </div>
                                            @endif

                                            <div class="absolute bottom-0 right-0 bg-blue-600 text-white px-3 py-1 text-lg font-bold">
                                                ${{ number_format($listing->price) }}
                                            </div>
                                        </div>

                                        <div class="p-4">
                                            <h3 class="text-xl font-bold truncate">{{ $listing->title }}</h3>
                                            <div class="mt-2 flex items-center text-gray-700">
                                                <span class="text-sm">{{ $listing->year }} | {{ $listing->transmission }} | {{ number_format($listing->odometer) }} km</span>
                                            </div>
                                            <div class="mt-2 flex justify-between items-center">
                                                <span class="text-sm text-gray-600">{{ $listing->body_type }} | {{ $listing->fuel_type }}</span>
                                                <span class="text-sm text-gray-600">{{ $listing->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $listings->links() }}
                        </div>
                    @else
                        <div class="bg-gray-100 p-6 rounded-lg text-center">
                            <p class="text-gray-700">No vehicles found matching your criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>