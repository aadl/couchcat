<?php

namespace App\Http\Controllers;

use Cache;
use App\License;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Cache::rememberForever('vendors', function () {
            return Vendor::with('licenses')->orderBy('name', 'dsc')->get();
        });
        return view('vendor.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendor.create');
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
            'name' => 'required|unique:vendors',
            'contact_email' => 'nullable|email'
        ]);
        $vendor = new Vendor;
        $input = $request->all();
        $vendor->fill($input)->save();
        Cache::forget('vendors');
        return redirect('vendor/'.$vendor->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        return view('vendor.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->validate($request, [
            'name' => ['required',Rule::unique('vendors')->ignore($vendor->id)],
            'contact_email' => 'nullable|email'
        ]);
        $input = $request->all();
        $vendor->fill($input)->save();
        Cache::forget('vendors');
        return redirect('vendor/'.$vendor->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        Cache::forget('vendors');
        return redirect('vendor');
    }

    public function welcome()
    {
        $vendor_changes = Vendor::orderBy('updated_at', 'DESC')->take(5)->get();
        $license_changes = License::orderBy('updated_at', 'DESC')->take(5)->get();
        return view('welcome', compact('vendor_changes', 'license_changes'));
    }
}
