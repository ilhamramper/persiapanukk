@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ __('Data Menu') }}</span>
                            <a href="{{ route('create.menu') }}" class="btn btn-success">+ Buat Menu</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="menu" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Gambar</th>
                                    <th class="text-center">Nama Masakan</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jenis Masakan</th>
                                    <th class="text-center">Status Masakan</th>
                                    <th class="text-center">
                                        Pilih Semua
                                        <span style="padding-left: 10px;">
                                            <input type="checkbox" id="checkAll">
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($masakans as $masakan)
                                    <tr>
                                        <td>
                                            @if ($masakan->image)
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#imageModal{{ $masakan->id }}">
                                                    <img src="{{ asset('storage/' . $masakan->image) }}"
                                                        style="max-width: 150px;">
                                                </a>
                                                <!-- Modal Image Masakan -->
                                                <div class="modal fade" id="imageModal{{ $masakan->id }}" tabindex="-1"
                                                    aria-labelledby="imageModalLabel{{ $masakan->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="imageModalLabel{{ $masakan->id }}">Gambar {{ $masakan->nama_masakan }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="{{ asset('storage/' . $masakan->image) }}"
                                                                    style="width: 100%;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                Gambar Tidak Ada
                                            @endif
                                        </td>
                                        <td>{{ $masakan->nama_masakan }}</td>
                                        <td>Rp{{ number_format($masakan->harga, 0, ',', '.') }}</td>
                                        <td>{{ $masakan->jmasakan->jenis_masakan }}</td>
                                        <td>
                                            <select class="form-control status-select" name="status_masakan"
                                                data-menu-id="{{ $masakan->id }}">
                                                <option value="Tersedia"
                                                    {{ $masakan->status_masakan === 'Tersedia' ? 'selected' : '' }}>
                                                    Tersedia
                                                </option>
                                                <option value="Tidak Tersedia"
                                                    {{ $masakan->status_masakan === 'Tidak Tersedia' ? 'selected' : '' }}>
                                                    Tidak Tersedia</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="menu-checkbox" name="menu_ids[]"
                                                value="{{ $masakan->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <input type="hidden" id="selectedMenuIds" name="selectedMenuIds" value="">
                        <button id="deleteButton" class="btn btn-secondary" disabled>Hapus Menu</button>
                        <button id="editButton" class="btn btn-secondary" disabled>Edit Menu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#menu').DataTable();

            $('#checkAll').change(function() {
                $('.menu-checkbox').prop('checked', $(this).prop('checked'));

                updateSelectedMenuIds();
                updateButtonStates();
            });

            $('.menu-checkbox').change(function() {
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }

                var allChecked = $('.menu-checkbox:checked').length === $('.menu-checkbox').length;

                $('#checkAll').prop('checked', allChecked);

                updateSelectedMenuIds();
                updateButtonStates();
            });

            $('.status-select').change(function() {
                var menuId = $(this).data('menu-id');
                var newStatus = $(this).val();

                updateMenuStatus(menuId, newStatus);
            });

            function updateSelectedMenuIds() {
                var selectedIds = $('.menu-checkbox:checked').map(function() {
                    return $(this).val();
                }).get().join(',');

                $('#selectedMenuIds').val(selectedIds);
            }

            function updateButtonStates() {
                var selectedCount = $('.menu-checkbox:checked').length;
                var deleteButton = $('#deleteButton');
                var editButton = $('#editButton');

                if (selectedCount === 1) {
                    editButton.prop('disabled', false);
                    editButton.removeClass('btn-secondary').addClass('btn-warning');
                } else {
                    editButton.prop('disabled', true);
                    editButton.removeClass('btn-warning').addClass('btn-secondary');
                }

                if (selectedCount > 0) {
                    deleteButton.prop('disabled', false);
                    deleteButton.removeClass('btn-secondary').addClass('btn-danger');
                } else {
                    deleteButton.prop('disabled', true);
                    deleteButton.removeClass('btn-danger').addClass('btn-secondary');
                }
            }

            function updateMenuStatus(menuId, newStatus) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('update.menu.status') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'menu_id': menuId,
                        'new_status': newStatus
                    },
                    success: function(response) {
                        console.log(response);
                        alert('Status updated successfully');
                        location.reload();
                    },
                    error: function(error) {
                        console.error(error);
                        alert('Error updating status');
                    }
                });
            }

            $('#deleteButton').click(function() {
                var selectedMenuIds = $('#selectedMenuIds').val();

                if (selectedMenuIds) {
                    var selectedCount = selectedMenuIds.split(',').length;

                    var confirmMessage = selectedCount > 1 ?
                        'Apakah anda yakin ingin menghapus ' + selectedCount + ' menu?' :
                        'Apakah anda yakin ingin menghapus menu?';

                    if (confirm(confirmMessage)) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('delete.menu') }}',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'menu_ids': selectedMenuIds
                            },
                            success: function(response) {
                                console.log(response);
                                alert('Menu deleted successfully');
                                location.reload();
                            },
                            error: function(error) {
                                console.error(error);
                                alert('Error deleting menu');
                            }
                        });
                    }
                } else {
                    alert('Please select at least one menu to delete');
                }
            });

            $('#editButton').click(function() {
                var selectedMenuIds = $('#selectedMenuIds').val();

                if (selectedMenuIds) {
                    var selectedCount = selectedMenuIds.split(',').length;

                    if (selectedCount === 1) {
                        var editUrl = '{{ route('edit.menu', ':id') }}';
                        editUrl = editUrl.replace(':id', selectedMenuIds);
                        window.location.href = editUrl;
                    } else {
                        alert('Please select only one menu to edit');
                    }
                } else {
                    alert('Please select at least one menu to edit');
                }
            });
        });
    </script>
@endsection
