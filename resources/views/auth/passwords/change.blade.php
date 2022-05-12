@extends('layouts.inventory')

@section('content')
    <div class="container">

        <div class="">
            <h1 class="h1 text-bold text-primary">Change Password</h1>
        </div>
        <hr class="mb-5">

        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show my-2" role="alert">
                <strong class="h5">{{ Session::get('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
                <strong class="h5">{{ Session::get('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mx-auto mt-5" style="width: 50rem">
            <div class="card-header text-center">Change Password</div>

            <div class="card-body">
                <form method="POST" action="{{ url('/changePassword') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="oldPw" class="col-md-4 col-form-label text-md-end">Current Password:</label>

                        <div class="col-md-6">
                            <input id="oldPw" type="password" class="form-control @error('oldPw') is-invalid @enderror"
                                name="oldPw" value="{{ old('oldPw') }}" required autocomplete="oldPw" autofocus>

                            @error('oldPw')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="newPw" class="col-md-4 col-form-label text-md-end">New Password:</label>

                        <div class="col-md-6">
                            <input id="newPw" type="password" class="form-control @error('newPw') is-invalid @enderror"
                                name="newPw" value="{{ old('newPw') }}" required autocomplete="newPw" autofocus>

                            @error('newPw')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="cfmPw" class="col-md-4 col-form-label text-md-end">Confirm Password:</label>

                        <div class="col-md-6">
                            <input id="cfmPw" type="password" class="form-control @error('cfmPw') is-invalid @enderror"
                                name="cfmPw" value="{{ old('cfmPw') }}" required autocomplete="cfmPw" autofocus>

                            @error('cfmPw')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
