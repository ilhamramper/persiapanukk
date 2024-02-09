@extends('layouts.app')

@section('content')
    <div class="container" style="margin-bottom: 4%">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ __('Data Transaksi') }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (count($orders) > 0)
                            <table id="transaksi" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID Order</th>
                                        <th class="text-center">No Meja</th>
                                        <th class="text-center">Nama Pelanggan</th>
                                        <th class="text-center">Total Bayar</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
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
                                            <td>{{ $order->sorder->status_order }}</td>
                                            <td>
                                                @if ($order->status_order == 1 || $order->status_order == 2)
                                                    <button class="btn btn-danger batal-order"
                                                        data-order-id="{{ $order->id }}"
                                                        data-meja-id="{{ $order->no_meja }}">Batalkan Order</button>
                                                @elseif ($order->status_order == 3)
                                                    <button class="btn btn-primary update-status-pembayaran"
                                                        data-order-id="{{ $order->id }}">Sudah Di Bayar</button>
                                                    <button class="btn btn-danger batal-order"
                                                        data-order-id="{{ $order->id }}"
                                                        data-meja-id="{{ $order->no_meja }}">Batalkan Order</button>
                                                @elseif ($order->status_order == 4)
                                                    <button class="btn btn-primary proses-order"
                                                        data-order-id="{{ $order->id }}"
                                                        data-user-id="{{ auth()->user()->id }}"
                                                        data-tanggal="{{ $order->created_at->toDateString() }}"
                                                        data-total-bayar="{{ $order->detailorder->sum(function ($item) {
                                                            return $item->masakan->harga * $item->qty;
                                                        }) }}">
                                                        Proses Order
                                                    </button>
                                                @elseif($order->status_order == 5)
                                                    <a href="{{ route('detail.transaksi', ['id' => $order->id]) }}"
                                                        class="btn btn-success">Lihat Detail Order</a>
                                                @endif
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
                                        <th class="text-center">No Meja</th>
                                        <th class="text-center">Nama Pelanggan</th>
                                        <th class="text-center">Total Bayar</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td colspan="6">
                                        <p>
                                            No data available in table<br>
                                            <a href="{{ route('riwayat.transaksi') }}" class="btn btn-primary mt-2">Riwayat
                                                Transaksi</a>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#transaksi').DataTable();

            $('.update-status-pembayaran').click(function() {
                var orderId = $(this).data('order-id');
                var confirmation = confirm('Apakah anda yakin order sudah dibayar?');
                if (confirmation) {
                    // Kirim permintaan AJAX ke server
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('update.status.pembayaran') }}',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'order_id': orderId
                        },
                        success: function(response) {
                            console.log(response);
                            alert('Berhasil membayar order');
                            location.reload();
                        },
                        error: function(error) {
                            console.error(error);
                            alert('Gagal membayar order');
                        }
                    });
                }
            });

            $('.batal-order').click(function() {
                var orderId = $(this).data('order-id');
                var mejaId = $(this).data('meja-id');
                var confirmation = confirm('Apakah anda yakin untuk membatalkan order?');
                if (confirmation) {
                    // Kirim permintaan AJAX ke server
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('batal.order') }}',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'order_id': orderId,
                            'meja_id': mejaId
                        },
                        success: function(response) {
                            console.log(response);
                            alert('Berhasil membatalkan order');
                            location.reload();
                        },
                        error: function(error) {
                            console.error(error);
                            alert('Gagal membatalkan order');
                        }
                    });
                }
            });

            $('.proses-order').click(function() {
                var orderId = $(this).data('order-id');
                var userId = $(this).data('user-id');
                var tanggal = $(this).data('tanggal');
                var totalBayar = $(this).data('total-bayar');

                // Kirim permintaan AJAX ke server
                $.ajax({
                    type: 'POST',
                    url: '{{ route('proses.order') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'order_id': orderId,
                        'user_id': userId,
                        'tanggal': tanggal,
                        'total_bayar': totalBayar
                    },
                    success: function(response) {
                        console.log(response);
                        alert('Berhasil memproses order');
                        location.reload();
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Gagal memproses order');
                    }
                });
            });
        });
    </script>
@endsection
