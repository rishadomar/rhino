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
		$users = [];
		$file = request()->file('xlsFile');
		if ($file == null) {
			//return back()->with('error', 'Oops no selected file.');
			return view('welcome')->with([
				'result' => 'fail',
				'message' => 'Oops no selected file.',
				'users' => $users
			]);
		}
		$ext = $file->extension();
		if ($ext != 'xlsx') {
			//return back()->with('error', 'Oops expected an xlsx file.');
			return view('welcome')->with([
				'result' => 'fail',
				'message' => 'Oops expected an XLSX file.',
				'users' => $users
			]);
		}
		$file->storeAs('xlsFiles/', 'names.xlsx');

		$details = Excel::toArray(new UsersImport, 'xlsFiles/names.xlsx');
		foreach ($details[0] as &$detail) {
			$user = User::makeWithRow($detail);
			if ($user) {
				$users[] = $user;
			}
		}

		//return Redirect::route('', array('users' => $users));

		//return back()->with('users', $users);

		return view('welcome')->with([
			'result' => 'success',
			'users' => $users
		]);

		//return back()->with('success','Image Upload successful');
	}
}
