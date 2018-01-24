<?php

namespace Couchcat\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;

class HarvestController extends Controller
{
    public function cacheCover(Request $request)
    {
        $record_id = $request->input('recordid');
        if ($record_id) {
            return Artisan::call('cache:cover', ['record_ids' => [$record_id]]);
        }
        abort(400, 'Missing recordid.');
    }
}
