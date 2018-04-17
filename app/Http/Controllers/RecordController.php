<?php

namespace App\Http\Controllers;

use Cache;
use Storage;
use App\Libraries\CoverCache;
use App\License;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecordController extends Controller
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
    public function create(Request $request)
    {
        $mat_types = config('mat_types')['downloads'];
        $license_slug = $request->input('license_slug') ?? '';
        return view('record.create', compact('mat_types', 'license_slug'));
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
            'title' => 'required',
            'license_slug' => 'required',
            'cover' => 'sometimes|mimes:jpg,jpeg'
        ]);
        $couch = resolve('Couchdb');
        $input = $request->all();
        $record = new \stdClass;
        $record->_id = str_slug($input['title'], '-');
        $record->licensed_from = $input['license_slug'];
        $record->mat_code = $input['mat_type'];
        $record->pub_year = $input['pub_year'];
        $record->active = $input['is_active'];
        if ($input['cover']) 
        {
            
        }

        try {
            $couch->storeDoc($record);
        } catch (Exception $e) {
            $this->error("Getting record failed : " . $e->getMessage());
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
