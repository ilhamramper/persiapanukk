@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ __('Buat Order') }}</span>
                                @if (!$isBuatOrderDisabled)
                                    <a href="{{ route('create.order') }}" class="btn btn-success">Buat Order</a>
                                @else
                                    <button class="btn btn-secondary" disabled>Buat Order</button>
                                @endif
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
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>ORD{{ sprintf('%03d', $order->id) }}</td>
                                            <td>{{ $order->no_meja }}</td>
                                            <td>{{ $order->tanggal }}</td>
                                            <td>{{ $order->sorder->status_order }}</td>
                                            <th>
                                                @if (in_array($order->status_order, [1, 2]))
                                                    <a href="{{ route('make.order', ['nomeja' => $order->no_meja]) }}"
                                                        class="btn btn-success">Buat Pesanan</a>
                                                @elseif (in_array($order->status_order, [3, 4, 5]))
                                                    <a href="{{ route('dorder', ['id' => $order->id]) }}"
                                                        class="btn btn-primary">Lihat Pesanan</a>
                                                @endif
                                            </th>
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
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td colspan="5">
                                        <p>
                                            Semua order sudah selesai<br>
                                            <a href="{{ route('riwayat.order') }}" class="btn btn-primary mt-2">Riwayat
                                                Order</a>
                                        </p>
                                    </td>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
