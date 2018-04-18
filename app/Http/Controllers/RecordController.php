<?php

namespace App\Http\Controllers;

use Artisan;
use Cache;
use Storage;
use App\Libraries\FileHandler;
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

        $input = $request->all();
        $file_handler = new FileHandler;

        // create new record object and assign fields
        $record = new \stdClass;
        $record->_id = str_slug($input['title'], '-');
        $record->licensed_from = $input['license_slug'];
        $record->mat_code = $input['mat_code'];
        $record->pub_year = $input['pub_year'];
        $record->active = ($input['is_active'] ?? 0);

        // if attachments do extra validation on the file and then upload it
        if ($input['attachment']) {
            $license_paths = config('license_paths');
            if ($input['mat_code'] == 'zb' || $input['mat_code'] == 'zp') {
                $allowed = 'pdf';
                $save_as = '.pdf';
                $path = $license_paths[$input['mat_code']] . '/' . $record->licensed_from . '/';
            }
            $this->validate($request, [
                'attachment' => 'required|mimes:' . $allowed
            ]);
            $input['attachment']->storeAs('/', $record->_id . $save_as);
            $file_handler->uploadFile('app/' . $record->_id . $save_as, 'licensed', $path);
        }
         
        if ($input['cover']) {
            $input['cover']->storeAs('/', $record->_id . '.jpg');
            $file_handler->uploadFile('app/' . $record->_id . '.jpg', 'covers');
            Artisan::call('aws:invalidate', ['paths' => ['/cover/200/'.$record->_id.'.jpg']]);
        }

        $couch = resolve('Couchdb');
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
