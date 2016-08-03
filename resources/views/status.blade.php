
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Mail-system status</div>

                <div class="panel-body">
                  <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                      @if(Session::has('alert-' . $msg))

                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                      @endif
                    @endforeach
                  </div>
                  <p class="alert alert-info">{{ $emails_count }} pending @if($emails_count == 1) email (e-mails are delivered every hour) @else emails (E-mails are delivered every hour) @endif</p>
                  @if($emails_count > 0)
                      <div class="table-responsive">
                        <table class="table">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>FROM</th>
                              <th>TO</th>
                              <th>SUBJECT</th>
                              <th>ACTION</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($emails as $email)
                            <tr>
                              <td>{{ $email->id }}</td>
                              <td>{{ $email->from }}</td>
                              <td>{{ $email->to }}</td>
                              <td>{{ $email->subject }}</td>
                              <td><a class="btn btn-danger" href="/deleteEmailFromPending/{{ $email->id }}"><i class="fa fa-trash" aria-hidden="true"></i> DELETE</a></td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
