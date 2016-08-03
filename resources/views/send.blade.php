
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Send Email</div>

                <div class="panel-body">
                  <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                      @if(Session::has('alert-' . $msg))

                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                      @endif
                    @endforeach
                  </div>
                    <form method="POST" action="{{ route('sendEmail') }}" role="form">

                    {!! csrf_field() !!}

                    <div class="form-group">
                      <label for="select_from" class="col-md-5 control-label">From</label>
                      <select class="form-control" id="select_from" style="width:40%" name="from">
                        <option>support@athensmapp.com</option>
                        <option>info@athensmapp.com</option>
                        <option>sales@athensmapp.com</option>
                      </select>
                    </div>

                    <div class="form-group">
                    <label for="email" class="col-md-4 control-label">To</label>
                    <div class="col-md-6">
                    @foreach($email as $data)
                      <input id="email" type="email" class="form-control" name="email" value="{{ $data->email }}" required>
                      <input type="hidden" value="{{ $data->id }}" name="id">
                    @endforeach
                    </div>
                  </div>

                  <div class="form-group">
                  <label for="email" class="col-md-4 control-label" style="margin-top:10px">Subject</label>
                  <div class="col-md-6">
                    <input id="subject" type="text" class="form-control" name="subject" placeholder="Enter a subject..." style="margin-top:10px" required>
                  </div>
                </div>

                  <div class="form-group">
                  <label for="body" class="col-md-4 control-label" style="margin-top:10px">Body</label>
                  <div class="col-md-6">
                    <textarea id="textarea" class="form-control" name="body" style="margin-top:10px" placeholder="Enter body or HTML..." required></textarea>
                  </div>
                </div>

                    <div class="form-group">
                    <div class="col-md-6 col-md-offset-4" style="margin-top:30px">
                    <button type="submit" class="btn btn-primary">
                      Send Email
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
