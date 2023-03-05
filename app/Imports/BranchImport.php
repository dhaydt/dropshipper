<?php

namespace App\Imports;

use App\JneBranch;
use Maatwebsite\Excel\Concerns\ToModel;

class BranchImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new JneBranch([
            'branch_code' => $row[0],
            'branch_name' => $row[1],
        ]);
    }
}
