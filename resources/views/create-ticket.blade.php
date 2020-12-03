@extends('master-layout')

@section('css')
@parent

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.semanticui.min.css">
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Ticket Create </h2>

    <div class="ui breadcrumb">
	    <a class="section" href="{{route('ticket.show')}}"><i class="fa fa-home"></i></a>
	    <i class="right chevron icon divider"></i>
	    <div class="section">Ticket</div>
	    <i class="right chevron icon divider"></i>
        <div class="active section">Create</div>
	</div>

    <div id="image">
		<div class="ui container mt2">
			<div class="ui floating message">
			    <p> <i class="info circle icon"></i> Please fill all the details. Phone number should contain 9 digits without zero</p>
			</div>

			<form class="ui equal width form mt2" action="{{route('ticket.store')}}" method="POST">
                <div class="required field">
                    <label for="name">Customer Name</label>
                    <input type="text" name="name" placeholder="Name" id="name">
                </div>

                <div class="required field">
                    <label for="problem">Problem Description</label>
                    <textarea name="problem" id="problem" cols="30" rows="10"></textarea>
                </div>

                <div class="required field">
                    <label for="email">Email</label>
                    <input type="text" name="email" placeholder="Email" id="email">
                </div>

                <div class="required field">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" placeholder="Eg: 75xxxxxxx" id="phone">
                </div>
			</form>

			<div class="ui column pt2 pb2">
				<button class="ui button btn-corporate right floated" id="submit_ticket">Submit</button>
			</div>
		</div>
	</div>
@endsection

@section('js')
@parent

    <script>
	    var image_datatable = "";
    </script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.semanticui.min.js"></script>
    <script>
        $( '#submit_ticket' ).click( function( e ) {
            e.preventDefault();

            let $form = $( ".ui.form" );
            let $meta = $( 'meta[name="csrf-token"]' );

            $form
                .form( {
                    fields: {
                        name   : 'empty',
                        problem: 'empty',
                        email  : ['empty', 'email'],
                        phone  : ['empty', 'number', 'minLength[9]', 'maxLength[9]'],
                    },
                    inline: true,
                    on    : 'blur'
                } );

            $form.form( 'validate form' );

            if( !$form.form( 'is valid' ) ) {
                return false;
            }

            $form.addClass('laoding');

            axios( {
                method : 'POST',
                url    : $form.attr( 'action' ),
                data   : {customer_name: $('#name').val(), description: $('#problem').val(), email: $('#email').val(), phone: $('#phone').val()},
                headers: {
                    'X-CSRF-TOKEN': $meta.attr( 'content' ),
                    'Content-Type': 'application/json',
                    'Accept'      : 'application/json',
                },
            } ).then( ( response ) => {
                Toast.fire( {
                    icon : 'success',
                    title: response.data
                } );
            } ).catch( ( error ) => {
                if( error.response.status === 500 ) {
                    Toast.fire( {
                        icon : 'error',
                        title: "Ticket Submition failed."
                    } );

                    return;
                }

                Toast.fire( {
                    icon : 'error',
                    title: error.response.data
                } );
            } ).finally( () => {
                $form.removeClass('laoding');

                $form.form('clear');
            } );


        } )
    </script>
@endsection
