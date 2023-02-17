<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user()->current_store;

        $shippings = Shipping::where('store_id', $user)->where('created_by', \Auth::user()->creatorId())->get();
        $locations = Location::where('store_id', $user)->where('created_by', \Auth::user()->creatorId())->get();

        return view('shipping.index', compact('shippings', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \Auth::user()->current_store;
        $locations = Location::where('store_id', $user)->get()->pluck('name', 'id');

        return view('shipping.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request, [
                        'name' => 'required|max:40',
                        'price' => 'required|numeric',
                    ]
        );

        $shipping              = new Shipping();
        $shipping->name        = $request->name;
        $shipping->price       = $request->price;
        $shipping->location_id = implode(',', $request->location);
        $shipping->store_id    = \Auth::user()->current_store;
        $shipping->created_by  = \Auth::user()->creatorId();
        $shipping->save();

        return redirect()->back()->with('success', __('Shipping added!'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Shipping $shipping
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Shipping $shipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Shipping $shipping
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipping $shipping)
    {
        $locations = Location::get()->pluck('name', 'id');

        return view('shipping.edit', compact('shipping', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Shipping $shipping
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipping $shipping)
    {
        $this->validate(
            $request, [
                        'name' => 'required|max:40',
                        'price' => 'required|numeric',
                    ]
        );

        $shipping->name        = $request->name;
        $shipping->price       = $request->price;
        $shipping->location_id = implode(',', $request->location);
        $shipping->created_by  = \Auth::user()->creatorId();
        $shipping->save();

        return redirect()->back()->with('success', __('Shipping Updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Shipping $shipping
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $shipping)
    {

        $shipping->delete();

        return redirect()->back()->with('success', __('Shipping Deleted!'));
    }
}
