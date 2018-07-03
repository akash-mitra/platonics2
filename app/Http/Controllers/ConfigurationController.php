<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Configuration;

class ConfigurationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function __construct()
    {
        //$this->middleware('permissions');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configurations = Configuration::getConfigs();

        return response()->json([
                'length' => count($configurations),
                'data' => $configurations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->input();
        $key = $input['key'];
        $value = $input['value'];

        return Configuration::setConfig($key, $value);
    }

    /**
     * Display the specified resource.
     *
     * @param  string                    $key
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        $configuration = Configuration::getConfig($key);

        return response()->json($configuration);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string                    $key
     * @return \Illuminate\Http\Response
     */
    public function destroy($key)
    {
        $configuration = Configuration::delConfig($key);

        return response()->json(['status' => $configuration]);
    }
}
