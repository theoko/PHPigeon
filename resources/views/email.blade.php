
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Add Email</div>

                <div class="panel-body">
                  <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                      @if(Session::has('alert-' . $msg))

                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                      @endif
                    @endforeach
                  </div>
                  <h4 class="alert alert-primary">Tip: You can insert multiple emails in this format: email1, email2, email3, email4, email5</h4>
                  <form method="POST" action="{{ route('addEmail') }}" role="form">
                    {!! csrf_field() !!}

                    <div class="form-group">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                    <div class="col-md-6">
                    <input id="email" type="text" class="form-control" name="email" value="" required>
                    </div>
                  </div>

                    <div class="form-group">
                    <div class="col-md-6 col-md-offset-4" style="margin-top:30px">
                    <button type="submit" class="btn btn-primary">
                      Add Email
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
