<!-- Stored in resources/views/layouts/master.blade.php-->

<html>
<head>
    <title>App Name - @yield('title')</title>
</head>
<body>
111111111111
@section('sidebar')
    <a>This is the master sidebar.</a>
@show
222222222
<div class="container">
    >>>>>@yield('content')<<<<<<
</div>
</body>
</html>