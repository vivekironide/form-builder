@extends('master-layout')

@section('css')
    @parent
@endsection

@section('content')
    <h2 class="ui header header-style" data-url="{{route('form.builder.index')}}"><i class="fa fa-home"></i> Form Builder </h2>

    <div class="ui floating message container">
        <p> <i class="info circle icon"></i> Click the field in form building area to remove the field</p>
    </div>

    <div id="form_builder_details">
        <div class="ui container mt4">
            <form class="ui form row" action="{{route('form.builder.store')}}" method="POST" id="form">
                <div class="required field">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name">
                </div>
			</form>
        </div>
	</div>

    <div id="form_builder">
        <div class="ui grid container mt4">
            <div class="five wide column" style="border-right: 1px solid #d8d5d5;">
                <h3 class="ui header">All Fields </h3>
                <div class="ui grid" style="max-height: 600px; overflow-y: auto">
                    <div class="fourteen wide column">
                        @foreach($fields as $key => $field)
                            <div class="ui raised segment form-field drag" id="drag_{{$key}}" draggable="true" ondragstart="drag(event)" style="cursor: pointer">
                              <p data-id="{{$field->id}}">{{$field->label}} ({{$field->type}})</p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="ui ten wide column centered">
                <h3 class="ui header">Form building </h3>
                <div class="ui grid" >
                    <div class="sixteen wide column form-fields" id="drop" style="border: 1px solid #d8d5d5; height: 400px; overflow-y: auto" ondrop="drop(event)"
                         ondragover="allowDrop(event)"></div>
                </div>
            </div>
        </div>
	</div>

    <div class="ui container pt2 pb2">
        <button class="ui button btn-corporate right floated">Cancel</button>
        <button class="ui button btn-corporate right floated" id="submit_form_builder">Submit</button>
    </div>
@endsection

@section('js')
    @parent

    <script>
	    var image_datatable = "";
    </script>
    <script>
        function allowDrop(ev)
        {
            ev.preventDefault();
        }


        function drag(ev)
        {
            ev.dataTransfer.setData("text", ev.target.id);
        }


        function drop(ev)
        {
            ev.preventDefault();

            let data = ev.dataTransfer.getData("text");

            document.getElementById(data).classList.add('builder-field')

            document.getElementById(data).classList.remove('form-field')

            console.log(ev);

            $('#' + ev.target.id).append(document.getElementById(data).outerHTML);

            document.getElementById(data).outerHTML = '';
        }

        $(document).on('click', '.form-field', function() {
            $(this).removeClass('form-field').addClass('builder-field');
            let field = $(this)[0].outerHTML;

            $('.sixteen.wide.column.form-fields').append(field);
            $(this).remove();
        });

        $(document).on('click', '.builder-field', function() {
            $(this).removeClass('builder-field').addClass('form-field');
            let field = $(this)[0].outerHTML;

            $('.fourteen.wide.column').append(field);
            $(this).remove();
        });

        $( '#submit_form_builder' ).click( function( e ) {
            e.preventDefault();
            $(this).prop('disabled', true);

            let $form = $( "#form" );
            let $meta = $( 'meta[name="csrf-token"]' );

            $form
                .form( {
                    fields: {
                        name   : 'empty',
                    },
                    inline: true,
                    on    : 'blur'
                } );

            let fields = $('.sixteen.wide.column.form-fields').find('p').map(function() {
                return this.attributes[0].value;
            }).get()

            $form.form( 'validate form' );

            if( !$form.form( 'is valid' ) ) {
                $(this).prop('disabled', false);
                return false;
            }

            $form.addClass('loading');

            axios( {
                method : 'POST',
                url    : $form.attr( 'action' ),
                data   : {name: $('#name').val(), fields: fields },
                headers: {
                    'X-CSRF-TOKEN': $meta.attr( 'content' ),
                    'Content-Type': 'application/json',
                    'Accept'      : 'application/json',
                },
            } ).then( ( response ) => {
                Toast.fire( {
                    icon : 'success',
                    title: response.data
                } ).then(function() {
                    location.href = $('.ui.header.header-style').attr('data-url');
                });;
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
                $form.removeClass('loading');
            } );


        } )

    </script>
@endsection
