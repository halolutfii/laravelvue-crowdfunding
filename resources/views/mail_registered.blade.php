<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body style="font-family: sans-serif;">
    <div style="display: block; margin: auto; max-width: 600px;" class="main">
      <h1 style="font-size: 18px; font-weight: bold; margin-top: 20px">Selamat bapak/ibu {{$name}} berhasil register!</h1>
      <p>Silahkan gunakan OTP code dibawah hingga 5 menit dari sekarang.</p>
      <p style="background-color :aquamarine; text-align:center; font-weight:bold;">{{$otp}}</p>
    </div>
    <!-- Example of invalid for email html/css, will be detected by Mailtrap: -->
    <style>
      .main { background-color: white; }
      a:hover { border-left-width: 1em; min-height: 2em; }
    </style>
  </body>
</html>
