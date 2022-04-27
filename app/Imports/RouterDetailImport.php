<?php

namespace App\Imports;

use App\RouterDetail;
use Maatwebsite\Excel\Concerns\ToModel;

class RouterDetailImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new RouterDetail([
        //     'sapid' => $row[0], 
        //     'hostname' => $row[1], 
        //     'loopback' => $row[2],
        //     'macaddress' => $row[3],
        // ]);
    }
}