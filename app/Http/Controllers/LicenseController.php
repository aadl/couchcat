<?php

namespace App\Http\Controllers;

use Cache;
use App\License;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        return view('license.index', compact('licenses', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $vendors = Vendor::all()->pluck('name', 'id');
        $vendor_id = $request->input('vendor_id') ?? 1;
        return view('license.create', compact('vendors', 'vendor_id'));
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
            'license_slug' => 'required|unique:licenses',
            'starts' => 'required|date',
            'ends' => 'nullable|date'
        ]);
        $license = new License;
        $input = $request->all();
        $license->fill($input);
        $license->patrons_only = $request->has('patrons_only');
        $license->save();
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
        $vendors = Vendor::all()->pluck('name', 'id');
        return view('license.edit', compact('license', 'vendors'));
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
        $this->validate($request, [
            'vendor_id' => 'required|exists:vendors,id',
            'license_slug' => ['required',Rule::unique('licenses')->ignore($license->id)],
            'starts' => 'required|date',
            'ends' => 'nullable|date'
        ]);
        $input = $request->all();
        $license->fill($input);
        $license->patrons_only = $request->has('patrons_only');
        $license->save();
        Cache::forget('licenses');
        return redirect('license/'.$license->id);
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
