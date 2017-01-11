<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Traits\EmailTrait;
use App\Jobs\SendEmail;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use Auth;

use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = DB::table('emails')->select('*');
        $emails_count = $emails->count();
        $emails = $emails->paginate(10);

        return view('home', ['emails' => $emails, 'emails_count' => $emails_count]);
    }

    public function status() {
      $emails = DB::table('send')->select('*');
      $emails_count = $emails->count();
      $emails = $emails->get();

      return view('status', ['emails' => $emails, 'emails_count' => $emails_count]);
    }

    public function send_email_to_all(Request $request) {
      $from = Input::get('from');
      $subject = Input::get('subject');
      $body = Input::get('body');

      if(empty(trim($body)) || empty(trim($from)) || empty(trim($subject))) {
        return view('all');
      }

      $emails = DB::table('emails')->select('*');
      $emails_count = $emails->count();
      $emails = $emails->get();

      foreach($emails as $email) {
        DB::table('send')->insert(
          array('from' => $from, 'to' => $email->email, 'subject' => $subject,'body' => $body)
        );

        $job = new SendEmail($email->email, $body);
        $this->dispatch($job);
      }

      $request->session()->flash('alert-success', 'Email sent to '.$emails_count.' emails with subject: '.$subject.'!');
      return redirect()->route('home');
    }

    public function selected(Request $request, $ids) {
      if(!isset($ids)) {
        $request->session()->flash('alert-warning', 'Select an email first!');
        return redirect()->route('home');
      }
      $emails = array();
      $ids_array = array_filter(explode(':', $ids));
      $count = 0;
      foreach($ids_array as $id) {
        $email = DB::table('emails')->select('*')->where('id', '=', $id)->first();
        array_push($emails, $email);
        $count++;
      }
      $count_emails = 0;
      $value = '';
      foreach($emails as $email) {
        $count_emails++;
        if ($count_emails == $count) {
          $value .= $email->email;
        } else {
          $value .= $email->email.", ";
        }
      }
      return view('selected', ['emails' => $value]);
    }

    public function selected_send(Request $request) {
      $from = Input::get('from');
      $to = Input::get('email');
      $subject = Input::get('subject');
      $body = Input::get('body');

      if(empty(trim($to)) || empty(trim($body)) || empty(trim($from)) || empty(trim($subject))) {
        $request->session()->flash('alert-danger', 'Email or body is empty!');
        return redirect()->route('home');
      }

      if(str_contains($to, ', ')) {
        $emails_array = explode(', ', $to);
        foreach($emails_array as $email) {
          $emails = DB::table('emails')->select('*')->where('email', '=', $email)->first();
          if(!$emails) {
            $request->session()->flash('alert-danger', 'Email '.$email.' does not exist in the database!');
            return redirect()->route('selected');
          }
            DB::table('send')->insert(
              array('from' => $from, 'to' => $email, 'subject' => $subject,'body' => $body)
            );

            $job = new SendEmail($email, $body);
            $this->dispatch($job);
          }
      } else {
        $email = DB::table('emails')->select('*')->where('email', '=', $to)->first();
        if(!$email) {
          $request->session()->flash('alert-danger', 'Email '.$to.' does not exist in the database!');
          return redirect()->route('selected');
        }
        DB::table('send')->insert(
          array('from' => $from, 'to' => $to, 'subject' => $subject, 'body' => $body)
        );

        $job = new SendEmail($to, $body);
        $this->dispatch($job);
      }

      $request->session()->flash('alert-success', 'Emails sent!');
      return redirect()->route('home');
    }

    public function add_email_view() {
      return view('email');
    }

    public function add_email(Request $request) {
      $email = Input::get('email');

      if(empty(trim($email))) {
        $request->session()->flash('alert-warning', 'Email is empty!');
        return redirect()->route('addEmail');
      }

      $exists = 0;
      $count = 0;

      if(str_contains($email, ', ')) {
        $emails_array = explode(', ', $email);
        foreach($emails_array as $email) {
          $check = DB::table('emails')->select('*')->where('email', '=', $email)->first();
          if($check) {
            $exists++;
          } else {
              DB::table('emails')->insert(
                array('email' => $email)
              );
          }
          $count++;
        }

        if ($count == $exists){
          $request->session()->flash('alert-success', 'All emails inserted already exist in the database!');
          return redirect()->route('addEmail');
        } else {
          if($exists > 0) {
            $request->session()->flash('alert-success', 'From the emails inserted, '.$exists.' already exist. The rest of them were added successfully!');
          } else {
            $request->session()->flash('alert-success', $count.' emails were added!');
          }
          return redirect()->route('addEmail');
        }

      } else {

        $check = DB::table('emails')->select('*')->where('email', '=', $email)->first();

        if($check) {
          $request->session()->flash('alert-danger', 'Email already exists!');
          return redirect()->route('addEmail');
        }

        DB::table('emails')->insert(
          array('email' => $email)
        );

        $request->session()->flash('alert-success', 'Email was successful added!');
        return redirect()->route('addEmail');
      }
    }

    public function send_email(Request $request, $id) {
      $email = DB::table('emails')->select('*')->where('id', '=', $id)->get();
      if(!$email) {
        $request->session()->flash('alert-danger', 'Email does not exist!');
        return redirect()->route('home');
      }

      return view('send', ['email' => $email]);
    }

    public function send(Request $request) {
      $from = Input::get('from');
      $to = Input::get('email');
      $subject = Input::get('subject');
      $body = Input::get('body');

      if(empty(trim($to)) || empty(trim($body)) || empty(trim($from)) || empty(trim($subject))) {
        $request->session()->flash('alert-danger', 'Email or body is empty!');
        return redirect()->route('home');
      }

      DB::table('send')->insert(
        array('from' => $from, 'to' => $to, 'subject' => $subject, 'body' => $body)
      );

      $job = new SendEmail($to, $body);
      $this->dispatch($job);

      $request->session()->flash('alert-success', 'Email sent to: '.$to.' from '.$from.'. It will be delivered in the next 6 hours!');
      return redirect()->route('home');
    }

    public function edit_email(Request $request, $id) {
      $email = DB::table('emails')->select('*')->where('id', '=', $id)->get();
      if(!$email) {
        $request->session()->flash('alert-danger', 'Email does not exist!');
        return redirect()->route('home');
      }

      return view('edit', ['email' => $email]);
    }

    public function save_email(Request $request) {
      $id = Input::get('id');
      $new_email = Input::get('email');

      if(empty(trim($id)) || empty(trim($new_email))) {
        $request->session()->flash('alert-danger', 'Email is empty!');
        return redirect()->route('home');
      }

      DB::table('emails')
            ->where('id', $id)
            ->update(['email' => $new_email]);

      $request->session()->flash('alert-success', 'Email updated to: '.$new_email.'!');
      return redirect()->route('home');
    }

    public function delete_email(Request $request, $id) {
      $check = DB::table('emails')->select('*')->where('id', '=', $id)->first();
      if($check) {
        $delete = DB::table('emails')->where('id', '=', $id)->delete();

        if($delete) {
          $request->session()->flash('alert-success', 'Email was deleted!');
        } else {
          $request->session()->flash('alert-success', 'There was an error!');
        }

        return redirect()->route('home');
      } else {
        $request->session()->flash('alert-danger', 'Email does not exist in the database and therefore cannot be deleted!');
        return redirect()->route('home');
      }
    }

    public function delete_pending(Request $request, $id) {
      $check = DB::table('send')->select('*')->where('id', '=', $id)->first();
      if($check) {
        $delete = DB::table('send')->where('id', '=', $id)->delete();

        if($delete) {
          $request->session()->flash('alert-success', 'Email was deleted!');
        } else {
          $request->session()->flash('alert-success', 'There was an error!');
        }
        return redirect()->route('status');
      } else {
        $request->session()->flash('alert-danger', 'Email does not exist in the database and therefore cannot be deleted!');
        return redirect()->route('status');
      }
    }
}
