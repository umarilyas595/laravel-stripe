<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-2">
        @if ($purchase)
            <div
                class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                        {{ $purchase->product->name }} ( {{ $user->pm_last_four }} )</h5>
                </a>
                <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">{{ $purchase->product->description }}</p>
            </div>
        @endif
    </div>
</x-app-layout>
