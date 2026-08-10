@extends('admin.layouts.app')

@section('title')
    @lang('dashboard.setting.titles')
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">@lang('dashboard.setting.titles')</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">@lang('dashboard.setting.titles')</span>
        </nav>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="imagePreview()">
            @csrf
            <input type="hidden" name="setting_id" value="{{ $data->id ?? '' }}">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-semibold uppercase tracking-wide text-gray-400">App Name</label>
                    <input type="text" name="title" value="{{ $data->title ?? '' }}" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold uppercase tracking-wide text-gray-400">Email</label>
                    <input type="email" name="email" value="{{ $data->email ?? '' }}" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold uppercase tracking-wide text-gray-400">@lang('dashboard.phone')</label>
                    <input type="number" name="phone" value="{{ $data->phone ?? '' }}" required
                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold uppercase tracking-wide text-gray-400">Owner Id</label>
                    <select name="owner_id" class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                        @foreach ($users as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold uppercase tracking-wide text-gray-400">Image</label>
                <div class="flex flex-col items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4 text-center">
                    <img x-ref="preview"
                        src="{{ $data->logo ? asset('storage/logo') . '/' . $data->logo : asset('assets/img/logo/logo-green-dark.svg') }}"
                        alt="Logo" class="h-16 w-auto object-contain">
                    <label for="change-picture" class="cursor-pointer rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">
                        Pilih Image
                        <input id="change-picture" name="logo" type="file" class="hidden"
                            accept="image/png, image/jpeg, image/jpg, image/svg+xml" @change="onFileChange($event.target)">
                    </label>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold uppercase tracking-wide text-gray-400">Login Background</label>
                <div class="flex flex-col items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4 text-center">
                    <img x-ref="loginBackgroundPreview"
                        src="{{ $data->loginBackgroundUrl ?? asset('assets/img/background/background-blue.webp') }}"
                        alt="Login Background" class="h-32 w-full rounded-lg object-cover">
                    <label for="change-login-background" class="cursor-pointer rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">
                        Pilih Image
                        <input id="change-login-background" name="login_background" type="file" class="hidden"
                            accept="image/png, image/jpeg, image/jpg, image/webp" @change="onFileChange($event.target, 'loginBackgroundPreview')">
                    </label>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold uppercase tracking-wide text-gray-400">Description</label>
                <textarea name="description" rows="5"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">{{ $data->description ?? '' }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold uppercase tracking-wide text-gray-400">Address</label>
                <textarea name="address" rows="5"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">{{ $data->address ?? '' }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold uppercase tracking-wide text-gray-400">Buku Panduan PDF</label>
                <input type="file" name="manual_book"
                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                @if ($errors->has('manual_book'))
                    <p class="mt-1 text-xs text-red-600">{{ $errors->first('manual_book') }}</p>
                @endif
                <div id="pdfViewer" class="mt-3"></div>
            </div>

            <button type="submit" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">@lang('dashboard.save')</button>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .pdfobject-container {
            height: 500px;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.5/pdfobject.min.js"></script>
    <script>
        PDFObject.embed(@json($data->pdfManualBook ?? ''), "#pdfViewer");
    </script>
@endpush
