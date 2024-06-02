<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kortings codes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="get" action="{{ route('coupons.create') }}">
                        <x-primary-button class="mt-4">{{ __('Toevoegen') }}</x-primary-button>
                    </form>
                </div>
            </div>
            <br />
            @foreach ($items as $coupon)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table>
                        <tr>
                            <td>Code:&nbsp;</td>
                            <td>{{ $coupon->code }}</td>
                            <td rowspan="3">
                                --edit--
                                --delete--
                            </td>
                        </tr>
                        <tr>
                            <td>Discount:&nbsp;</td>
                            <td>{{ $coupon->discount }}</td>
                        </tr>
                        <tr>
                            <td>Type:&nbsp;</td>
                            <td>{{ $coupon->typeDescription() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            @endforeach
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="get" action="{{ route('coupons.create') }}">
                        <x-primary-button class="mt-4">{{ __('Toevoegen') }}</x-primary-button>
                    </form
                </div>
            </div>
        </div>
    </div>
</x-app-layout>