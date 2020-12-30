@extends('master-layout')

@section('css')
    @parent

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.semanticui.min.css">
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Form Preview </h2>

    <div class="ui breadcrumb">
	    <a class="section" href="{{route('field.list')}}"><i class="fa fa-home"></i></a>
	    <i class="right chevron icon divider"></i>
	    <div class="section">Forms</div>
	</div>

    <div id="image">
		<div class="ui container mt2">
            <h3 class="ui header">{{ucfirst($form->name)}}</h3>
			<form class="ui form mt2">
                @foreach($fields as $key => $field)
                    @if($field->type === 'combobox')
                        <div class="field @if($field->mandatory) required @endif">
                            <label for="{{strtolower($field->label)}}">{{$field->label}}</label>
                             <div class="ui search selection dropdown">
                                <input type="hidden" name="{{strtolower($field->label)}}" id="{{strtolower($field->label)}}">
                                <i class="dropdown icon"></i>
                                <div class="default text">Select</div>
                                 <div class="menu">
                                     @foreach($field->list as $list)
                                         <div class="item" data-value="{{strtolower($list)}}">{{$list}}</div>
                                     @endforeach
                                 </div>
                            </div>
                        </div>
                    @elseif($field->type === 'text')
                        <div class="@if($field->mandatory) required @endif field">
                            <label for="{{strtolower($field->label)}}">{{$field->label}}</label>
                            <input type="text" name="{{strtolower($field->label)}}" id="{{strtolower($field->label)}}">
                        </div>
                    @elseif($field->type === 'textarea')
                        <div class="@if($field->mandatory) required @endif field">
                            <label for="{{strtolower($field->label)}}">{{$field->label}}</label>
                            <textarea name="{{strtolower($field->label)}}" id="{{strtolower($field->label)}}"></textarea>
                        </div>
                    @elseif($field->type === 'date')
                        <div class="@if($field->mandatory) required @endif field">
                            <label for="{{strtolower($field->label)}}">{{$field->label}}</label>
                            <input type="date" name="{{strtolower($field->label)}}" id="{{strtolower($field->label)}}">
                        </div>
                    @elseif($field->type === 'radio')
                        <div class="inline fields">
                            <label for="{{strtolower($field->label)}}">{{$field->label}}: </label>
                            @foreach($field->list as $list)
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="{{strtolower($field->label)}}" tabindex="0" class="hidden">
                                        <label>{{$list}}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
			</form>

			<div class="ui container pt2 pb2">
                <button class="ui button btn-corporate right floated">Cancel</button>
                <button class="ui button btn-corporate right floated">Submit</button>
            </div>
		</div>
	</div>

    <div id="ticket"></div>
@endsection

@section('js')
    @parent
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
                        title: "Field Submition failed."
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
