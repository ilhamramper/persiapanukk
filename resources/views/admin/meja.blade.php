@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ __('Data Meja') }}</span>
                                <a href="{{ route('create.meja') }}" class="btn btn-success">+ Tambah Meja</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="meja" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">No. Meja</th>
                                    <th class="text-center">Status Meja</th>
                                    <th class="text-center">
                                        Pilih Semua
                                        <span style="padding-left: 10px;">
                                            <input type="checkbox" id="checkAll">
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mejas as $meja)
                                    <tr>
                                        <td>{{ $meja->no_meja }}</td>
                                        <td>{{ $meja->status }}</td>
                                        <td>
                                            <input type="checkbox" class="meja-checkbox" name="meja_ids[]"
                                                value="{{ $meja->no_meja }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <input type="hidden" id="selectedMejaIds" name="selectedMejaIds" value="">
                        <button id="deleteButton" class="btn btn-secondary" disabled>Hapus Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#meja').DataTable();

            $('#checkAll').change(function() {
                $('.meja-checkbox').prop('checked', $(this).prop('checked'));

                updateSelectedMejaIds();
                updateButtonStates();
            });

            $('.meja-checkbox').change(function() {
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }

                var allChecked = $('.meja-checkbox:checked').length === $('.meja-checkbox').length;

                $('#checkAll').prop('checked', allChecked);

                updateSelectedMejaIds();
                updateButtonStates();
            });

            function updateSelectedMejaIds() {
                var selectedIds = $('.meja-checkbox:checked').map(function() {
                    return $(this).val();
                }).get().join(',');

                $('#selectedMejaIds').val(selectedIds);
            }

            function updateButtonStates() {
                var selectedCount = $('.meja-checkbox:checked').length;
                var deleteButton = $('#deleteButton');

                if (selectedCount > 0) {
                    deleteButton.prop('disabled', false);
                    deleteButton.removeClass('btn-secondary').addClass('btn-danger');
                } else {
                    deleteButton.prop('disabled', true);
                    deleteButton.removeClass('btn-danger').addClass('btn-secondary');
                }
            }

            $('#deleteButton').click(function() {
                var selectedMejaIds = $('#selectedMejaIds').val();

                if (selectedMejaIds) {
                    var selectedCount = selectedMejaIds.split(',').length;

                    var confirmMessage = selectedCount > 1 ?
                        'Apakah anda yakin ingin menghapus ' + selectedCount + ' nomor meja?' :
                        'Apakah anda yakin ingin menghapus nomor meja?';

                    if (confirm(confirmMessage)) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('delete.meja') }}',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'meja_ids': selectedMejaIds
                            },
                            success: function(response) {
                                console.log(response);
                                alert('Data meja deleted successfully');
                                location.reload();
                            },
                            error: function(error) {
                                console.error(error);
                                alert('Error deleting data meja');
                            }
                        });
                    }
                } else {
                    alert('Please select at least one data meja to delete');
                }
            });
        });
    </script>
@endsection
