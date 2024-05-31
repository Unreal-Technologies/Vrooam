<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($items as $item)
                <form method='post' action='{{ route('cartitems.update', ['cartitem' => $item->id]) }}'>
                    @csrf
                    @method('put')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <table>
                                <tr>
                                    <td style='white-space: nowrap;vertical-align: bottom;'>
                                        Product: {{ $item->product()->description }} @if (session('status') === 'item-updated-'.$item->id)
                                            <span
                                                x-data="{ show: true }"
                                                x-show="show"
                                                x-transition
                                                x-init="setTimeout(() => show = false, 2000)"
                                                class="text-sm text-gray-600"
                                            >{{ __('Saved.') }}</span>
                                        @endif<br />
                                        Aantal: <input type="number" min="0" max="255" name="amount" value="{{ $item->amount }}" /><br />
                                        Prijs: &euro; {{ number_format($item->product()->price, 2, ',', '.') }}
                                    </td>
                                    <td style='text-align: right; width: 100%;vertical-align: bottom;'>
                                        <x-primary-button class="mt-4">{{ __('Update') }}</x-primary-button><br /><br />
                                        &euro; {{ number_format($item->amount * $item->product()->price, 2, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br />
                </form>
            @endforeach
            @if (count($items) === 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __('Cart is empty.') }}
                        @if (session('status') === 'item-deleted')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Product "'.session('product').'" Deleted.') }}</p>
                        @endif
                    </div>
                </div>
                <br />
            @endif
            @if (count($items) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <span style='float:right;'>Subtotaal: &euro; {{ number_format($sum, 2, ',', '.') }}</span><br />
                        @if (session('status') === 'item-deleted')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Product "'.session('product').'" Deleted.') }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>