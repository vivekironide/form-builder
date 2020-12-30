<?php

    namespace App\Http\Controllers;

    use App\Mail\CustomerTicket;
    use App\Models\Field;
    use App\Models\TicketReply;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;

    class FieldController extends Controller
    {
        public function index()
        {
            return view( 'field-list' );
        }

        public function create()
        {
            return view( 'create-field' );
        }

        public function store( Request $request )
        {
            try {
                $validator = Validator::make( $request->all(),
                                              [
                                                  'type'  => 'required',
                                                  'label' => 'required',
                                              ] );

                if ( $validator->fails() ) {
                    throw new \Exception( 'Validation error.', 422 );
                }

                $inputs = [
                    'type'        => request( 'type' ),
                    'label'       => request( 'label' ),
                    'default'     => request( 'default' ),
                    'placeholder' => request( 'placeholder' ),
                    'mandatory'   => request( 'mandatory' ),
                    'list'        => request( 'list' ),
                ];

                Field::create( $inputs );

                return response( 'Field Submitted.', 200 );
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
                $tickets = Field::query();

                $recordsTotal = $tickets->count();

                if ( ! is_null( request()->get( 'search' )[ 'value' ] ) ) {
                    $tickets->where( 'label', 'LIKE', '%' . request()->get( 'search' )[ 'value' ] . '%' );
                }

                $recordsFiltered = $tickets->count();

                $fileds = $tickets->orderBy( 'id', 'desc' )->skip( (int) request( 'start' ) )->take( (int) request( 'length' ) )->get();

                $fieldsRevamped = $fileds->map( function ( $field ) {
                    $type = '';

                    switch ( $field->type ) {
                        case 'text':
                            $type = 'Text';
                            break;
                        case 'textarea':
                            $type = 'Text Area';
                            break;
                        case 'combobox':
                            $type = 'Combo Box';
                            break;
                        case 'radio':
                            $type = 'Radio';
                            break;
                        case 'date':
                            $type = 'Date Picker';
                            break;
                    }

                    return [
                        'type'        => $type,
                        'label'       => $field->label,
                        'default'     => $field->default,
                        'placeholder' => $field->placeholder,
                        'mandatory'   => $field->mandatory ? '<a class="ui green label">Yes<a>' : '<a class="ui yellow label">No<a>',
                        'action'      => '<a href="' . route( 'field.edit',
                                                              [ 'id' => $field->id ] ) . '" class="ui button btn-blue btn-corporate " id="edit_field" type="button" style="padding: 0.5rem 0.5rem 0.5rem 0.7rem; margin-left: 2rem;"><i class="fa fa-pencil"></i></a> <a href="' . route( 'field.destroy',
                                                                                                                                                                                                                                                                                          [ 'id' => $field->id ] ) . '" class="ui button btn-corporate btn-red" id="delete_field" type="button" style="padding: 0.5rem 0.5rem 0.5rem 0.7rem; margin-left: 2rem;"><i class="fa fa-trash"></i></a>',
                    ];
                } );

                return [
                    'draw'            => (int) request( 'draw' ),
                    'recordsTotal'    => (int) $recordsTotal,
                    'recordsFiltered' => (int) $recordsFiltered,
                    'data'            => $fieldsRevamped,
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

        public function edit( $id )
        {
            $field = Field::where( 'id', $id )->first();

            if ( is_null( $field ) ) {
                return redirect()->route('field.list');
            }

            return view( 'edit-field', compact( 'field' ) );
        }

        public function update( $id, Request $request )
        {
            try {
                $validator = Validator::make( $request->all(),
                                              [
                                                  'type'  => 'required',
                                                  'label' => 'required',
                                              ] );

                if ( $validator->fails() ) {
                    throw new \Exception( 'Validation error.', 422 );
                }

                $field = Field::where( 'id', $id )->first();

                if ( is_null( $field ) ) {
                    throw new \Exception( 'Field not found', 422 );
                }

                $field->type        = request( 'type' );
                $field->label       = request( 'label' );
                $field->default     = request( 'default' );
                $field->placeholder = request( 'placeholder' );
                $field->mandatory   = request( 'mandatory' );
                $field->list        = request( 'list' );

                $field->save();

                return response( 'Field Submitted.', 200 );
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
                $ticket = Field::where( 'reference_no', $request->get( 'ref_id' ) )->first();

                return view( 'ticket-show', compact( 'ticket' ) )->render();
            }

            return view( 'dashboard' );
        }

        public function destroy( $id )
        {
            try {
                Field::where( 'id', $id )->delete();

                return response( 'Field Deleted.', 200 );
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
