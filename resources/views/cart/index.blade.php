<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($items as $item)
                <form method='post' action='{{ route('cart.update', ['id' => $item->id, 'cart' => $cart]) }}'>
                    @csrf
                    @method('patch')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <table>
                                <tr>
                                    <td style='white-space: nowrap;vertical-align: bottom;'>
                                        Product: {{ $item->description }}<br />
                                        Aantal: <input type="number" min="0" max="255" name="amount" value="{{ $item->amount }}" /><br />
                                        Prijs: &euro; {{ number_format($item->price, 2, ',', '.') }}
                                    </td>
                                    <td style='text-align: right; width: 100%;vertical-align: bottom;'>
                                        <x-primary-button class="mt-4">{{ __('Update') }}</x-primary-button><br /><br />
                                        &euro; {{ number_format($item->price(), 2, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br />
                </form>
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