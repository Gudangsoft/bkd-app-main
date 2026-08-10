<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-8 py-6 bg-white border border-gray-100 shadow-sm overflow-hidden sm:rounded-2xl">
        {{ $slot }}
    </div>
</div>
