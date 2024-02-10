@extends('layouts.app')

@section('content')
    <div class="container" style="margin-bottom: 4%">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ __('Data Riwayat Transaksi') }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (count($orders) > 0)
                            <table id="riwayattransaksi" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID Order</th>
                                        <th class="text-center">No Meja</th>
                                        <th class="text-center">Nama Pelanggan</th>
                                        <th class="text-center">Total Bayar</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center aksi-column">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>ORD{{ sprintf('%03d', $order->id) }}</td>
                                            <td>{{ $order->no_meja }}</td>
                                            <td>{{ $order->user->nama_user }}</td>
                                            <td>Rp{{ number_format(
                                                $order->detailorder->sum(function ($item) {
                                                    return $item->masakan->harga * $item->qty;
                                                }),
                                                0,
                                                ',',
                                                '.',
                                            ) }}
                                            </td>
                                            <td>
                                                {{ $order->sorder->status_order }}<br>

                                                @php
                                                    $selesaiCount = $order->detailorder->where('status_detail_order', 6)->count();
                                                    $batalCount = $order->detailorder->where('status_detail_order', 7)->count();
                                                @endphp

                                                <strong>
                                                    {{ $selesaiCount }} Order Selesai, {{ $batalCount }} Order Dibatalkan
                                                </strong>
                                            </td>
                                            <td class="aksi-column">
                                                <a href="{{ route('detail.transaksi', ['id' => $order->id]) }}"
                                                    class="btn btn-success">Lihat Detail Order</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <table id="riwayattransaksi" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID Order</th>
                                        <th class="text-center">No Meja</th>
                                        <th class="text-center">Nama Pelanggan</th>
                                        <th class="text-center">Total Bayar</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center aksi-column">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6">
                                            <p>No data available in table</p>
                                        </td>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#riwayattransaksi').DataTable({
                buttons: [{
                        extend: 'copy',
                        exportOptions: {
                            columns: ':visible:not(.aksi-column)'
                        },
                        title: 'BelajarUKK',
                        filename: 'BelajarUKK',
                        className: 'btn-datatable',
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible:not(.aksi-column)'
                        },
                        title: 'BelajarUKK',
                        filename: 'BelajarUKK',
                        className: 'btn-datatable',
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible:not(.aksi-column)'
                        },
                        title: 'BelajarUKK',
                        filename: 'BelajarUKK',
                        className: 'btn-datatable',
                    }
                ]
            });

            table.buttons().container()
                .appendTo('#riwayattransaksi_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
