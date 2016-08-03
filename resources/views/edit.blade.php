
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Email</div>

                <div class="panel-body">
                  <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                      @if(Session::has('alert-' . $msg))

                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                      @endif
                    @endforeach
                  </div>
                    <form method="POST" action="{{ route('editEmail') }}" role="form">

                    {!! csrf_field() !!}

                    <div class="form-group">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                    <div class="col-md-6">
                    @foreach($email as $data)
                      <input id="email" type="email" class="form-control" name="email" value="{{ $data->email }}">
                      <input type="hidden" value="{{ $data->id }}" name="id">
                    @endforeach
                    </div>
                  </div>

                    <div class="form-group">
                    <div class="col-md-6 col-md-offset-4" style="margin-top:30px">
                    <button type="submit" class="btn btn-primary">
                      Edit Email
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
