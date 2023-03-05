<?php

namespace App\Imports;

use App\JneOrigin;
use Maatwebsite\Excel\Concerns\ToModel;

class OriginImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new JneOrigin([
            'origin_code' => $row[0],
            'origin_name' => $row[1],
        ]);
    }
}
