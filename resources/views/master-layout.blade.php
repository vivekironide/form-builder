<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', ' PickMe Corporate Solution')</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="format-detection" content="telephone=no">
	<meta name="csrf-token" content="{{csrf_token()}}">
    @section('css')
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
        <link rel="stylesheet" href="{{asset('css/main.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    @show
</head>

<body>
	<div class="ui sidebar vertical left menu borderless inverted-corporate visible">
		<a href="https://pickme.lk" class="item logo">
			<img src="{{asset('images/logo.jpg')}}" alt="Online Support" class="ui image centered tiny">
		</a>

		<a class="title" href="{{route('field.list')}}">
			<i class="icon home"></i>
			Dashboard
		</a>

        <div class="ui accordion inverted-corporate">
			<div class="title">
                <i class="archive icon"></i>
                Field
                <i class="icon dropdown"></i>
            </div>

            <div class="content">
                <a href="{{route('field.list')}}" class="item transition">List</a>
                <a href="{{route('field.create')}}" class="item transition">Add</a>
            </div>
		</div>

        <div class="ui accordion inverted-corporate">
			<div class="title">
                <i class="archive icon"></i>
                Form
                <i class="icon dropdown"></i>
            </div>

            <div class="content">
                <a href="{{route('form.builder.index')}}" class="item transition">List</a>
                <a href="{{route('form.builder')}}" class="item transition">Builder</a>
            </div>
		</div>
	</div>

	<div class="pusher">
		<div class="ui top menu inverted-corporate borderless">
			<div class="item sandwich" style="cursor: pointer">
				<i class="fa fa-bars fa-lg" aria-hidden="true"></i>
			</div>


			<div class="right menu">
                 @auth
                    <div class="ui dropdown item">
                         Welcome {{ auth()->user()->name }}
                        <i class="dropdown icon" style="padding-top: 3px"></i>
                        <div class="menu">
                            <a class="item" href="{{route('logout')}}">
                                <i class="fa fa-sign-out fa-lg"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="ui item">
                        <a href="{{route('login')}}">Login</a>
                    </div>
                @endguest

			</div>
		</div>

        @yield('content')


	</div>

    @section('js')
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script src="{{asset('js/main.js') . '?' . filemtime(public_path('js/main.js'))}}"></script>
        <script>
			var routes = {
                fieldDatatable: "{{ route('field.datatable') }}",
                formDatatable: "{{ route('form.datatable') }}",
                ticketData: "{{ route('field.show') }}"
            };

            var Toast = Swal.mixin( {
                toast            : true,
                position         : 'top-end',
                showConfirmButton: false,
                timer            : 3000,
                timerProgressBar : true,
                onOpen           : ( toast ) => {
                    toast.addEventListener( 'mouseenter', Swal.stopTimer );
                    toast.addEventListener( 'mouseleave', Swal.resumeTimer );
                },
                padding          : '1rem'
            } );

            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
		</script>
    @show
</body>
</html>


