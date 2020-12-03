@extends('master-layout')

@section('css')
    @parent

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.semanticui.min.css">
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Dashboard </h2>

    <div id="image">
		<div class="ui grid mt2 mx2">
			<div class="ui form sixteen wide column">
                <div class="field">
                    <label for="search">Enter Your Ticket Refernce Number</label>
                    <input type="text" placeholder="Ref No" name="search" id="search">
                </div>
            </div>

            <div class="ui sixteen wide column pt2 pb2">
                <button class="ui button btn-corporate right floated" id="show_ticket">Submit</button>
            </div>
		</div>
	</div>

    <div id="ticket"></div>
@endsection

@section('js')
    @parent

    <script>
	    var image_datatable = "";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.semanticui.min.js"></script>
    <script>
        $( '#show_ticket' ).click( function( e ) {
            e.preventDefault();

            let $form = $( ".ui.form" );
            let $meta = $( 'meta[name="csrf-token"]' );

            $form
                .form( {
                    fields: {
                        search: 'empty',
                    },
                    inline: true,
                    on    : 'blur'
                } );

            $form.form( 'validate form' );

            if( !$form.form( 'is valid' ) ) {
                return false;
            }

            $form.addClass( 'laoding' );

            axios( {
                method : 'GET',
                url    : window.routes.ticketData + '?' + "ref_id=" + $('#search').val(),
                headers: {
                    'X-CSRF-TOKEN': $meta.attr( 'content' ),
                    'Content-Type': 'application/json',
                    'Accept'      : 'text/html; charset=utf-8',
                },
            } ).then( ( response ) => {
                $('#ticket').html(response.data);
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
                $form.removeClass( 'laoding' );

                $form.form( 'clear' );
            } );

        } )

    </script>
@endsection
