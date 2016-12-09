
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
  <br><br>
  <!-- @if ($errors->has('email'))
      <span class="help-block">
          <strong>{{ $errors->first('email') }}</strong>
      </span> -->
  <!-- @endif -->
  <form class="login-form" method="POST" action="{{ url('/login') }}" style="margin-top: 50px">
    <center><img src="{{ asset('images/logo/logo.png')}}" alt="" style="width: 200px" align="center" /></center>
    <br>

    @if($errors)
     @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
      @endforeach
    @endif
    {{ csrf_field() }}
    <input type="text" id="name" placeholder="Username" name="username"/>
    <input type="password" id="password" placeholder="Password" name="password"/>
    <button type="submit">Login</button>
  </form>
</body>
</html>
