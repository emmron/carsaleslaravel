<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $listing->title }}
            </h2>
            <div class="flex space-x-2">
                @auth
                    @if(Auth::id() === $listing->user_id || Auth::user()->isAdmin())
                        <x-primary-button-link href="{{ route('listings.edit', $listing) }}">
                            {{ __('Edit Listing') }}
                        </x-primary-button-link>

                        <form method="POST" action="{{ route('listings.destroy', $listing) }}" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit" onclick="return confirm('Are you sure you want to delete this listing?')">
                                {{ __('Delete') }}
                            </x-danger-button>
                        </form>
                    @endif
                @endauth

                <x-primary-button-link href="{{ route('listings.index') }}">
                    {{ __('Back to Listings') }}
                </x-primary-button-link>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Images Gallery -->
                    <div class="mb-8">
                        @if($listing->images->count() > 0)
                            <div class="grid grid-cols-1">
                                <div class="relative h-96 bg-gray-200 mb-4">
                                    <img src="{{ asset('storage/' . $listing->images->where('is_primary', true)->first()->image_path) }}"
                                        alt="{{ $listing->title }}"
                                        class="w-full h-full object-contain">

                                    @if($listing->featured)
                                        <div class="absolute top-0 left-0 bg-yellow-500 text-white px-3 py-1 text-sm font-bold">
                                            FEATURED
                                        </div>
                                    @endif
                                </div>

                                @if($listing->images->count() > 1)
                                    <div class="grid grid-cols-6 gap-2">
                                        @foreach($listing->images as $image)
                                            <div class="h-24 bg-gray-200 cursor-pointer">
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                    alt="{{ $listing->title }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-96 bg-gray-200 flex items-center justify-center">
                                <p class="text-gray-500">No images available</p>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Main Details -->
                        <div class="md:col-span-2">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $listing->title }}</h1>
                                    <p class="text-gray-600">{{ $listing->make }} {{ $listing->model }} {{ $listing->trim }}</p>
                                </div>
                                <div class="text-3xl font-bold text-blue-600">${{ number_format($listing->price) }}</div>
                            </div>

                            <div class="mb-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Description</h2>
                                <div class="prose max-w-none">
                                    {{ $listing->description }}
                                </div>
                            </div>

                            <div class="mb-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Vehicle Details</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Year</p>
                                        <p class="font-semibold">{{ $listing->year }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Make</p>
                                        <p class="font-semibold">{{ $listing->make }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Model</p>
                                        <p class="font-semibold">{{ $listing->model }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Trim</p>
                                        <p class="font-semibold">{{ $listing->trim ?: 'N/A' }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Body Type</p>
                                        <p class="font-semibold">{{ $listing->body_type }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Fuel Type</p>
                                        <p class="font-semibold">{{ $listing->fuel_type }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Transmission</p>
                                        <p class="font-semibold">{{ $listing->transmission }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Odometer</p>
                                        <p class="font-semibold">{{ number_format($listing->odometer) }} km</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Color</p>
                                        <p class="font-semibold">{{ $listing->color }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">VIN</p>
                                        <p class="font-semibold">{{ $listing->vin ?: 'N/A' }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Registration</p>
                                        <p class="font-semibold">{{ $listing->registration_number ?: 'N/A' }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="text-sm text-gray-600">Reg. Expiry</p>
                                        <p class="font-semibold">{{ $listing->registration_expiry ? $listing->registration_expiry->format('d/m/Y') : 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($listing->features->count() > 0)
                                <div class="mb-6">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Features</h2>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($listing->features->groupBy('category') as $category => $features)
                                            <div class="mb-4">
                                                <h3 class="font-semibold text-gray-800 mb-2">{{ $category }}</h3>
                                                <ul class="list-disc list-inside">
                                                    @foreach($features as $feature)
                                                        <li>{{ $feature->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Sidebar -->
                        <div class="md:col-span-1">
                            <div class="bg-gray-100 p-6 rounded-lg mb-6">
                                <h3 class="text-xl font-semibold mb-4">Contact Seller</h3>
                                <div class="mb-4">
                                    <p class="text-gray-700 font-semibold">{{ $listing->user->name }}</p>
                                    <p class="text-gray-600">Listed {{ $listing->created_at->diffForHumans() }}</p>
                                </div>

                                @auth
                                    <form action="{{ route('inquiries.store', $listing) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <x-input-label for="message" :value="__('Message')" />
                                            <textarea id="message" name="message" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>I'm interested in this {{ $listing->year }} {{ $listing->make }} {{ $listing->model }}. Please contact me for more information.</textarea>
                                        </div>

                                        <x-primary-button type="submit" class="w-full justify-center">
                                            {{ __('Send Inquiry') }}
                                        </x-primary-button>
                                    </form>
                                @else
                                    <p class="mb-4 text-gray-700">Please log in to contact the seller.</p>
                                    <x-primary-button-link href="{{ route('login') }}" class="w-full justify-center">
                                        {{ __('Log In to Contact') }}
                                    </x-primary-button-link>
                                @endauth
                            </div>

                            @if($similarListings->count() > 0)
                                <div>
                                    <h3 class="text-xl font-semibold mb-4">Similar Listings</h3>
                                    <div class="space-y-4">
                                        @foreach($similarListings as $similar)
                                            <a href="{{ route('listings.show', $similar) }}" class="block bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                                                <div class="relative h-32 bg-gray-200">
                                                    @if($similar->images->where('is_primary', true)->first())
                                                        <img src="{{ asset('storage/' . $similar->images->where('is_primary', true)->first()->image_path) }}"
                                                            alt="{{ $similar->title }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <div class="flex items-center justify-center h-full">
                                                            <p class="text-gray-500">No image</p>
                                                        </div>
                                                    @endif

                                                    <div class="absolute bottom-0 right-0 bg-blue-600 text-white px-2 py-1 text-sm font-bold">
                                                        ${{ number_format($similar->price) }}
                                                    </div>
                                                </div>

                                                <div class="p-3">
                                                    <h4 class="font-semibold text-gray-900 truncate">{{ $similar->title }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $similar->year }} | {{ number_format($similar->odometer) }} km</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>