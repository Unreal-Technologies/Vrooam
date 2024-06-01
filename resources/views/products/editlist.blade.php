<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Producten - Bewerken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @foreach ($products as $product)
            <form method="POST" action="{{ route('cart.store', ['id' => $product->id]) }}">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="product-editlist">
                            <tr>
                                <td>Omschrijving:&nbsp;</td>
                                <td>{{ $product->description }}</td>
                                <td rowspan="4">
                                    --buttons--
                                </td>
                            </tr>
                            <tr>
                                <td>Artiekel Nummer:&nbsp;</td>
                                <td>{{ $product->code }}</td>
                            </tr>
                            <tr>
                                <td>Prijs:&nbsp;</td>
                                <td>&euro; {{ number_format($product->price, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Omschrijving:&nbsp;</td>
                                <td>{{ $product->text }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
            <br />
            @endforeach
        </div>
    </div>
</x-app-layout>