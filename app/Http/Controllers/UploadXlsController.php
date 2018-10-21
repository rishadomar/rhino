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
			return view('welcome')->with([
				'result' => 'fail',
				'message' => 'Oops expected an xlsx file.',
				'users' => $users
			]);
		}
		$storeAs = $file->getClientOriginalName();
		$file->storeAs('xlsFiles/', $storeAs);

		$details = Excel::toArray(new UsersImport, 'xlsFiles/' . $storeAs);
		foreach ($details[0] as &$detail) {
			$user = User::makeWithRow($detail);
			if ($user) {
				$users[] = $user;
			}
		}

		return view('welcome')->with([
			'result' => 'success',
			'users' => $users
		]);
	}
}
