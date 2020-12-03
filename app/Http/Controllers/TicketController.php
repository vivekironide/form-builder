<?php

    namespace App\Http\Controllers;

    use App\Mail\CustomerTicket;
    use App\Models\Ticket;
    use App\Models\TicketReply;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;

    class TicketController extends Controller
    {
        public function index()
        {
            return view( 'ticket-list' );
        }

        public function create()
        {
            return view( 'create-ticket' );
        }

        public function store( Request $request )
        {
            try {
                $validator = Validator::make( $request->all(),
                                              [
                                                  'customer_name' => 'required',
                                                  'description'   => 'required',
                                                  'email'         => 'required|email',
                                                  'phone'         => 'required|numeric',
                                              ] );

                if ( $validator->fails() ) {
                    throw new \Exception( 'Validation error.', 422 );
                }

                $inputs = [
                    'customer_name' => request( 'customer_name' ),
                    'description'   => request( 'description' ),
                    'email'         => request( 'email' ),
                    'phone'         => request( 'phone' ),
                    'reference_no'  => (string) Str::uuid(),
                ];

                $ticket = Ticket::create( $inputs );

                Mail::to( $request->get( 'email' ) )->send( new CustomerTicket( $ticket ) );

                return response( 'Ticket Submitted.', 200 );
            }
            catch ( \Exception $e ) {
                $response = [
                    'error' => $e->getMessage(),
                    'file'  => 'File: ' . $e->getFile() . '. Line: ' . $e->getLine(),
                    'trace' => $e->getTrace(),
                    'code'  => $e->getCode(),
                ];

                return response( $response, 500 );
            }
        }

        public function datatable( Request $request )
        {

            try {
                $tickets = Ticket::query();

                $recordsTotal = $tickets->count();

                if ( ! is_null( request()->get( 'search' )[ 'value' ] ) ) {
                    $tickets->where( 'customer_name', 'LIKE', '%' . request()->get( 'search' )[ 'value' ] . '%' );
                }

                $recordsFiltered = $tickets->count();

                $tickets = $tickets->orderBy( 'id', 'desc' )->skip( (int) request( 'start' ) )->take( (int) request( 'length' ) )->get();

                $ticketsRevamped = $tickets->map( function ( $ticket ) {
                    return [
                        'customer_name' => $ticket->is_opened ? '<a href="' . route( 'ticket.show.agent', [ 'id' => $ticket->id ] ) . '"> ' . $ticket->customer_name . '<a>' : $ticket->customer_name,
                        'description'   => $ticket->description,
                        'email'         => $ticket->email,
                        'phone'         => $ticket->phone,
                        'action'        => $ticket->is_opened ? '<a class="ui green label">Opened<a>' : '<a class="ui yellow label">New<a>',
                    ];
                } );

                return [
                    'draw'            => (int) request( 'draw' ),
                    'recordsTotal'    => (int) $recordsTotal,
                    'recordsFiltered' => (int) $recordsFiltered,
                    'data'            => $ticketsRevamped,
                    'error'           => '',
                ];
            }
            catch ( \Exception $e ) {
                $response = [
                    'error' => $e->getMessage(),
                    'file'  => 'File: ' . $e->getFile() . '. Line: ' . $e->getLine(),
                    'trace' => $e->getTrace(),
                    'code'  => $e->getCode(),
                ];

                return response( $response, 500 );
            }
        }

        public function show( Request $request )
        {
            if ( $request->ajax() ) {
                $ticket = Ticket::where( 'reference_no', $request->get( 'ref_id' ) )->first();

                return view( 'ticket-show', compact( 'ticket' ) )->render();
            }

            return view( 'dashboard' );
        }

        public function showAgent( $id )
        {
            $ticket = Ticket::find( $id );

            return view( 'ticket-agent-show', compact( 'ticket' ) );
        }

        public function updateAgent( $id, Request $request )
        {
            try {
                $validator = Validator::make( $request->all(),
                                              [
                                                  'reply'    => 'required',
                                                  'agent_id' => 'required',
                                              ] );

                if ( $validator->fails() ) {
                    throw new \Exception( 'Validation error.', 422 );
                }

                $inputs = [
                    'reply'     => request( 'reply' ),
                    'agent_id'  => request( 'agent_id' ),
                    'ticket_id' => $id,
                ];

                $ticket = TicketReply::create( $inputs );

                $tic = Ticket::find($id);

                Mail::to( $tic->email )->send( new CustomerTicket( $tic ) );

                return response( 'Ticket Replied.', 200 );
            }
            catch ( \Exception $e ) {
                $response = [
                    'error' => $e->getMessage(),
                    'file'  => 'File: ' . $e->getFile() . '. Line: ' . $e->getLine(),
                    'trace' => $e->getTrace(),
                    'code'  => $e->getCode(),
                ];

                return response( $response, 500 );
            }
        }
    }
