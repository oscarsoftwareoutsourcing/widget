<?php

namespace App\Http\Controllers;

use App\Http\Requests\WidgetDataRequest;
use App\Models\Widget;
use App\Models\WidgetData;
use App\Notifications\WidgetDataNotification;
use App\User;
use App\Helpers\FormatTime;
use Illuminate\Http\Request;
use ErrorException;
use Illuminate\Http\Response;
use Notification;

class WidgetDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WidgetDataRequest $request
     * @return Response
     */
    public function store(WidgetDataRequest $request)
    {
        $request->validated();

        try {
            // Headers from API request
            $origin = request()->headers->get('origin');
            $widgetId = request()->headers->get('widgetId');
            // Search the widget ID
            $widget = Widget::where('token', $widgetId)->first();
            $user = User::find($widget->user_id);
            $user_referred = User::find($widget->user_id_referred);
            // Saving the data into Database (WidgetData table)
            $widgetData = new WidgetData();
            $widgetData->widget_id = $widget->id;
            $widgetData->origin = $origin;
            $data = array ('phone' => $request->phone);
            $widgetData->info_data = json_encode($data);
            $widgetData->save();
            $time=FormatTime::LongTimeFilter($widgetData->created_at);

            /** Notificación al usuario que creo el widget (por correo y por notificación de la aplicación) */
            $user->notify(new WidgetDataNotification($user_referred, $widget, $widgetData, $time));
            /** Notificación al usuario referido (por correo y por notificación de la aplicación) */
            $user_referred->notify(new WidgetDataNotification($user, $widget, $widgetData, $time));
        } catch (ErrorException $error) {
            return response()->json(['success'=>false, 'message' => $error], 500);
        }
        return response()->json(['success'=>true, 'message' => "Register created successfully"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
