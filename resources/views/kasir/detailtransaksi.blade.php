@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Detail Order ORD') }}{{ sprintf('%03d', $id) }}</span>
                        <span>
                            <button class="btn btn-sm btn-danger" onclick="window.history.back()">X</button>
                        </span>
                    </div>
                    <div class="card-body">
                        <table id="detailtransaksi" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Nama Masakan</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Catatan</th>
                                    <th class="text-center">Ubah Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailOrders as $index => $detailOrder)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detailOrder->masakan->nama_masakan }}</td>
                                        <td>{{ $detailOrder->qty }}</td>
                                        <td>
                                            @if ($detailOrder->keterangan)
                                                {{ $detailOrder->keterangan }}
                                            @else
                                                Tidak ada catatan
                                            @endif
                                        </td>
                                        @if ($detailOrder->status_detail_order == 5)
                                            <td>
                                                <button class="btn btn-success pesanan-selesai"
                                                    data-detail-order-id="{{ $detailOrder->id }}"
                                                    data-order-id="{{ $detailOrder->id_order }}"
                                                    data-meja-id="{{ $detailOrder->order->no_meja }}"
                                                    onclick="handlePesananSelesai(this)">Pesanan Selesai</button>

                                                <button class="btn btn-danger pesanan-batal"
                                                    data-detail-order-id="{{ $detailOrder->id }}"
                                                    data-order-id="{{ $detailOrder->id_order }}"
                                                    data-meja-id="{{ $detailOrder->order->no_meja }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#batalModal{{ $detailOrder->id }}">Pesanan
                                                    Dibatalkan</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="batalModal{{ $detailOrder->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="batalModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="batalModalLabel">Alasan
                                                                    Pembatalan Pesanan</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="batalForm{{ $detailOrder->id }}">
                                                                    @csrf
                                                                    <input type="hidden" name="detail_order_id"
                                                                        value="{{ $detailOrder->id }}">
                                                                    <input type="hidden" name="order_id"
                                                                        value="{{ $detailOrder->id_order }}">
                                                                    <input type="hidden" name="meja_id"
                                                                        value="{{ $detailOrder->order->no_meja }}">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" name="alasan" placeholder="Isi alasan pembatalan pesanan (Wajib Diisi)" required></textarea>
                                                                    </div>
                                                                    <button type="button" class="btn btn-primary mt-2"
                                                                        onclick="submitBatalForm('{{ $detailOrder->id }}')">Submit</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @else
                                            @if ($detailOrder->alasan)
                                                <td>
                                                    {{ $detailOrder->status_order ?? 'N/A' }}
                                                    <strong><p>Alasan : {{ $detailOrder->alasan }}</p></strong>
                                                </td>
                                            @else
                                                <td>
                                                    {{ $detailOrder->status_order ?? 'N/A' }}
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#detailtransaksi').DataTable();
        });

        function handlePesananSelesai(button) {
            var confirmation = confirm('Apakah anda yakin pesanan sudah selesai?');
            if (confirmation) {
                // Proceed with your logic for 'Pesanan Selesai'
                executePesananSelesai(button);
            }
        }

        function submitBatalForm(detailOrderId) {
            var formId = 'batalForm' + detailOrderId;
            var form = $('#' + formId);

            // You can add additional validation if needed
            if (form[0].checkValidity()) {
                // Proceed with your logic for 'Pesanan Dibatalkan'
                executePesananBatal(form);
            } else {
                // Trigger HTML5 validation
                form.find(':submit').click();
            }
        }

        function executePesananSelesai(button) {
            var detailOrderId = button.dataset.detailOrderId;
            var orderId = button.dataset.orderId;
            var mejaId = button.dataset.mejaId;

            // Execute your AJAX call for 'Pesanan Selesai'
            $.ajax({
                type: 'POST',
                url: '{{ route('pesanan.selesai') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'detail_order_id': detailOrderId,
                    'order_id': orderId,
                    'meja_id': mejaId
                },
                success: function(response) {
                    console.log(response);
                    alert('Berhasil menyelesaikan pesanan');
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                    alert('Gagal menyelesaikan pesanan');
                }
            });
        }

        function executePesananBatal(form) {
            var detailOrderId = form.find('input[name="detail_order_id"]').val();
            var orderId = form.find('input[name="order_id"]').val();
            var mejaId = form.find('input[name="meja_id"]').val();
            var alasan = form.find('textarea[name="alasan"]').val();

            // Execute your AJAX call for 'Pesanan Dibatalkan' with additional 'alasan' parameter
            $.ajax({
                type: 'POST',
                url: '{{ route('pesanan.batal') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'detail_order_id': detailOrderId,
                    'order_id': orderId,
                    'meja_id': mejaId,
                    'alasan': alasan
                },
                success: function(response) {
                    console.log(response);
                    alert('Berhasil membatalkan pesanan');
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                    alert('Gagal membatalkan pesanan');
                }
            });
        }
    </script>
@endsection
