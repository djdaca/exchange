<?php

namespace h4kuna\Exchange\Currency;

use h4kuna\Number;

/**
 * @author Milan Matějček
 */
class Property implements IProperty
{

	/** @var float */
	private $foreing;

	/** @var strning */
	private $code;

	/** @var int */
	private $home;

	/** @var INumberFormat */
	private $format;

	/** @var array */
	private $stack = [];

	/**
	 * Is need added by reference!
	 * @var self
	 */
	public $default;

	public function __construct($home, $code, $foreing)
	{
		$this->home = floatval($home);
		$this->code = strtoupper($code);
		$this->foreing = floatval($foreing);
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getForeing()
	{
		return $this->foreing;
	}

	public function getHome()
	{
		return $this->home;
	}

	public function getRate()
	{
		return ($this->foreing / $this->home) / ($this->default->getForeing() / $this->default->getHome());
	}

	public function getFormat()
	{
		return $this->format;
	}

	public function setFormat(Number\INumberFormat $nf)
	{
		$this->format = $nf;
		return $this;
	}

	/**
	 * Remove first in stack and set up.
	 * @return self
	 */
	public function popRate()
	{
		if ($this->stack) {
			$this->home = array_pop($this->stack);
		}
		return $this;
	}

	/**
	 * Add new rate.
	 * @param type $number
	 * @return self
	 */
	public function pushRate($number)
	{
		array_push($this->stack, $this->home);
		$this->home = $number;
		return $this;
	}

	/**
	 * Set last rate in stack and clear.
	 * @return self
	 */
	public function revertRate()
	{
		if ($this->stack) {
			$this->home = end($this->stack);
			$this->stack = [];
		}
		return $this;
	}

	public function __toString()
	{
		return $this->getCode();
	}

}
