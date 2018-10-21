<?php
namespace App\Rhino;

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

	/**
	 * User constructor.
	 * @param $firstName
	 * @param $surname
	 * @param $email
	 * @param $contact
	 * @param $joinDate
	 */
	public function __construct($firstName, $surname, $email, $contact, $joinDate)
	{
		$this->firstName = $firstName;
		$this->surname = $surname;
		$this->email = $email;
		$this->contact = new Contact($contact);
		$this->joinDate = $joinDate;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	static private function validateName($name)
	{
		if ($name === null || strlen($name) == 0) {
			return false;
		}

		// Match for case insensitive alpha only + whitespace + - (hypen)
		return preg_match('/^[A-Z\s\-]+$/i', $name) === 1 ? true : false;
	}

	/**
	 * @return bool
	 */
	public function validateFirstName()
	{
		return self::validateName($this->firstName);
	}

	/**
	 * @return string
	 */
	public function getFirstNameForPrinting()
	{
		if ($this->validateFirstName()) {
			return $this->firstName;
		}
		return strlen($this->firstName) == 0 ? '(missing)' : $this->firstName;
	}

	/**
	 * @return bool
	 */
	public function validateSurname()
	{
		return self::validateName($this->surname);
	}

	/**
	 * @return string
	 */
	public function getSurnameForPrinting()
	{
		if ($this->validateSurName()) {
			return $this->surname;
		}
		return strlen($this->surname) == 0 ? '(missing)' : $this->surname;
	}

	/**
	 * @return bool
	 */
	public function validateContact()
	{
		return $this->contact->validate();
	}

	/**
	 * @return string
	 */
	public function getContactForPrinting()
	{
		return $this->contact->getValueForPrinting();
	}

	/**
	 * @return mixed
	 */
	public function validateEmail()
	{
		return filter_var($this->email, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * @return string
	 */
	public function getEmailForPrinting()
	{
		if ($this->validateEmail()) {
			return $this->email;
		}
		return strlen($this->email) == 0 ? '(missing)' : $this->email;
	}

	/**
	 * @return bool
	 */
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

	/**
	 * @return string
	 */
	public function getJoinDateForPrinting()
	{
		if ($this->validateJoinDate()) {
			$m = new \DateTime($this->joinDate);
			return $m->format('Y-m-d');
		}
		return strlen($this->joinDate) == 0 ? '(missing)' : $this->joinDate;
	}

	/**
	 * Make an instance given details in an array.
	 * May return null if details are not according to required specification.
	 *
	 * @param array $details
	 * @return User|null
	 */
	static public function makeWithDetails(array $details)
	{
		//
		// Check for Empty
		//
		if ($details[0] == null || $details[0] == '') {
			return null;
		}

		//
		// Skip Province
		//
		if ($details[1] == null || $details[1] == '') {
			return null;
		}

		//
		// Check for Header
		//
		if ($details[0] == 'Name') {
			return null;
		}

		//
		// Assume rest of the details are required values
		//
		$name = $details[self::NameColumn];
		$p = strpos($name, ' ');
		if ($p === false) {
			$firstName = $name;
			$surname = null;
		} else {
			$firstName = substr($name, 0, $p);
			$surname = substr($name, $p);
		}

		$contact = $details[self::ContactColumn];

		$email = $details[self::EmailColumn];

		$joinDate = $details[self::JoinDateColumn];

		return new User($firstName, $surname, $email, $contact, $joinDate);
	}

	/**
	 * Check all fields
	 *
	 * @return bool
	 */
	public function validateAll()
	{
		return $this->validateFirstName() && $this->validateSurname() && $this->validateEmail() && $this->validateContact() && $this->validateJoinDate();
	}
}
