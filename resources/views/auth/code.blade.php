@extends('front.layout.app')

@section('main')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Inserisci il codice di sicurezza') }}</div>

                <div class="card-body">

                  <p>Abbiamo inviato il codice all'indirizzo: <strong>{{ $email }}</strong> </p>

                    <form method="POST" action="{{ route('sCode', ['email' => $email]) }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Codice di sicurezza') }}</label>

                            <div class="col-md-8">
                                <input id="email" type="text" class="form-control{{ $errors->has('sCode') ? ' is-invalid' : '' }}" name="sCode" value="{{ old('sAuth') }}" required autofocus>

                                @if ($errors->has('sCode'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('sCode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Invia codice') }}
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
