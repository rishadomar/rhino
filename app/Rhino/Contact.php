<?php
namespace App\Rhino;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Contact
{
	static private $phoneUtil = false;
	private $number;
	private $valid;

	/**
	 * Contact constructor.
	 * @param $number
	 */
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

	/**
	 * @return bool
	 */
	public function validate()
	{
		return $this->valid;
	}

	/**
	 * @return string
	 */
	public function getValueForPrinting()
	{
		if ($this->valid) {
			return self::$phoneUtil->format($this->number, PhoneNumberFormat::E164);
		}
		return strlen($this->number) == 0 ? '(missing)' : $this->number;
	}
}