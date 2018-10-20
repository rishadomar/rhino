<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadXlsController extends Controller
{

	public function index()
	{
		$file = request()->file('xlsFile');
		$ext = $file->extension();
		if ($ext != 'xlsx') {
			return back()->withErrors(['Oops expected an xlsx file.']);
		}
		$file->storeAs('xlsFiles/', 'names2.xlsx');
		return back()->with('success','Image Upload successful');
	}
}
