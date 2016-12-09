
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Halaman Login</title>


  <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300'>
  <link rel='stylesheet prefetch' href='{{ asset('css/font-awesome.min.css') }}'>
  <link rel='stylesheet prefetch' href='{{ asset('css/bootstrap.min.css') }}'>

  <link rel="stylesheet" href="css/style.css">

</head>

<body>
  <div style="padding-top: 80px">
    <div class="header">
      <h1 style="text-align: center; color: white">Bengkel</h1>
    </div>
  </div>
  <form class="login-form" method="POST" action="{{ url('/login') }}">
    <label for="name">Email:</label>
    @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
    <input type="text" id="name" name="email"/>
    <label for="password">Password:</label>
    @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
    <input type="password" id="password" name="password"/>
    <button type="submit">Login</button>
  </form>
</body>
</html>
