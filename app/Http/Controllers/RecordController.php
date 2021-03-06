<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
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
        $licenses = License::all()->mapWithKeys(function ($license) {
            return [$license['license_slug'] => $license['license_slug']];
        })->all();
        // give blank option empty key so it won't be counted as set if no license selected
        $this->licenses = array_merge(['' => ''], $licenses);
    }

    private function process_form_file_uploads($files, $id, $mat_code = '', $licensed_from = '')
    {
        set_time_limit(0);
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

        // process and upload vid files
        if ($mat_code == 'zm') {
            $save_as = '.mp4';
            $path = $license_paths[$mat_code] . '/' . $licensed_from . '/';
            $files->storeAs('/', $id . $save_as);
            $file_handler->uploadFile('app/' . $id . $save_as, 'licensed', $path);
        }
        
        // process and upload audio files
        if ($mat_code == 'z' || $mat_code == 'za') {
            $path = $license_paths[$mat_code] . '/' . $licensed_from . '/' . $id . '/derivatives/';
            
            foreach ($files as $key => $track) {
                $track_split = explode('.', $track->getClientOriginalName())[0];
                $track_name = Str::slug($track_split, '-') . '.mp3';
                $track->storeAs("music/$id/derivatives/tracks", $track_name);
                $file_handler->uploadFile("app/music/$id/derivatives/tracks/$track_name", 'licensed', $path . 'tracks/');
            }
            Artisan::call('process:mp3', ['couchid' => $id]);
            Artisan::call('process:mp3:metadata', ['couchid' => $id]);
            $file_handler->uploadFile('app/music/' . $id . '/derivatives/' . $id . '.zip', 'licensed', $path);
        }
    }

    private function process_record($id = null, $request)
    {
        $this->validate($request, [
            'cover' => 'sometimes|nullable|mimes:jpg,jpeg',
            'title' => 'required'
        ]);

        $input = $request->all();

        if (!$id) {
            $record = new \stdClass;
            $record->_id = Str::slug($input['title'], '-');
            $record->bib_created = date('Y-m-d');
        } else {
            $record = $this->couch->getDoc($id);
        }
        $record->bib_lastupdate = date('Y-m-d');
        if (isset($input['title'])) {
            $record->title = $input['title'];
        }
        if (isset($input['author'])) {
            $record->author = $input['author'];
        }
        if (isset($input['artist'])) {
            $record->artist = $input['artist'];
        }
        if (isset($input['tagline'])) {
            $record->tagline = trim($input['tagline']);
        } else {
            unset($record->tagline);
        }
        if (isset($input['notes'])) {
            $record->notes = explode("\r\n", $input['notes']);
        }
        if (isset($input['lexile'])) {
            $record->reading_level = $record->reading_level ?? new \stdClass;
            $record->reading_level->lexile = $input['lexile'];
        }
        
        if (isset($input['series'])) {
            $record->series = explode("\r\n", $input['series']);
        } else {
            unset($record->series);
        }
        if (isset($input['subjects'])) {
            $record->subjects = explode("\r\n", $input['subjects']);
        } else {
            unset($record->subjects);
        }
        if (isset($input['license_slug'])) {
            $record->licensed_from = $input['license_slug'];
        }
        if (isset($input['mat_code'])) {
            $record->mat_code = $input['mat_code'];
        }
        if (isset($input['pub_year'])) {
            $record->pub_year = $input['pub_year'];
        }
        if (isset($input['stdnum'])) {
            $record->stdnum = explode("\r\n", $input['stdnum']);
        }
        $record->active = (isset($input['is_active']) ? 1 : 0);
        $record->flags = $record->flags ?? new \stdClass;
        $record->flags->protected = (isset($input['is_protected']) ? 1 : 0);
        $record->flags->public_domain = (isset($input['is_public_domain']) ? 1 : 0);
        $record->disable_requests = (isset($input['not_requestable']) ? 1 : 0);
        if (isset($input['documentation'])) {
            $record->documentation = explode("\r\n", $input['documentation']);
        } else {
    	    unset($record->documentation);
        }
        if (isset($input['contents'])) {
            $record->contents = explode("\r\n", $input['contents']);
        }
        if (isset($input['related_links'])) {
            $record->related_links = explode("\r\n", $input['related_links']);
        } else {
            unset($record->related_links);
        }
        if (isset($input['accessories'])) {
            $record->accessories = explode("\r\n", $input['accessories']);
        } else {
            unset($record->accessories);
        }
        if (isset($input['specifications'])) {
          $record->specifications = explode("\r\n", $input['specifications']);
        } else {
          unset($record->specifications);
        }
        if (isset($input['casing'])) {
          $record->casing = explode("\r\n", $input['casing']);
        } else {
          unset($record->casing);
        }
        if (isset($input['ages'])) {
            $record->ages = $input['ages'];
        }

        // grab and upload the cover image if provided
        if (isset($input['cover'])) {
            $this->process_form_file_uploads($input['cover'], $record->_id, 'cover');
        }

        // grab and upload catalog guide if attached
        if (isset($input['cat_guide'])) {
            // $this->process_form_file_uploads($input['cat_guide'], $record->_id, 'cover');
            $filename = $input['cat_guide']->getClientOriginalName();
            Storage::disk('catalog_guides')->put($filename, fopen($input['cat_guide'], 'r+'));
            $record->documentation[] = "https://aadl.org/files/catalog_guides/$filename";
        }

        $license_paths = config('license_paths');
        // if attachments do extra validation on the file and then upload it
        if (isset($input['attachment'])) {
            if ($input['mat_code'] == 'zb' || $input['mat_code'] == 'zp') {
                $allowed = 'pdf';
            } elseif ($input['mat_code'] == 'zm') {
                $allowed = 'mp4,mov';
            }
            // $this->validate($request, [
            //     'attachment' => 'required|mimes:' . $allowed
            // ]);
            $this->process_form_file_uploads($input['attachment'], $record->_id, $input['mat_code'], $record->licensed_from);
        }

        if (isset($input['track-file'])) {
            $allowed = 'mp3';
            // $this->validate($request, [
            //     'track-file' => 'required|mimes:' . $allowed
            // ]);
            foreach ($input['track-file'] as $track) {
                $track_title = explode('.', $track->getClientOriginalName())[0];
                $track_title = str_replace('_', '', $track_title);
                $track_num = substr($track_title, 0, 3);
                $track_num = (int) preg_replace('/\D/', '', $track_num);
                $track_title = trim(substr($track_title, 2));
                if (!isset($record->tracks)) {
                    $record->tracks = new \stdClass;
                }
                $record->tracks->$track_num = new \stdClass;
                $record->tracks->$track_num->title = $track_title;
            }
        }

        // update track titles
        if (isset($input['edit-track-title'])) {
            foreach ($input['edit-track-title'] as $num => $track_title) {
                $record->tracks->$num->title = trim($track_title);
            }
        }

        // update game codes
        if (isset($input['edit-gamecode'])) {
            unset($record->gamecodes);
            $record->gamecodes = new \stdClass;
            foreach ($input['edit-gamecode'] as $game_term => $codes) {
                $record->gamecodes->$game_term = [];
                foreach ($codes as $code) {
                    $record->gamecodes->$game_term[] = $code;
                }
            }
        } elseif (isset($record->gamecodes)) {
            unset($record->gamecodes);
        }

        try {
            $this->couch->storeDoc($record);
            $request->session()->flash('status', 'Record saved successfully! View it <a href="https://aadl.org/catalog/record/' . $record->_id . '" target="_blank">here</a>.');
        } catch (Exception $e) {
            $this->error("Saving record failed : " . $e->getMessage());
        }

        // tracks need to be processed after an initial record fields are already processed
        if (isset($input['track-file'])) {
            $this->process_form_file_uploads($input['track-file'], $record->_id, $input['mat_code'], $record->licensed_from);
        }

        return $record;
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
        $record = $this->process_record(null, $request);
        return redirect('record/' . $record->_id . '/edit');
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
        $protect_evg_fields = (strpos($record->mat_code, 'z') !== false) ? '' : 'readonly';
        return view('record.edit', compact('mat_types', 'licenses', 'record', 'protect_evg_fields'));
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
        return redirect('record/' . $id . '/edit');
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

    public function updateCover(Request $request, $id)
    {
        $record = $this->couch->getDoc($id);
        $title = $record->title;
        $input = $request->all();
        if (isset($input['cover'])) {
            try {
                $this->process_form_file_uploads($input['cover'], $id, 'cover');
                $request->session()->flash('status', 'Cover updated successfully!');
            } catch (Exception $e) {
                $this->error("Updating cover failed : " . $e->getMessage());
            }
        }

        return view('record.cover', compact('id', 'title'));
    }
}
