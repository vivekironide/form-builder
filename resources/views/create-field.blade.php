@extends('master-layout')

@section('css')
    @parent

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.semanticui.min.css">

    <style>
        .lists {
            display: none;
        }
    </style>
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Field Create </h2>

    <div class="ui breadcrumb">
	    <a class="section" href="{{route('field.list')}}"><i class="fa fa-home"></i></a>
	    <i class="right chevron icon divider"></i>
	    <div class="section">Field</div>
	    <i class="right chevron icon divider"></i>
        <div class="active section">Create</div>
	</div>

    <div id="image">
		<div class="ui container mt2">
			<form class="ui equal width form mt2" action="{{route('field.store')}}" method="POST">
                <div class="required field">
                    <label>Field Type</label>
                     <div class="ui selection dropdown">
                        <input type="hidden" name="type" id="type">
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
                    <input type="text" name="label" id="label">
                </div>

                <div class="field">
                    <label for="default">Default Value</label>
                    <input type="text" name="default" id="default">
                </div>

                <div class="field">
                    <label for="placeholder">Input Placeholder</label>
                    <input type="text" name="placeholder" id="placeholder">
                </div>

                <div class="field">
                    <div class="ui checkbox">
                        <input type="checkbox" name="required" id="required">
                        <label for="required">Mandatory</label>
                    </div>
                </div>

                <div class="field lists">
                    <label for="list"><span>Lists</span> <button class="ui button btn-corporate" id="add_list" type="button" style="padding: 0.5rem 0.5rem 0.5rem 0.7rem; margin-left: 2rem;"><i class="fa fa-plus"></i></button></label>
                    <div class="ui list">
                        <div class="item">
                            <div class="inline field">
                                <label for="list[0]">Name</label>
                                <input type="text" name="list[0]" id="list[0]">
                            </div>
                        </div>
                    </div>
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

        $('#type').change(function() {
            let option = $(this).val();

            if(option === 'combobox' || option === 'radio') {
                $('#default').parents('.field').addClass('disabled');
                $('#placeholder').parents('.field').addClass('disabled');
                $('.lists').show();
            } else {
                $('#default').parents('.field').removeClass('disabled');
                $('#placeholder').parents('.field').removeClass('disabled');
                $('.lists').hide();
            }

            if(option === 'combobox') {
                $('.lists').find('span').text('Lists');
            } else if (option === 'radio') {
                $('.lists').find('span').text('Options');
            }
        });

        $( '#submit_ticket' ).click( function( e ) {
            e.preventDefault();

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

            console.log(lists);

            $form.addClass( 'loading' );

            axios( {
                method : 'POST',
                url    : $form.attr( 'action' ),
                data   : { type: $( '#type' ).val(), label: $( '#label' ).val(), default: $( '#default' ).val(), placeholder: $( '#placeholder' ).val(), mandatory: required, list: lists },
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

                $form.form( 'clear' );
            } );

        } )
    </script>
@endsection
