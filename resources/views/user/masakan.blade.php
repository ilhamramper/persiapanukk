@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="row col-md-10">
                                <div class="col-sm-auto">
                                    <label for="jmasakan" class="col-form-label">{{ __('Pilih Jenis Menu :') }}</label>
                                </div>
                                <div class="col-sm-auto">
                                    <select id="jmasakan" class="form-control" name="jmasakan">
                                        <option value="All">Tampilkan Semua</option>
                                        @foreach ($jmasakans as $jmasakan)
                                            <option value="{{ $jmasakan->id }}">{{ $jmasakan->jenis_masakan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-auto">
                                    <label for="search" class="col-form-label">{{ __('Cari Menu :') }}</label>
                                </div>
                                <div class="col-sm-auto">
                                    <input type="text" id="search" class="form-control" placeholder="Cari Menu">
                                </div>
                            </div>
                            <div class="col-sm-auto mt-2">
                                <a href="{{ route('dorder', ['id' => $idorder]) }}" class="btn position-relative">
                                    <i class="fa-solid fa-utensils fa-xl"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $jumlahData }}
                                    </span>
                                </a>
                                <span><a href="{{ route('order') }}" class="btn btn-sm btn-danger ms-3">X</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-md-3 g-3" id="masakan-container">
                            @foreach ($masakans as $masakan)
                                <div class="col" data-jmasakan="{{ $masakan->id_jmasakan }}">
                                    <div class="card shadow-sm">
                                        <img src="{{ asset('storage/' . $masakan->image) }}"
                                            style="max-height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $masakan->nama_masakan }}</h5>
                                            <p class="card-text">
                                                Harga : Rp{{ number_format($masakan->harga, 0, ',', '.') }}<br>
                                                Status :
                                                @if ($masakan->status_masakan == 'Tersedia')
                                                    <span class="badge text-bg-success">Tersedia</span>
                                                @else
                                                    <span class="badge text-bg-danger">Tidak Tersedia</span>
                                                @endif
                                            </p>
                                            @if ($masakan->status_masakan == 'Tersedia')
                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#pesanModal{{ $masakan->id }}">
                                                    Pesan
                                                </button>
                                            @else
                                                <button class="btn btn-secondary" disabled>Pesan</button>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Modal Pesan Masakan -->
                                    <div class="modal fade" id="pesanModal{{ $masakan->id }}" tabindex="-1"
                                        aria-labelledby="pesanModalLabel{{ $masakan->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="pesanModalLabel{{ $masakan->id }}">Pesan
                                                        {{ $masakan->nama_masakan }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form enctype="multipart/form-data" method="POST"
                                                        action="{{ route('store.dorder') }}">
                                                        @csrf

                                                        <div class="form-group">
                                                            <label for="qty">Jumlah Makanan</label>
                                                            <input name="qty" type="number"
                                                                class="form-control qty-input" id="qty"
                                                                placeholder="Masukkan jumlah makanan yang ingin anda pesan">
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="totalHarga">Total Harga</label>
                                                            <input type="text" class="form-control totalHarga"
                                                                id="totalHarga" readonly>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="keterangan">Catatan Tambahan</label>
                                                            <input name="keterangan" type="text" class="form-control"
                                                                placeholder="Contoh : 'Jangan lupa sendok yaa' (Opsional)">
                                                        </div>

                                                        <input name="idmasakan" type="hidden" value="{{ $masakan->id }}">
                                                        <button type="submit" class="btn btn-primary mt-3">Pesan</button>
                                                    </form>
                                                    <input class="hargamasakan" type="hidden"
                                                        value="{{ $masakan->harga }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div id="no-match-message" class="col" style="display: none;">
                                <p><strong>Tidak Ada Menu Yang Cocok</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#jmasakan').on('change', function() {
                filterMenu();
            });

            $('#search').on('input', function() {
                filterMenu();
            });

            $('.qty-input').on('input', function() {
                updateTotalHarga($(this));
            });

            function filterMenu() {
                var selectedValue = $('#jmasakan').val();
                var searchValue = $('#search').val().toLowerCase();
                var matchedElements;

                if (selectedValue === 'All') {
                    matchedElements = $('#masakan-container .col');
                } else {
                    matchedElements = $('#masakan-container .col[data-jmasakan="' + selectedValue + '"]');
                }

                if (searchValue) {
                    matchedElements = matchedElements.filter(function() {
                        return $(this).find('.card-title').text().toLowerCase().includes(searchValue);
                    });
                }

                if (selectedValue === 'All' && searchValue === '' && matchedElements.length === 0) {
                    $('#no-match-message').show();
                } else {
                    $('#no-match-message').hide();
                }

                $('#masakan-container .col').hide();
                matchedElements.show();

                if (matchedElements.length === 0) {
                    $('#no-match-message').show();
                } else {
                    $('#no-match-message').hide();
                }
            }

            function updateTotalHarga(input) {
                var qty = input.val();
                var harga = input.closest('.modal').find('.hargamasakan').val();
                var totalHarga = qty * harga;

                var formattedTotalHarga = 'Rp' + totalHarga.toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&.');

                input.closest('.modal').find('.totalHarga').val(formattedTotalHarga);
            }
        });
    </script>
@endsection
