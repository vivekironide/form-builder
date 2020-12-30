@extends('master-layout')

@section('css')
    @parent

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.semanticui.min.css">
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Login </h2>

    <div id="image">
		<div class="ui container mt2">
            @error('msg')
                <div class="ui floating message">
                    <p> <i class="info circle icon"></i> {{$message}}</p>
                </div>
            @enderror
			<form class="ui equal width form mt2" action="{{route('logged')}}" method="POST">
                @csrf
                <div class="required field">
                    <label for="email">Email</label>
                    <input type="text" name="email" placeholder="Email" id="email">
                </div>

                <div class="required field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                </div>

                <div class="required field pt2">
                    <button class="ui button btn-corporate right floated" id="submit_ticket" type="submit">Log IN</button>
                </div>
			</form>
		</div>
	</div>

    <div id="ticket"></div>
@endsection

@section('js')
    @parent
@endsection
