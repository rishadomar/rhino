<?php
namespace App\Rhino;

use App\Rhino\Contact;

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
		$this->contact = new Contact($contact);
		$this->joinDate = $joinDate;
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
			if (strlen($this->firstName) == 0) {
				return '(missing)';
			}
			return $this->firstName;
		}
	}

	public function validateSurname()
	{
		return $this->surname;
	}

	public function getSurnameForPrinting()
	{
		if ($this->validateSurName()) {
			return $this->surname;
		} else {
			if (strlen($this->surname) == 0) {
				return '(missing)';
			}
			return $this->surname;
		}
	}

	public function validateContact()
	{
		return $this->contact->validate();
	}

	public function getContactForPrinting()
	{
		return $this->contact->getValueForPrinting();
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
			if (strlen($this->email) == 0) {
				return '(missing)';
			}
			return $this->email;
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
			if (strlen($this->joinDate) == 0) {
				return '(missing)';
			}
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

	public function getTextStyle($field)
	{
		$function = 'validate' . $field;
		$v = false;
		if (is_callable([$this, $function])) {
			$v = $this->$function();
		}
		return $v ? '' : '#ff0000';
	}

	public function validateAll()
	{
		return $this->validateFirstName() && $this->validateSurname() && $this->validateEmail() && $this->validateContact() && $this->validateJoinDate();
	}
}
