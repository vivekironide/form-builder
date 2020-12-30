<?php

    namespace App\Http\Controllers;

    use App\Models\Field;
    use App\Models\Form;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class FormBuilderController extends Controller
    {
        public function index()
        {
            return view( 'form-list' );
        }

        public function create()
        {
            $fields = Field::all();

            return view( 'form-builder', compact( 'fields' ) );
        }

        public function store( Request $request )
        {
            try {
                $validator = Validator::make( $request->all(),
                                              [
                                                  'name'   => 'required',
                                                  'fields' => 'required',
                                              ] );

                if ( $validator->fails() ) {
                    throw new \Exception( 'Validation error.', 422 );
                }

                $inputs = [
                    'name'   => request( 'name' ),
                    'fields' => request( 'fields' ),
                ];

                Form::create( $inputs );

                return response( 'Form Creeated.', 200 );
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

        public function show( $id )
        {
            $form = Form::where('id', $id)->first();

            if (is_null( $form)) {
                return redirect()->route('field.list');
            }

            $orderedFields = implode( ',', $form->fields);

            $fields = Field::whereIn('id', $form->fields)->orderByRaw("FIELD(id, $orderedFields)")->get();

            return view('form-preview', compact( 'form', 'fields'));
        }

        public function datatable( Request $request )
        {

            try {
                $form = Form::query();

                $recordsTotal = $form->count();

                if ( ! is_null( request()->get( 'search' )[ 'value' ] ) ) {
                    $form->where( 'name', 'LIKE', '%' . request()->get( 'search' )[ 'value' ] . '%' );
                }

                $recordsFiltered = $form->count();

                $forms = $form->orderBy( 'id', 'desc' )->skip( (int) request( 'start' ) )->take( (int) request( 'length' ) )->get();

                $formsRevamped = $forms->map( function ( $form ) {
                    return [
                        'name'        => $form->name,
                        'date_created' => $form->created_at->format('M d Y'),
                        'action'      => '<a href="' . route( 'form.show',
                                                              [ 'id' => $form->id ] ) . '" class="ui button btn-blue btn-corporate " id="edit_field" type="button" style="padding: 0.5rem 0.5rem 0.5rem 0.7rem; margin-left: 2rem;"><i class="fa fa-eye"></i></a> <a href="' . route( 'form.destroy',
                                                                                                                                                                                                                                                                                         [ 'id' => $form->id ] ) . '" class="ui button btn-corporate btn-red" id="delete_field" type="button" style="padding: 0.5rem 0.5rem 0.5rem 0.7rem; margin-left: 2rem;"><i class="fa fa-trash"></i></a>',
                    ];
                } );

                return [
                    'draw'            => (int) request( 'draw' ),
                    'recordsTotal'    => (int) $recordsTotal,
                    'recordsFiltered' => (int) $recordsFiltered,
                    'data'            => $formsRevamped,
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

        public function destroy( $id )
        {
            try {
                Form::where( 'id', $id )->delete();

                return response( 'Form Deleted.', 200 );
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
