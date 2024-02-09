@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ __('Riwayat Order') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (count($orders) > 0)
                            <table class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID Order</th>
                                        <th class="text-center">No. Meja</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>ORD{{ sprintf('%03d', $order->id) }}</td>
                                            <td>{{ $order->no_meja }}</td>
                                            <td>{{ $order->tanggal }}</td>
                                            <td>
                                                {{ $order->sorder->status_order }}
                                                @php
                                                    $selesaiCount = $order->detailorder->where('status_detail_order', 6)->count();
                                                    $batalCount = $order->detailorder->where('status_detail_order', 7)->count();
                                                @endphp

                                                <strong>
                                                    <p>{{ $selesaiCount }} Order Selesai, {{ $batalCount }} Order
                                                        Dibatalkan</p>
                                                </strong>
                                            </td>
                                            <td>
                                                <a href="{{ route('dorder', ['id' => $order->id]) }}"
                                                    class="btn btn-primary">Detail Order</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <table class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID Order</th>
                                        <th class="text-center">No. Meja</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <tr>
                                        <td colspan="5"><p>No data available in table</p></td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
