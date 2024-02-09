@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Edit Akun') }}</span>
                        <span><a href="{{ route('home') }}" class="btn btn-sm btn-danger">X</a></span>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('update.users') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nama User') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name', isset($user) ? $user->nama_user : '') }}" required
                                        autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="username"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                                <div class="col-md-6">
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username', isset($user) ? $user->username : '') }}" required
                                        autocomplete="username" autofocus>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Konfirmasi Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="level"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Pilih Level') }}</label>
                                <div class="col-md-6">
                                    <select id="level" class="form-control" name="level_id">
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id }}"
                                                {{ old('level_id', isset($user) ? $user->id_level : '') == $level->id ? 'selected' : '' }}>
                                                {{ $level->nama_level }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('level_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return confirm('Apakah anda yakin ingin mengedit data?');">
                                        {{ __('Edit Akun') }}
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
