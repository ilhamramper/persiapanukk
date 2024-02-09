@extends('layouts.app')

@section('content')
    <div class="container" style="margin-bottom: 4%">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            @if (Auth::user()->id_level == 3)
                                <span>{{ __('Data Akun') }}</span>
                            @elseif(Auth::user()->id_level == 2)
                                <span>{{ __('Data Akun Pelanggan') }}</span>
                            @endif
                            <a href="{{ route('create.users') }}" class="btn btn-success">+ Buat Akun</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="akun" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Nama User</th>
                                    <th class="text-center">Level User</th>
                                    <th class="text-center">
                                        Pilih Semua
                                        <span style="padding-left: 10px;">
                                            <input type="checkbox" id="checkAll">
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->nama_user }}</td>
                                        <td>{{ $user->level->nama_level }}</td>
                                        <td>
                                            <input type="checkbox" class="user-checkbox" name="user_ids[]"
                                                value="{{ $user->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <input type="hidden" id="selectedUserIds" name="selectedUserIds" value="">
                        <button id="deleteButton" class="btn btn-secondary" disabled>Hapus Data</button>
                        <button id="editButton" class="btn btn-secondary" disabled>Edit Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#akun').DataTable();

            $('#checkAll').change(function() {
                $('.user-checkbox').prop('checked', $(this).prop('checked'));

                updateSelectedUserIds();
                updateButtonStates();
            });

            $('.user-checkbox').change(function() {
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }

                var allChecked = $('.user-checkbox:checked').length === $('.user-checkbox').length;

                $('#checkAll').prop('checked', allChecked);

                updateSelectedUserIds();
                updateButtonStates();
            });

            function updateSelectedUserIds() {
                var selectedIds = $('.user-checkbox:checked').map(function() {
                    return $(this).val();
                }).get().join(',');

                $('#selectedUserIds').val(selectedIds);
            }

            function updateButtonStates() {
                var selectedCount = $('.user-checkbox:checked').length;
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

            $('#deleteButton').click(function() {
                var selectedUserIds = $('#selectedUserIds').val();

                if (selectedUserIds) {
                    var selectedCount = selectedUserIds.split(',').length;

                    var confirmMessage = selectedCount > 1 ?
                        'Apakah anda yakin ingin menghapus ' + selectedCount + ' data?' :
                        'Apakah anda yakin ingin menghapus data?';

                    if (confirm(confirmMessage)) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('delete.users') }}',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'user_ids': selectedUserIds
                            },
                            success: function(response) {
                                console.log(response);
                                alert('Users deleted successfully');
                                location.reload();
                            },
                            error: function(error) {
                                console.error(error);
                                alert('Error deleting users');
                            }
                        });
                    }
                } else {
                    alert('Please select at least one user to delete');
                }
            });

            $('#editButton').click(function() {
                var selectedUserIds = $('#selectedUserIds').val();

                if (selectedUserIds) {
                    var selectedCount = selectedUserIds.split(',').length;

                    if (selectedCount === 1) {
                        var editUrl = '{{ route('edit.users', ':id') }}';
                        editUrl = editUrl.replace(':id', selectedUserIds);
                        window.location.href = editUrl;
                    } else {
                        alert('Please select only one user to edit');
                    }
                } else {
                    alert('Please select at least one user to edit');
                }
            });
        });
    </script>
@endsection
