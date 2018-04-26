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

    public function __construct()
    {
        $this->couch = resolve('Couchdb');
        $this->mat_types = config('mat_types');
        $licenses = License::all()->mapWithKeys(function($license) {
            return [$license['license_slug'] => $license['license_slug']];
        })->all();
        // give blank option empty key so it won't be counted as set if no license selected
        $this->licenses = array_merge(['' => ''], $licenses);
    }

    private function process_form_file_uploads($files, $id, $mat_code = '', $licensed_from = '') 
    {
        $file_handler = new FileHandler;
        $license_paths = config('license_paths');

        // process and upload cover
        if ($mat_code == 'cover') {
            $files->storeAs('/', $id . '.jpg');
            $file_handler->uploadFile('app/' . $id . '.jpg', 'covers');
            Artisan::call('aws:invalidate', ['paths' => ['/cover/200/' . $id . '.jpg']]);
        }

        // process and upload pdfs
        if ($mat_code == 'zb' || $mat_code == 'zp') {
            $save_as = '.pdf';
            $path = $license_paths[$mat_code] . '/' . $licensed_from . '/';
            $files->storeAs('/', $id . $save_as);
            $file_handler->uploadFile('app/' . $id . $save_as, 'licensed', $path);
        }
        
        // process and upload audio files
        if ($mat_code == 'z' || $mat_code == 'za') {
            $path = $license_paths[$mat_code] . '/' . $licensed_from . '/derivatives/';
            foreach ($files as $key => $track) {
                $track->storeAs('/music/' . $id . '/derivatives/tracks', $track->getClientOriginalName());
                $file_handler->uploadFile('app/music/' . $id . '/derivatives/' . $id . '.mp3', 'licensed', $path . 'tracks/');
            }
            Artisan::call('process:mp3', ['couchid' => $id]);
            Artisan::call('process:mp3:metadata', ['couchid' => $id]);
            $file_handler->uploadFile('app/music/' . $id . '/derivatives/' . $id . '.zip', 'licensed', $path);
        }
    }

    private function process_record($id = NULL, $request) 
    {
        $this->validate($request, [
            'cover' => 'sometimes|nullable|mimes:jpg,jpeg',
            'title' => 'required'
        ]);

        $input = $request->all();

        if (!$id) {
            $record = new \stdClass;
            $record->_id = str_slug($input['title'], '-');
            $record->bib_created = date('Y-m-d');
        } else {
            $record = $this->couch->getDoc($id);
        }

        $record->bib_lastupdate = date('Y-m-d');
        $record->title = $input['title'];
        if (isset($input['license_slug'])) {
           $record->licensed_from = $input['license_slug']; 
        }
        $record->mat_code = $input['mat_code'];
        $record->pub_year = $input['pub_year'];
        if (isset($input['notes'])) {
           $record->notes = explode("\n\n", $input['notes']); 
        }
        $record->active = (isset($input['is_active']) ? 1 : 0);
        if (isset($input['documentation'])) {
            $record->documentation = explode("\n\n", $input['notes']);
        }

        // grab and upload the cover image if provided
        if (isset($input['cover'])) {
            dd($input['cover']);
            $this->process_form_file_uploads($input['cover'], $record->_id, 'cover');
        }

        $license_paths = config('license_paths');
        // if attachments do extra validation on the file and then upload it
        if (isset($input['attachment'])) {
            if ($input['mat_code'] == 'zb' || $input['mat_code'] == 'zp') {
                $allowed = 'pdf';
                $this->validate($request, [
                    'attachment' => 'required|mimes:' . $allowed
                ]);
                $this->process_form_file_uploads($input['attachment'], $record->_id, $input['mat_code'], $record->licensed_from);
            }
        }

        try {
            $this->couch->storeDoc($record);
        } catch (Exception $e) {
            $this->error("Saving record failed : " . $e->getMessage());
        }

        // tracks need to be processed after an initial record fields are already processed
        if (isset($input['track-file'])) {
            $this->process_form_file_uploads($input['track-file'], $record->_id, $input['mat_code'], $record->licensed_from);
        }
    }

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
        $mat_types = $this->mat_types['downloads'];
        $licenses = $this->licenses;
        $license_slug = $request->input('license_slug') ?? '';
        return view('record.create', compact('mat_types', 'licenses', 'license_slug'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->process_record(NULL, $request);
        // return redirect('record/' . $id);
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
        $mat_types = array_merge($this->mat_types['physical'], $this->mat_types['downloads']);
        $licenses = $this->licenses;
        $record = $this->couch->getDoc($id);
        if (isset($record->tracks)) {
            $record->tracks = (array) $record->tracks;
            ksort($record->tracks);
        }
        return view('record.edit', compact('mat_types', 'licenses', 'record'));
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
        $this->process_record($id, $request);
        // return redirect('record/' . $id);
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
