@extends('admin.layouts.app')

@section('title')
    Invoice @lang('dashboard.payment.title')
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Invoice @lang('dashboard.payment.titles')</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <a href="{{ route('payments.index') }}" class="hover:text-gray-700">@lang('dashboard.payment.titles')</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Invoice</span>
        </nav>
    </div>

    <div class="mb-4 text-right">
        <a href="{{ route('download-invoice', $payment->id) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">
            Download
        </a>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Invoice @lang('dashboard.payment.title')</h2>
                <p class="mt-1 text-sm text-gray-600">Date: {{ $payment->date }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-400">Bill To</h3>
                <p class="mt-1 font-medium text-gray-900">{{ $payment->user->name }}</p>
                <p class="text-sm text-gray-600">{{ $payment->user->progdi }}</p>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wide text-gray-400">
                        <th class="py-2 pr-4">Description</th>
                        <th class="py-2 pr-4">Nama Asesor</th>
                        <th class="py-2 pr-4">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 pr-4">Asesor 1</td>
                        <td class="py-3 pr-4">{{ $payment->assessorOne->name }}</td>
                        <td class="py-3 pr-4">{{ number_format($payment->amount_one) }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-4">Asesor 2</td>
                        <td class="py-3 pr-4">{{ $payment->assessorTwo->name }}</td>
                        <td class="py-3 pr-4">{{ number_format($payment->amount_two) }}</td>
                    </tr>
                    <tr class="font-semibold text-gray-900">
                        <td class="py-3 pr-4" colspan="2">Total</td>
                        <td class="py-3 pr-4">{{ number_format($payment->amount) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="mt-6 border-t border-gray-100 pt-4 text-sm text-gray-500">Terima kasih telah membayar lunas</p>
    </div>
@endsection
