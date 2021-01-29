@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadowed profile" style="background: #ffffffe0">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="col-md-12 login-form-1">
                            <img class="mx-auto" style="text-align: center;display: block" src="{{ asset('images/onesyntax.svg') }}" />
                            <p style="text-align: center;" class="mt-3">
                               <b> Web Application developed for manage employees</b>
                            </p>
                            <div class="form-group">
                                <input type="text" class="form-control @error('email') is-invalid @enderror"" style=" font-size:15px" name="email" placeholder="E-mail Address" value="{{ old('email')}}" /> @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <div class="form-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" style="font-size:15px" name="password" placeholder="Password" value="{{ old('password') }}" />
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" style="text-align: right" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-11 ml-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login To System') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="information/">
                                    {{ __('or Contact Admin for login?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection