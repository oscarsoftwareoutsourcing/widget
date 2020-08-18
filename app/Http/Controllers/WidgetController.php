<?php

namespace App\Http\Controllers;

use App\Http\Requests\WidgetRequest;
use App\Models\Widget;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class WidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(WidgetRequest $request)
    {
        $request->validated();

        try{

            $widget = new Widget();
            $widget->app_id = $request->app_id;
            $widget->user_id = $request->user_id;
            $widget->name = $request->name;
            $widget->user_id_referred = $request->user_id_referred;
            $widget->url = $request->url;
            $widget->token = UuidController::v4();
            $widget->save();
            return response()->json(['success'=>true, 'message' => "Widget created successfully"]);
        } catch (ErrorException $error){

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $where[] = ['token',$uuid];
        $where[] = ['status',1];
        $widget = Widget::where($where)->first();

        if ($widget){
            $widgetFile = Storage::disk('local')->get('widget.js');
            $widgetFile = str_replace("%%%WIDGET_ID%%%",$widget->token,$widgetFile);
            $widgetFile = str_replace("%%%URL_SITE%%%",Config::get('app.url'),$widgetFile);
            return Response::make($widgetFile, '200')->header('Content-Type', 'text/javascript');
        } else {
            return Response::make('', '200')->header('Content-Type', 'text/javascript');
        }

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
