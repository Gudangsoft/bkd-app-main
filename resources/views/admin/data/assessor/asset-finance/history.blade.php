@extends('admin.layouts.master')
@section('title')
    Riwayat Pembayaran Dana Asesor
@endsection
@section('content')
    <div class="container" id="contacts">
        <div class="page-title-container">
            <div class="row g-0">
                <div class="col-auto mb-2 mb-md-0 me-auto">
                    <h1 class="mb-0 pb-2 display-4">Riwayat Pembayaran Dana Asesor</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="/">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('finances.index', ['category' => 'assessor']) }}">@lang('dashboard.finance.titles')
                                    Asesor</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">Riwayat Pembayaran Dana Asesor</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="w-100 d-md-none"></div>
            </div>
        </div>

        <div class="row g-0">
            <div class="col-12">
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('message') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                {{-- <livewire:assessor-finace> --}}
            </div>
            <div class="col-12">
                <div class="card mb-5">
                    <div class="card-body">
                        @foreach ($data as $item)
                            <div class="row g-0">
                                <div
                                    class="col-auto sw-1 d-flex flex-column justify-content-center align-items-center position-relative me-4">
                                    <div class="w-100 d-flex sh-1"></div>
                                    <div
                                        class="rounded-xl shadow d-flex flex-shrink-0 justify-content-center align-items-center">
                                        <div class="bg-gradient-light sw-1 sh-1 rounded-xl position-relative"></div>
                                    </div>
                                    <div class="w-100 d-flex h-100 justify-content-center position-relative">
                                        <div class="line-w-1 bg-separator h-100 position-absolute"></div>
                                    </div>
                                </div>
                                <div class="col mb-4">
                                    <div class="h-100">
                                        <div class="d-flex flex-column justify-content-start">
                                            <div class="d-flex flex-column">
                                                <a href="#" class="heading">{{ $item->date }}</a>
                                                <div class="text-alternate">Rp {{ number_format($item->amount) }} | <a
                                                        data-bs-toggle="modal" data-bs-target="#proofOfPayment"
                                                        class="badge bg-primary text-uppercase">Bukti Bayar</a></div>
                                                <div class="modal fade" id="proofOfPayment" tabindex="-1" role="dialog"
                                                    aria-labelledby="proofOfPaymentLabelDefault" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="proofOfPaymentLabelDefault">Bukti Bayar</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="{{ asset('storage/images/payment_assessor/proof_of_payment/'.$item->proof_of_payment) }}" alt="Bukti Bayar" style="width: 100%; height:100%;">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-muted mt-1">
                                                    {{ $item->description }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('assets/js') }}/forms/controls.select2.js"></script>
        <script>
            window.addEventListener('openModalPay', event => {
                $("#assessor_id").val(event.detail.uid);
                $("#openModalPay").modal('show');
            });
        </script>
    @endpush
@endsection
