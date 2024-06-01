<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Winkelwagen') }}
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
                                            >{{ __('Opgeslagen.') }}</span>
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
                            >{{ __('Product "'.session('product').'" is verwijderd.') }}</p>
                        @endif
                    </div>
                </div>
                <br />
            @endif
            @if (count($items) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table id="cart-totals">
                            <tr>
                                <td rowspan="3">
                                    @if ($coupon !== null)
                                        <span style='float:left;clear: both;'>
                                            <form method='post' action='{{ route('cart.removecoupon', ['cart' => $items[0]->cart_id]) }}'>
                                                @csrf
                                                <x-primary-button class="mt-4" style="clear: both;">{{ __('Coupon Verwijderen') }}</x-primary-button>
                                            </form>
                                        </span>
                                    @else
                                        <form method='post' action='{{ route('cart.addcoupon', ['cart' => $items[0]->cart_id]) }}'>
                                            @csrf
                                            <label for="code">Coupon code:</label>
                                            <input id="code" name="code" type="text" class="mt-1 block w-full" />
                                            <x-primary-button class="mt-4">{{ __('Toevoegen') }}</x-primary-button>
                                            @error('code')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </form>
                                    @endif
                                    <br /><br />
                                    <span style='float:left;clear: both;'>
                                        <form method='post' action='{{ route('cart.destroy', ['cart' => $items[0]->cart_id]) }}'>
                                            @csrf
                                            @method('delete')
                                            <x-danger-button class="ms-3">{{ __('Winkelwagen legen') }}</x-danger-button>
                                        </form>
                                    </span>
                                </td>
                                <td></td>
                                <td>Subtotaal:&nbsp;</td>
                                <td>&euro; {{ number_format($sum, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>
                                    @if ($coupon !== null)
                                        {!! $coupon->text() !!}
                                    @endif
                                </td>
                                <td>Korting:&nbsp;</td>
                                <td>&euro; {{ number_format($discount, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Totaal:&nbsp;</td>
                                <td>&euro; {{ number_format($total, 2, ',', '.') }}</td>
                            </tr>
                        </table>
                        @if (session('status') === 'item-deleted')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Product "'.session('product').'" is verwijderd.') }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>