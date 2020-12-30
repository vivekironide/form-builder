@extends('master-layout')

@section('css')
    @parent

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.semanticui.min.css">
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Fields </h2>

    <div class="ui breadcrumb">
	    <a class="section" href="{{route('field.list')}}"><i class="fa fa-home"></i></a>
	    <i class="right chevron icon divider"></i>
	    <div class="section">Field</div>
	</div>

    <div id="image">
		<div class="ui grip padded mx2 mt2">
			<table class="ui striped black celled table" id="datatable">
				<thead class="full width">
					<tr>
						<th>Type</th>
						<th>Label</th>
						<th>Default</th>
						<th>Placeholder</th>
						<th>Mandatory</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection

@section('js')
    @parent

    <script>
	    var image_datatable = "";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.semanticui.min.js"></script>
    <script>
        $.fn.dataTable.ext.errMode = 'none';

        let invoiceDataTable = $( '#datatable' ).on( 'error.dt', function( e, settings, techNote, message ) {
            Toast.fire( {
                icon : 'error',
                title: 'Datatable error, please check the console.'
            } );

            console.log( 'An error has been reported by DataTables: ', message );
        } ).DataTable( {
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "language"  : {
                "processing": "<i class=\"fa fa-refresh fa-2x fa-spin\" aria-hidden=\"true\"></i>",
            },
            "ajax"      : {
                'url' : window.routes.fieldDatatable,
                'type': 'GET',
                'data': function( d ) {

                },
            },
            "columns"   : [
                { "data": "type", 'searchable': false, 'orderable': false },
                { "data": "label", 'searchable': false, 'orderable': false },
                { "data": "default", 'searchable': false, 'orderable': false },
                { "data": "placeholder", 'searchable': false, 'orderable': false },
                { "data": "mandatory", 'searchable': false, 'orderable': false },
                { "data": "action", 'searchable': false, 'orderable': false },
            ],
            "order"     : false,
            "dom"       : 'frt<p>',
        } );

        $(document).on('click', '#delete_field', function(e) {
            e.preventDefault();

            let $form = $( ".ui.form" );
            let $meta = $( 'meta[name="csrf-token"]' );
            $(this).prop('disabled', true);

            axios( {
                method : 'POST',
                url    : $(this).attr( 'href' ),
                data   : { _method: 'DELETE' },
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
                $(this).prop('disabled', false);
                invoiceDataTable.ajax.reload();
            } );
        });

    </script>
@endsection
