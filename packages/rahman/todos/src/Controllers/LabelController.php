<?php

namespace Rahman\Todos\Controllers;

use Illuminate\Http\Request;
use Rahman\Todos\Requests\LabelRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Rahman\Todos\Models\Label;
use Rahman\Todos\Resources\LabelResource;
use Rahman\Todos\Models\TaskLabel;
use Rahman\Todos\Services\LabelService;
use Rahman\Todos\Resources\LabelCollection;
use DB;

/**
 * @group Labels
 * API endpoints for managing labels
 */
class LabelController extends Controller
{

    //LabelService param for injecting dependency
    protected $labelService;

    /**
     * Constructor for injecting tht dependency
     */
    public function __construct(LabelService $labelService){
        $this->labelService = $labelService;
    }

    public function index()
    {


        $labels = $this->labelService->labelsList();

        return $labels;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Rahman\Todos\Requests\LabelRequest  $request
     * @bodyParam title string required The title of the label.Example: School
     * @bodyParam user_id int The id of user who stores the label.
     * @return Rahman\Todos\Resources\LabelResource
     */
    public function store(LabelRequest $request) 
    {
        try{
            $label = Label::create([
                'title' => $request->title,
                'user_id' => Auth::id()
            ]);

            return new LabelResource($label);
        }
        catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                //Log the error in application log file with proper message
                Log::channel('application')->error("Duplicated entry ".$request->title." entered!");
                //Error code 400 for bad request
                return response()->json([
                    'message' => 'Duplicated label entered!'
                ], 400);
            }
        }

       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
