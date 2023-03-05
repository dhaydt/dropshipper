<?php

namespace App\Imports;

use App\JneDest;
use Maatwebsite\Excel\Concerns\ToModel;

class DestImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new JneDest([
            'country' => $row[0],
            'province' => $row[1],
            'city' => $row[2],
            'district' => $row[3],
            'sub_district' => $row[4],
            'zip' => $row[5],
            'tarif_code' => $row[6],
        ]);
    }
}
