
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Halaman Login</title>
  <?php $toko = App\Toko::all()->first(); ?>

  <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300'>
  <link rel='stylesheet prefetch' href='{{ asset('css/font-awesome.min.css') }}'>
  <link rel='stylesheet prefetch' href='{{ asset('css/bootstrap.min.css') }}'>

  <link rel="stylesheet" href="css/login.css">

</head>

<body background="{{ asset('images/login/'.$toko->halaman_login) }}">
  <br><br>
  <!-- @if ($errors->has('email'))
      <span class="help-block">
          <strong>{{ $errors->first('email') }}</strong>
      </span> -->
  <!-- @endif -->
  <form class="login-form" method="POST" action="{{ url('/login') }}" style="margin-top: 50px">
    <center><img src="{{ asset('images/login/'.$toko->login_logo) }}" alt="" style="height :150px ;width: 200px" align="center"; /></center>
    <br>

    @if($errors)
     @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
      @endforeach
    @endif
    {{ csrf_field() }}
    <input type="text" id="name" placeholder="Username" name="username" value="{{ old('username') }}" />
    <input type="password" id="password" placeholder="Password" name="password"/>
    <button type="submit">Login</button>
  </form>
</body>
</html>
