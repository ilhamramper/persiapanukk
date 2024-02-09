@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Buat Menu') }}</span>
                        <span><a href="{{ route('menu') }}" class="btn btn-sm btn-danger">X</a></span>
                    </div>

                    <div class="card-body">
                        <form enctype="multipart/form-data" method="POST" action="{{ route('store.menu') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="image"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Gambar Masakan') }}</label>

                                <div class="col-md-6">
                                    <input class="form-control" type="file" id="image" name="image">

                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="jmasakan"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Pilih Jenis Masakan') }}</label>

                                <div class="col-md-6">
                                    <select id="jmasakan" class="form-control" name="jmasakan">
                                        @foreach ($jmasakans as $jmasakan)
                                            <option value="{{ $jmasakan->id }}">{{ $jmasakan->jenis_masakan }}</option>
                                        @endforeach
                                    </select>

                                    @error('jmasakan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nama Masakan') }}</label>

                                <div class="col-md-6">
                                    <input id="nama" type="text"
                                        class="form-control @error('nama') is-invalid @enderror" name="nama"
                                        value="{{ old('nama') }}" required>

                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="harga"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Harga Masakan') }}</label>

                                <div class="col-md-6">
                                    <input id="harga" type="number"
                                        class="form-control @error('harga') is-invalid @enderror" name="harga"
                                        value="{{ old('harga') }}" required>

                                    @error('harga')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Buat Menu') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
