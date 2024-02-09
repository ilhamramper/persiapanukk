@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Buat Order') }}</span>
                        <span><a href="{{ route('order') }}" class="btn btn-sm btn-danger">X</a></span>
                    </div>

                    <div class="card-body">
                        <form enctype="multipart/form-data" method="POST" action="{{ route('store.order') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="no_meja"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nomor Meja') }}</label>

                                <div class="col-md-6">
                                    <select id="no_meja" class="form-control @error('no_meja') is-invalid @enderror"
                                        name="no_meja" required autofocus>
                                        @foreach ($nomejas as $nomeja)
                                            <option value="{{ $nomeja->no_meja }}">{{ $nomeja->no_meja }}</option>
                                        @endforeach
                                    </select>

                                    @error('no_meja')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Buat Order') }}
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
