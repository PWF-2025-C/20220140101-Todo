<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 drak:text-gray-200 leading-tight">
        {{ __('Todo') }}
    </h2>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white drak:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 drak:text-gray-100">
            {{ __('Ini Halaman Todo') }}
            </div>
        </div>
    </div>
</div>
</x-slot>
</x-app-layout>