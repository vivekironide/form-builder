@extends('master-layout')

@section('css')
    @parent

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.semanticui.min.css">
@endsection

@section('content')
    <h2 class="ui header header-style"><i class="fa fa-home"></i> Tickets </h2>

    <div class="ui breadcrumb">
	    <a class="section" href="{{route('ticket.show')}}"><i class="fa fa-home"></i></a>
	    <i class="right chevron icon divider"></i>
	    <div class="section">Ticket</div>
	    <i class="right chevron icon divider"></i>
        <div class="active section">List</div>
	</div>

    <div id="image">
		<div class="ui grip padded mx2 mt2">
			<table class="ui striped black celled table" id="datatable">
				<thead class="full width">
					<tr>
						<th>Customer Name</th>
						<th>Description</th>
						<th>Email</th>
						<th>Phone</th>
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
                'url' : window.routes.ticketDatatable,
                'type': 'GET',
                'data': function( d ) {

                },
            },
            "columns"   : [
                { "data": "customer_name", 'searchable': false, 'orderable': false },
                { "data": "description", 'searchable': false, 'orderable': false },
                { "data": "email", 'searchable': false, 'orderable': false },
                { "data": "phone", 'searchable': false, 'orderable': false },
                { "data": "action", 'searchable': false, 'orderable': false },
            ],
            "order"     : false,
            "dom"       : 'frt<p>',
        } );

    </script>
@endsection
