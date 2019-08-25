@extends('front.layout.app')

@section('main')
  <div class="publisher-home">
    <div class="publisher-body">
      <h1>Eliminazione Account</h1>

      <form method="POST" action="{{ url('account_delete') }}">
        @csrf
        <h3>@lang('label.deleting_notice', ['days' => '30'])</h3>

        <div class="row">

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">Contenuto</div>
              <div class="card-body">
                <div class="d-flex">
                  <p>@lang('label.deleting_info', ['articles' => $articoli->count(), 'comments' => $feedback->count()])</p>
                  <div class="float-right pl-5">
                    <i class="fas fa-box fa-5x"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

          <div class="group-row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-danger">@lang('button.delete_account')</button>
            </div>
          </div>
        </div>

      </form>

    </div>
  </div>
@endsection
