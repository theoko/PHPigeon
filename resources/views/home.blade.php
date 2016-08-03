
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">MAPP / mass-mail dashboard</div>

                <div class="panel-body">
                  <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                      @if(Session::has('alert-' . $msg))

                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                      @endif
                    @endforeach
                  </div>
                  @if($emails_count > 0)
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>EMAIL</th>
                          <th>ACTION</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($emails as $email)
                        <tr>
                          <th scope="row">{{ $email->id }}</th>
                          <td>{{ $email->email }}</td>
                          <td><a class="btn btn-info" href="/editEmail/{{ $email->id }}"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT</a>&nbsp;<a class="btn btn-info" href="/sendEmail/{{ $email->id }}"><i class="fa fa-envelope" aria-hidden="true"></i> SEND EMAIL</a>&nbsp;<a class="btn btn-danger" href="/deleteEmail/{{ $email->id }}"><i class="fa fa-trash" aria-hidden="true"></i> DELETE</a><div class="checkbox checkbox-primary"><input id="checkbox{{ $email->id }}" type="checkbox" class="email_checkbox"><label for="checkbox{{ $email->id }}">SELECT</label></div></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  {{ $emails->links() }}
                  <br>
                  <a href="{{ route('send_to_all') }}" class="btn btn-primary"><i class="fa fa-envelope" aria-hidden="true"></i> SEND AN EMAIL TO ALL</a>
                  <a href="//users.athensmapp.com/sendEmailToSelected/" class="btn btn-success" id="email_selected"><i class="fa fa-envelope" aria-hidden="true"></i> SEND AN EMAIL TO SELECTED</a>
                  @else
                    No emails currently exist. To add an email, navigate to <a href="{{ route('addEmail') }}">{{ route('addEmail') }}</a>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
      var ids = [];
      var count = 0;
      $('#email_selected').hide();
      $(".email_checkbox").click(function() {
        var id = $(this).attr('id');
        if($('#' + id).is(":checked")) {
            $('#email_selected').show();
            ids.push(id.replace('checkbox', ''));
            jQuery.each(ids, function(i, val) {
              if (i == 0) {
                $("#email_selected").attr("href", $("#email_selected").attr("href").replace(val, '') + val);
              } else {
                $("#email_selected").attr("href", $("#email_selected").attr("href").replace(val, '') + ':' + val);
              }
              count++;
            });
        } else {
          var removeItem = id.replace('checkbox', '');
          // console.log(removeItem);

          ids = jQuery.grep(ids, function(value) {
            return value != removeItem;
          });
          console.log(ids);
          $("#email_selected").attr("href", $("#email_selected").attr("href").replace(removeItem, ''));
        }
        var atLeastOneIsChecked = $('input[class="email_checkbox"]:checked').length;
        if (atLeastOneIsChecked == 0) {
          ids = [];
          $("#email_selected").hide();
        }
      });
  });
</script>
@endsection
