@extends('master-layout')

@section('css')
    @parent
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Ticket - {{$ticket->reference_no}} </h2>

    <div id="image">
        <div class="ui grid mx2 segment segment-hover">
            <div class="ui form sixteen wide column">
                <div class="inline fields">
                    <label for="search">Customer Name: </label>
                    <p>{{$ticket->customer_name}}</p>
                </div>

                <div class="inline fields">
                    <label for="search">Description: </label>
                    <p>{{$ticket->description}}</p>
                </div>

                <div class="inline fields">
                    <label for="search">Email: </label>
                    <p>{{$ticket->email}}</p>
                </div>

                <div class="inline fields">
                    <label for="search">Phone: </label>
                    <p>{{$ticket->phone}}</p>
                </div>

                <div class="inline fields">
                    <label for="search">Agent Reply: </label>
                    <p>{{$ticket->reply->reply}}</p>
                </div>
            </div>
        </div>

		<div class="ui grid mt2 mx2">
			<div class="ui sixteen wide column">
                <form class="ui form equal width form mt2" action="{{route('ticket.update.agent', ['id' => $ticket->id])}}" id="form">
                    <div class="field">
                        <label for="reply">Agent Reply</label>
                        <input type="text" placeholder="Reply" name="reply" id="reply">
                        <input type="hidden" name="agent_id" id="agent_id" value="1">
                    </div>
                </form>
            </div>

            <div class="ui sixteen wide column pt2 pb2">
                <button class="ui button btn-corporate right floated" id="submit_reply">Submit</button>
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
    <script>
        $( '#submit_reply' ).click( function( e ) {
            e.preventDefault();

            let $form = $( "#form" );
            let $meta = $( 'meta[name="csrf-token"]' );

            $form
                .form( {
                    fields: {
                        reply   : 'empty',
                    },
                    inline: true,
                    on    : 'blur'
                } );

            $form.form( 'validate form' );

            if( !$form.form( 'is valid' ) ) {
                return false;
            }

            $form.addClass('loading');

            axios( {
                method : 'POST',
                url    : $form.attr( 'action' ),
                data   : {reply: $('#reply').val(), agent_id: $('#agent_id').val() },
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
                $form.removeClass('loading');

                $form.form('clear');
            } );


        } )

    </script>
@endsection
