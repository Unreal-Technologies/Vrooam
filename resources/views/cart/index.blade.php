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
                            <table id="cart-item">
                                <tr>
                                    <td>Product:&nbsp;</td>
                                    <td>{{ $item->product()->description }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Aantal:&nbsp;</td>
                                    <td>
                                        <input type="number" min="0" max="255" name="amount" value="{{ $item->amount }}" />
                                        @if (session('status') === 'item-updated-'.$item->id)
                                            <span
                                                x-data="{ show: true }"
                                                x-show="show"
                                                x-transition
                                                x-init="setTimeout(() => show = false, 2000)"
                                                class="text-sm text-gray-600"
                                            >{{ __('Opgeslagen.') }}</span>
                                        @endif
                                    </td>
                                    <td><x-primary-button class="mt-4">{{ __('Update') }}</x-primary-button></td>
                                </tr>
                                <tr>
                                    <td>Prijs:&nbsp;</td>
                                    <td>&euro; {{ number_format($item->product()->price, 2, ',', '.') }}</td>
                                    <td>&euro; {{ number_format($item->amount * $item->product()->price, 2, ',', '.') }}</td>
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
                                <td rowspan="4">
                                    @if ($coupon !== null)
                                        <span style='float:left;clear: both;'>
                                            <form method='post' action='{{ route('cart.removecoupon', ['cart' => $cartId]) }}'>
                                                @csrf
                                                <x-primary-button class="mt-4" style="clear: both;">{{ __('Korting Verwijderen') }}</x-primary-button>
                                            </form>
                                        </span>
                                    @else
                                        <x-primary-button x-data=""  x-on:click.prevent="$dispatch('open-modal', 'input-coupon-code')">Korting Toevoegen</x-primary-button>
                                        @error('code')
                                            <span
                                                x-data="{ show: true }"
                                                x-show="show"
                                                x-transition
                                                x-init="setTimeout(() => show = false, 2000)"
                                                class="text-sm text-gray-600"
                                            >{{ $message }}</span>
                                        @enderror
                                    @endif
                                    <br /><br />
                                    <span style='float:left;clear: both;'>
                                        <form method='post' action='{{ route('cart.destroy', ['cart' => $cartId]) }}'>
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
                                <td colspan="3"><hr /></td>
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
    <x-modal name="input-coupon-code" :show="$coupon === null && session('status') === 'open-modal'" focusable>
        <form method='post' action='{{ route('cart.addcoupon', ['cart' => $cartId]) }}'>
            @csrf
            <label for="code">Korting code:</label>
            <input id="code" name="code" type="text" class="mt-1 block w-full" />
            <x-primary-button class="mt-4">{{ __('Toevoegen') }}</x-primary-button>
        </form>
    </x-modal>
</x-app-layout>