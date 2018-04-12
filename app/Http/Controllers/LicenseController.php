<?php

namespace App\Http\Controllers;

use Cache;
use App\License;
use App\Vendor;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $licenses = Cache::rememberForever('licenses', function () {
            return License::with('vendor')->orderBy('expires', 'asc')->get();
        });
        $couch = resolve('Couchdb');
        $records_view = $couch->group(true)->getView('couchcat', 'licensed_from');
        $records = $records_view->rows;
        return view('license.index', compact('licenses', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Cache::rememberForever('vendors', function () {
            return Vendor::orderBy('name', 'asc')->get();
        });
        return view('license.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'vendor_id' => 'required|exists:vendors,id',
            'statistics_stub' => 'required|unique:licenses',
            'starts' => 'required|date',
            'ends' => 'nullable|date'
        ]);
        $license = new License;
        $input = $request->all();
        $license->fill($input)->save();
        Cache::forget('licenses');
        return redirect('license/'.$license->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\License  $license
     * @return \Illuminate\Http\Response
     */
    public function show(License $license)
    {
        return view('license.show', compact('license'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\License  $license
     * @return \Illuminate\Http\Response
     */
    public function edit(License $license)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\License  $license
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, License $license)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\License  $license
     * @return \Illuminate\Http\Response
     */
    public function destroy(License $license)
    {
        //
    }
}
