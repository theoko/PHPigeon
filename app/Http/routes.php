<?php

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

// ADD EMAIL FOR MASS-EMAIL

Route::get('systemStatus', [
  'uses' => 'HomeController@status',
  'middleware' => 'auth',
  'as' => 'status',
]);

Route::get('deleteEmailFromPending/{id}', [
  'uses' => 'HomeController@delete_pending',
  'middleware' => 'auth',
]);

Route::get('sendEmailToSelected/{ids}', [
  'uses' => 'HomeController@selected',
  'middleware' => 'auth',
]);

Route::post('sendEmailToSelected', [
  'uses' => 'HomeController@selected_send',
  'middleware' => 'auth',
  'as' => 'selected',
]);

Route::get('sendEmailToAll', [
  'uses' => 'HomeController@send_email_to_all',
  'middleware' => 'auth',
  'as' => 'send_to_all',
]);

Route::post('sendEmailToAll', [
  'uses' => 'HomeController@send_email_to_all',
  'middleware' => 'auth',
]);

Route::get('addEmail', [
  'uses' => 'HomeController@add_email_view',
  'middleware' => 'auth',
  'as' => 'addEmail',
]);

Route::post('addEmail', [
  'uses' => 'HomeController@add_email',
  'middleware' => 'auth',
]);

Route::post('editEmail', [
  'uses' => 'HomeController@save_email',
  'middleware' => 'auth',
  'as' => 'editEmail',
]);

Route::post('sendEmail', [
  'uses' => 'HomeController@send',
  'middleware' => 'auth',
  'as' => 'sendEmail',
]);

Route::get('sendEmail/{id}', [
  'uses' => 'HomeController@send_email',
  'middleware' => 'auth',
]);

Route::get('editEmail/{id}', [
  'uses' => 'HomeController@edit_email',
  'middleware' => 'auth',
]);

Route::get('deleteEmail/{id}', [
  'uses' => 'HomeController@delete_email',
  'middleware' => 'auth',
]);

Route::get('/home', [
  'uses' => 'HomeController@index',
  'as' => 'home',
]);

// TURN OFF REGISTRATION

// Route::get('register', function() {
//   return redirect()->to('/login');
// });
//
// Route::post('register', function() {
//   return redirect()->to('/login');
// });
