@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Detail Pesanan') }}</span>
                        <span>
                            @if ($orderStatus == 1 || $orderStatus == 2)
                                <a href="{{ route('make.order', ['nomeja' => $idmeja]) }}" class="btn btn-sm btn-danger">X</a>
                            @elseif (url()->previous() == route('riwayat.order'))
                                <a href="{{ route('riwayat.order') }}" class="btn btn-sm btn-danger">X</a>
                            @else
                                <a href="{{ route('order') }}" class="btn btn-sm btn-danger">X</a>
                            @endif
                        </span>
                    </div>
                    <div class="card-body">
                        @if ($dorders->isEmpty())
                            <h5 class="text-center">Anda Belum Membuat Pesanan</h5>
                        @else
                            <table class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Nama Masakan</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Catatan</th>
                                        <th class="text-center">Total Harga</th>
                                        @if (in_array($dorders->first()->status_detail_order, [1, 2]))
                                            <th class="text-center">Aksi</th>
                                        @else
                                            <th class="text-center">Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalHargaPesanan = 0; // Inisialisasi total harga
                                    @endphp

                                    @foreach ($dorders as $index => $dorder)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $dorder->masakan->nama_masakan }}</td>
                                            <td>Rp{{ number_format($dorder->masakan->harga, 0, ',', '.') }}</td>
                                            <td>{{ $dorder->qty }}</td>
                                            <td>
                                                @if ($dorder->keterangan)
                                                    {{ $dorder->keterangan }}
                                                @else
                                                    Tidak ada catatan
                                                @endif
                                            </td>
                                            <td>
                                                Rp{{ number_format($dorder->qty * $dorder->masakan->harga, 0, ',', '.') }}
                                            </td>
                                            @if (in_array($dorder->status_detail_order, [1, 2]))
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="mx-auto">
                                                            <button class="btn btn-warning me-2" data-bs-toggle="modal"
                                                                data-bs-target="#editpesananModal{{ $dorder->id }}">
                                                                Edit Pesanan
                                                            </button>
                                                        </div>

                                                        <div class="mx-auto">
                                                            <form action="{{ route('delete.dorder', $dorder->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger"
                                                                    onclick="return confirm('Apakah anda yakin mau menghapus item ini dari pesanan anda?')">
                                                                    Hapus Pesanan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            @else
                                                @if ($dorder->alasan)
                                                    <td>
                                                        {{ $dorder->status_order ?? 'N/A' }}
                                                        <strong>
                                                            <p>Alasan : {{ $dorder->alasan }}</p>
                                                        </strong>
                                                    </td>
                                                @else
                                                    <td>
                                                        {{ $dorder->status_order ?? 'N/A' }}
                                                    </td>
                                                @endif
                                            @endif
                                        </tr>
                                        @php
                                            // Tambahkan total harga pesanan
                                            $totalHargaPesanan += $dorder->qty * $dorder->masakan->harga;
                                        @endphp
                                        <!-- Modal Update Pesanan -->
                                        <div class="modal fade" id="editpesananModal{{ $dorder->id }}" tabindex="-1"
                                            aria-labelledby="editpesananModalLabel{{ $dorder->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit Pesanan
                                                            {{ $dorder->masakan->nama_masakan }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form enctype="multipart/form-data" method="POST"
                                                            action="{{ route('update.dorder') }}">
                                                            @csrf

                                                            <div class="form-group">
                                                                <label for="qty">Jumlah Makanan</label>
                                                                <input name="qty" type="number"
                                                                    class="form-control qty-input"
                                                                    placeholder="Masukkan jumlah makanan yang ingin anda pesan"
                                                                    value="{{ $dorder->qty }}">
                                                            </div>

                                                            <div class="form-group mt-2">
                                                                <label for="keterangan">Catatan Tambahan</label>
                                                                <input name="keterangan" type="text" class="form-control"
                                                                    placeholder="Contoh : 'Jangan lupa sendok yaa' (Opsional)"
                                                                    value="{{ $dorder->keterangan }}">
                                                            </div>

                                                            <input name="iddorder" type="hidden"
                                                                value="{{ $dorder->id }}">
                                                            <button type="submit"
                                                                class="btn btn-primary mt-3">Pesan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <h5>Total Harga Pesanan</h5>
                                        </td>
                                        <td>
                                            <h5>
                                                Rp{{ number_format($totalHargaPesanan, 0, ',', '.') }}
                                            </h5>
                                        </td>
                                        @if (in_array($dorders->first()->status_detail_order, [1, 2]))
                                            <td>
                                                <form action="{{ route('simpan.pesanan') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id_order" value="{{ $dorder->id_order }}">
                                                    <button type="submit" class="btn btn-primary"
                                                        onclick="return confirm('Apakah pesanan anda sudah benar semua?')">
                                                        Simpan Pesanan
                                                    </button>
                                                </form>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                            @if ($dorders->first()->status_detail_order == 3)
                                <h5>Segera Bayar Pesanan Anda di Kasir !!!</h5>
                                <h5>Sebutkan ID Order Anda ke Kasir ("<span
                                        style="color: red">ORD{{ sprintf('%03d', $dorder->id_order) }}</span>")</h5>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
