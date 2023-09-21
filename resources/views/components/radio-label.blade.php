@props(['value'])

<label
    {{ $attributes->merge(['class' => 'block m-2 p-1 border-gray-300 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-2']) }}>
    {{ $value ?? $slot }}
</label>
