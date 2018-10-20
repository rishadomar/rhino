<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class XlsUser {
	public $name;
	public $email;

	public function __construct($name, $email)
	{
		$this->name = $name;
		$this->email = $email;
	}

}

class UploadXlsController extends Controller
{

	public function index()
	{
		$file = request()->file('xlsFile');
		$ext = $file->extension();
		if ($ext != 'xlsx') {
			return back()->withErrors(['Oops expected an xlsx file.']);
		}
		$file->storeAs('xlsFiles/', 'names.xlsx');

		$details = Excel::toArray(new UsersImport, 'xlsFiles/names.xlsx');
		$users = [];
		foreach ($details[0] as &$detail) {
			$name = $detail[0];
			$email = $detail[3];
			$users[] = new XlsUser($name, $email);
		}

		return view('users')->with([
			'users' => $users
		]);

		//return back()->with('success','Image Upload successful');
	}
}
