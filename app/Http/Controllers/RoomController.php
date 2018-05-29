<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoom;
use App\Http\Requests\UpdateRoom;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class RoomController
 *
 * @package App\Http\Controllers
 * @author Pisyek K
 * @url www.pisyek.com
 * @copyright © 2017 Pisyek Studios
 */
class RoomController extends Controller
{
    private $data;

    /**
     * RoomController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('permission:create-room|read-room|update-room|delete-room');

        $this->data = [
            'pageTitle' => __('Room Management'),
            'pageHeader' => __('Room Management'),
            'pageSubHeader' => __('Manage your rooms here')
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.room-management', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRoom $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoom $request)
    {
        $data = $request->all();

        return response()->json([
            'message' => __('Room :name is successfully saved!', ['name' => $data['name']])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->data['room'] = Room::findOrFail($id);


      return view('dashboard.room-show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->canUpdateRoom()) {
            return response()->json([
                'message' => __('You have no authorization to perform this action.')
            ], 403);
        }

        $this->data['room'] = Room::findOrFail($id);
        return view('dashboard.room-edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoom $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRoom $request, $id)
    {
        $data = $request->all();
        $room = Room::findOrFail($id);
        $room->fill($data);
        $room->save();

        return response()->json([
            'message' => __('Room :name is successfully updated!', ['name' => $data['name']])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->canDeleteRoom()) {
            return response()->json([
                'message' => __('You have no authorization to perform this action.')
            ], 403);
        }

        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json([
            'message' => __(':name is successfully deleted!', ['name' => $room->name])
        ]);
    }
}
