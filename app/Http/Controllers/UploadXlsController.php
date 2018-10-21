<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Rhino\User;


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
			$user = User::makeWithRow($detail);
			if ($user) {
				$users[] = $user;
			}
		}

		//return Redirect::route('', array('users' => $users));

		//return back()->with(['users' => $users]);

		return view('welcome')->with([
			'users' => $users
		]);

		//return back()->with('success','Image Upload successful');
	}
}
