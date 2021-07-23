<?php


namespace App\Http\Controllers;


use App\Interfaces\IService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BaseAbstractController extends Controller
{
    protected $service;
    protected $classname;
    protected $rules;

    public function __construct(IService $service, string $classname, array $rules)
    {
        $this->service = $service;
        $this->classname = $classname;
        $this->rules = $rules;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        return $this->classname::all();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create()
    {
        return null;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function store(Request $request)
    {
        $fields = $request->toArray();
        $validator = Validator::make($fields, $this->rules);
        $validatorErrors = $validator->errors()->all();
        $obj = new $this->classname;
        if (sizeof($validatorErrors) == 0) {
            foreach ($fields as $key => $value) {
                $obj->$key = $value;
            }
            if (!$obj->save()) {
                return response()->json(['message' => 'There was a problem saving.'], env("HTTP_SERVER_ERROR"));
            }
            return response()->json($obj, env("HTTP_SUCCESS"));
        } else {
            return response()->json([['message' => $validatorErrors]], env("HTTP_VALIDATION_ERROR"));
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */

    public function show($id)
    {
        $obj = $this->classname::find($id);
        if ($obj === null) {
            return response()->json([['message' => 'Entity not found.']], env("HTTP_NOT_FOUND"));
        }
        return $obj;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */

    public function edit($id)
    {
        return null;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */

    public function update(Request $request, $id)
    {
        $fields = $request->toArray();
        $validator = Validator::make($fields, $this->rules);
        $validatorErrors = $validator->errors()->all();
        $obj = $this->classname::find($id);
        if ($obj === null) {
            return response()->json([['message' => 'Entity not found.']], env("HTTP_NOT_FOUND"));
        }
        if (sizeof($validatorErrors) == 0) {
            foreach ($fields as $key => $value) {
                $obj->$key = $value;
            }
            if (!$obj->save()) {
                return response()->json(['message' => 'There was a problem saving.'], env("HTTP_SERVER_ERROR"));
            }
            return response()->json($obj, env("HTTP_SUCCESS"));
        } else {
            return response()->json([['message' => $validatorErrors]], env("HTTP_VALIDATION_ERROR"));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */

    public function destroy($id)
    {
        $obj = $this->classname::find($id);
        if ($obj === null) {
            return response()->json([['message' => 'Entity not found.']], env("HTTP_NOT_FOUND"));
        }
        $obj->delete();
        return response()->json(['message' => 'Succesfuly deleted.'], env("HTTP_SUCCESS"));
    }

}
