<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($items as $item)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Product: {{ $item->description }}<br/>
                        Aantal: {{ $item->amount }}<br />
                        Prijs: &euro; {{ number_format($item->price, 2, ',', '.') }}
                        <span style='float:right;'>&euro; {{ number_format($item->price(), 2, ',', '.') }}</span>
                    </div>
                </div>
                <br />
            @endforeach
            @if (count($items) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <span style='float:right;'>Subtotaal: &euro; {{ number_format($sum, 2, ',', '.') }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>