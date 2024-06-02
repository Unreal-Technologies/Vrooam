<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product - '.$title) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="{{ route($route, $param) }}">
                        @csrf
                        @method($method)
                        <table class="product-editlist">
                            <tr>
                                <td>Omschrijving:&nbsp;</td>
                                <td><input name="description" value="{{ $description }}" /></td>
                                <td>
                                    @error('description')
                                        <span
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600"
                                        >{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>Artiekel Nummer:&nbsp;</td>
                                <td><input name="code" value="{{ $code }}" /></td>
                                <td>
                                    @error('code')
                                        <span
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600"
                                        >{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>Prijs:&nbsp;</td>
                                <td><input name="price" type="number" min="0" max="65536" step="0.01" value="{{ number_format($price, 2); }}" /></td>
                                <td>
                                    @error('price')
                                        <span
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600"
                                        >{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>Omschrijving:&nbsp;</td>
                                <td><textarea name="text">{{ $text }}</textarea></td>
                                <td>
                                    @error('text')
                                        <span
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600"
                                        >{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td><a href='{{ route('products.editlist') }}' class='inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'>{{ __('Terug') }}</a></td>
                                <td align="right"><x-primary-button class="mt-4">{{ __('Opslaan') }}</x-primary-button></td>
                            </tr>
                        </table>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>