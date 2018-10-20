<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class User {
	const NameColumn = 0;
	const ContactColumn = 2;
	const EmailColumn = 3;
	const JoinDateColumn = 6;
	private $firstName;
	private $surname;
	private $email;
	private $contact;
	private $joinDate;

	public function __construct($firstName, $surname, $email, $contact, $joinDate)
	{
		$this->firstName = $firstName;
		$this->surname = $surname;
		$this->email = $email;
		$this->contact = $contact; // e164 format
		$this->joinDate = $joinDate; // yyyy-mm-dd format
	}

	public function validateFirstName()
	{
		return $this->firstName;
	}

	public function getFirstNameForPrinting()
	{
		if ($this->validateFirstName()) {
			return $this->firstName;
		} else {
			return $this->firstName;
		}
	}

	public function validateSurname()
	{
		return $this->surname;
	}

	public function getForPrinting()
	{
		if ($this->validateSurName()) {
			return $this->surname;
		} else {
			return $this->surname;
		}
	}

	public function validateContact()
	{
		return $this->contact;
	}

	public function getContactForPrinting()
	{
		if ($this->validateContact()) {
			return $this->contact;
		} else {
			return $this->contact;
		}
	}

	public function validateEmail()
	{
		return filter_var($this->email, FILTER_VALIDATE_EMAIL);
	}

	public function getEmailForPrinting()
	{
		if ($this->validateEmail()) {
			return $this->email;
		} else {
			return $this->email . 'xxxx';
		}
	}

	public function validateJoinDate()
	{
		if ($this->joinDate === null || strlen($this->joinDate) == 0) {
			return false;
		}
		try {
			$m = new \DateTime($this->joinDate);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function getJoinDateForPrinting()
	{
		if ($this->validateJoinDate()) {
			$m = new \DateTime($this->joinDate);
			return $m->format('Y-m-d');
		} else {
			return $this->joinDate;
		}
	}

	static public function makeWithRow($row)
	{
		//
		// Check for Empty
		//
		if ($row[0] == null || $row[0] == '') {
			return null;
		}

		//
		// Skip Province
		//
		if ($row[1] == null || $row[1] == '') {
			return null;
		}

		//
		// Check for Header
		//
		if ($row[0] == 'Name') {
			return null;
		}

		$name = $row[self::NameColumn];
		$p = strpos($name, ' ');
		if ($p === false) {
			$firstName = $name;
			$surname = null;
		} else {
			$firstName = substr($name, 0, $p);
			$surname = substr($name, $p);
		}

		$contact = $row[self::ContactColumn];

		$email = $row[self::EmailColumn];

		$joinDate = $row[self::JoinDateColumn];

		return new User($firstName, $surname, $email, $contact, $joinDate);
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
			$user = User::makeWithRow($detail);
			if ($user) {
				$users[] = $user;
			}
		}

		return view('users')->with([
			'users' => $users
		]);

		//return back()->with('success','Image Upload successful');
	}
}
