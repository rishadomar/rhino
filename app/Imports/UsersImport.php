<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
	        'name' => $row[0],
	        'publication' => $row[1],
	        'contactNumber' => $row[2],
	        'email' => $row[3],
	        'joinDate' => $row[4]
        ]);
    }
}
