<?php
namespace App\Rhino;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Contact
{

	static private $phoneUtil = false;
	private $number;
	private $valid;
	private $reason;

	public function __construct($number)
	{
		if (self::$phoneUtil === false) {
			self::$phoneUtil = PhoneNumberUtil::getInstance();
		}
		try {
			$zaNumber = self::$phoneUtil->parse($number, "ZA");
			$this->valid = self::$phoneUtil->isValidNumber($zaNumber);
			if ($this->valid) {
				$this->number = $zaNumber;
			} else {
				$this->number = $number;
			}
		} catch (NumberParseException $e) {
			$this->number = $number;
			$this->valid = false;
		}
	}

	public function validate()
	{
		return $this->valid;
	}

	public function getValueForPrinting()
	{
		if ($this->valid) {
			return self::$phoneUtil->format($this->number, PhoneNumberFormat::E164);
		} else {
			if (strlen($this->number) == 0) {
				return '(missing)';
			}
			return $this->number;
		}
	}
}