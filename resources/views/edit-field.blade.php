@extends('master-layout')

@section('css')
    @parent

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.semanticui.min.css">
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Field Create </h2>

    <div class="ui breadcrumb">
	    <a class="section" id="field_list" href="{{route('field.list')}}"><i class="fa fa-home"></i></a>
	    <i class="right chevron icon divider"></i>
	    <div class="section">Field</div>
	    <i class="right chevron icon divider"></i>
        <div class="active section">Edit</div>
	</div>

    <div id="image">
		<div class="ui container mt2">
			<form class="ui equal width form mt2" action="{{route('field.update', ['id' => $field->id])}}" method="POST">
                <div class="required field">
                    <label>Field Type</label>
                     <div class="ui selection dropdown">
                        <input type="hidden" name="type" id="type" value="{{ $field->type }}">
                        <i class="dropdown icon"></i>
                        <div class="default text">None</div>
                         <div class="menu">
                             <div class="item" data-value="text">Text</div>
                             <div class="item" data-value="textarea">Text Area</div>
                             <div class="item" data-value="combobox">Combo Box</div>
                             <div class="item" data-value="date">Date Picker</div>
                             <div class="item" data-value="radio">Radio Button</div>
                         </div>
                    </div>
                </div>

                <div class="required field">
                    <label for="label">Label</label>
                    <input type="text" name="label" id="label" value="{{ $field->label }}">
                </div>

                <div class="field">
                    <label for="default">Default Value</label>
                    <input type="text" name="default" id="default" value="{{ $field->default }}" @if($field->type === 'combobox' || $field->type === 'radio') disabled="disabled"  @endif>
                </div>

                <div class="field">
                    <label for="placeholder">Input Placeholder</label>
                    <input type="text" name="placeholder" id="placeholder" value="{{ $field->placeholder }}" @if($field->type === 'combobox' || $field->type === 'radio') disabled="disabled"  @endif>
                </div>

                <div class="field">
                    <div class="ui checkbox">
                        <input type="checkbox" name="required" id="required" @if($field->mandatory) checked="checked"  @endif>
                        <label for="required">Mandatory</label>
                    </div>
                </div>

                @if($field->type === 'combobox' || $field->type === 'radio')
                    <div class="field lists">
                        <label for="list"><span>@if($field->type === 'combobox') Lists @elseif($field->type === 'radio') Options @endif</span> <button class="ui button btn-corporate" id="add_list" type="button" style="padding: 0.5rem 0.5rem 0.5rem 0.7rem; margin-left: 2rem;"><i class="fa fa-plus"></i></button></label>
                        <div class="ui list">
                            @foreach($field->list as $key => $list)
                                <div class="item">
                                    <div class="inline field">
                                        <label for="list[{{$key}}]">Name</label>
                                        <input type="text" name="list[{{$key}}]" id="list[{{$key}}]" value="{{$list}}">
                                        <button class="ui button btn-corporate sub-list" id="sub_list" type="button" style="padding: 0.5rem 0.5rem 0.5rem 0.7rem; margin-left: 2rem;"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
			</form>

			<div class="ui column pt2 pb2">
				<button class="ui button btn-corporate right floated" id="submit_field">Submit</button>
			</div>
		</div>
	</div>
@endsection

@section('js')
    @parent
    <script>
        $('.ui.dropdown').addClass("disabled");

        let $subList = $('.sub-list');

        $subList.each(function() {
            $(this).hide();
        });

        $subList.last().show();

        $('#add_list').click(function(e) {
            e.preventDefault();

            let $uiList = $('.ui.list');

            let listNo = $uiList.find('.item').length;

            let html = '<div class="item">\n' +
                '<div class="inline field">\n' +
                '<label for="list['+ listNo +']">Name</label>\n' +
                '<input type="text" name="list['+ listNo +']" id="list['+ listNo +']">\n' +
                '<button class="ui button btn-corporate sub-list" id="sub_list" type="button" style="padding: 0.5rem 0.5rem 0.5rem 0.7rem; margin-left: 2rem;"><i class="fa fa-minus"></i></button>\n' +
                '</div>\n' +
                '</div>';

            $uiList.append(html);

            let $subList = $('.sub-list');

            $subList.each(function() {
                $(this).hide();
            });

            $subList.last().show();
        });

        $(document).on('click', '.sub-list', function() {
            $(this).parents('.item').remove();

            $('.sub-list').last().show();
        });

        $( '#submit_field' ).click( function( e ) {
            e.preventDefault();
            $(this).prop('disabled', true);

            let $form = $( ".ui.form" );
            let $meta = $( 'meta[name="csrf-token"]' );
            let required = 0;

            $form
                .form( {
                    fields: {
                        type : 'empty',
                        label: 'empty',
                    },
                    inline: true,
                    on    : 'blur'
                } );

            $form.form( 'validate form' );

            if( !$form.form( 'is valid' ) ) {
                return false;
            }

            if ($('#required').prop('checked')) {
                required = 1;
            }

            let lists = $('input[name^=list]').map(function() {
                return this.value;
            }).get();

            $form.addClass( 'loading' );

            axios( {
                method : 'POST',
                url    : $form.attr( 'action' ),
                data   : { type: $( '#type' ).val(), label: $( '#label' ).val(), default: $( '#default' ).val(), placeholder: $( '#placeholder' ).val(), mandatory: required, list: lists, _method: 'PUT' },
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
                    location.href = $('#field_list').attr('href');
                });
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
                $form.removeClass( 'loading' );
            } );

        } )
    </script>
@endsection
