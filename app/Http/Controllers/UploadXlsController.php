<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Rhino\User;

class UploadXlsController extends Controller
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$users = [];
		$file = request()->file('xlsFile');

		//
		// Confirm that a file was selected
		//
		if ($file == null) {
			return view('welcome')->with([
				'result' => 'fail',
				'message' => 'Please select an xlsx file first, before pressing the Import button.',
				'users' => $users
			]);
		}

		//
		// Confirm type
		//
		$ext = $file->extension();
		if ($ext != 'xlsx') {
			return view('welcome')->with([
				'result' => 'fail',
				'message' => 'Sorry, expected an xlsx file. Instead got: ' . $ext,
				'users' => $users
			]);
		}

		//
		// Store temp file
		//
		$storeAs = $file->getClientOriginalName();
		$file->storeAs('xlsFiles/', $storeAs);

		//
		// Read file and create array of Users
		//
		$sheets = Excel::toArray(new UsersImport, 'xlsFiles/' . $storeAs);
		foreach ($sheets[0] as &$rows) {
			$user = User::makeWithDetails($rows);
			if ($user) {
				$users[] = $user;
			}
		}

		//
		// Totals
		//
		$totalValid = 0;
		foreach ($users as &$user) {
			if ($user->validateAll()) {
				++$totalValid;
			}
		}

		//
		// Load data in View
		//
		return view('welcome')->with([
			'result' => 'success',
			'users' => $users,
			'total' => count($users),
			'totalValid' => $totalValid
		]);
	}
}
