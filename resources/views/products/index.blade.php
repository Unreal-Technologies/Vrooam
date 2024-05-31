<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @foreach ($products as $product)
            <form method="POST" action="{{ route('cart.store', ['id' => $product->id]) }}">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>{{ $product->description }}</p>
                        <span style='float: right;'>
                            &euro; {{ number_format($product->price, 2, ',', '.') }} 
                            <input value='1' type='number' min="1" max='255' name='amount' />
                            <x-primary-button class="mt-4">{{ __('Add To Cart') }}</x-primary-button>
                        </span>
                        <hr />
                        {{ $product->text }}
                        <br /><br/>
                    </div>
                </div>
            </form>
            <br />
            @endforeach
        </div>
    </div>
</x-app-layout>