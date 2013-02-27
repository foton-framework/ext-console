<?php

class stdOut
{
	//--------------------------------------------------------------------------

	private $style_vars = array(
		'reset'     => 0,
		'bold'      => 1,
		'underline' => 4,
		'blink'     => 5,
		'reverse'   => 7,
		'concealed' => 8,

		'bg_black'   => 40,
		'bg_red'     => 41,
		'bg_green'   => 42,
		'bg_yellow'  => 43,
		'bg_blue'    => 44,
		'bg_magenta' => 45,
		'bg_cyan'    => 46,
		'bg_white'   => 47,

		'black'   => 30,
		'red'     => 31,
		'green'   => 32,
		'yellow'  => 33,
		'blue'    => 34,
		'magenta' => 35,
		'cyan'    => 36,
		'white'   => 37,
	);

	private $_def_str = array(
		'hr' => "--------------------------------------------------------------------------\n",
		'br' => "\n",
	);


	//--------------------------------------------------------------------------

	public function _print($msg)
	{
		fputs(STDOUT, $msg);
	}

	//--------------------------------------------------------------------------

	public function &clear()
	{
		$this->_print(`clear`);
		return $this;
	}

	//--------------------------------------------------------------------------

	public function &reset()
	{
		$this->style();
		return $this;
	}

	//--------------------------------------------------------------------------

	public function &style($style = 'reset')
	{
		$this->_print("\33[K\33[" . str_replace(array_keys($this->style_vars), $this->style_vars, $style) . "m");
		return $this;
	}

	//--------------------------------------------------------------------------

	public function &pos($left = 0, $top = 0)
	{
		$this->_print("\33[" . $left . ';' . $top . "H");
		return $this;
	}

	//--------------------------------------------------------------------------

	public function &put($message, $style = FALSE)
	{
		if ($style) $this->style($style);
		$this->_print($message);
		$this->reset();
		return $this;
	}

	//--------------------------------------------------------------------------

	public function &pad($message, $chars = 0)
	{
		$this->_print( str_pad($message, $chars));
		$this->reset();
		return $this;
	}

	//--------------------------------------------------------------------------

	public function &color($color)
	{
		$this->style($color);
		return $this;
	}

	//--------------------------------------------------------------------------

	public function &__call($method, $args)
	{
		if (isset($this->_def_str[$method]))
		{
			$this->_print($this->_def_str[$method]);
		}
		elseif (isset($this->style_vars[$method]))
		{
			if (isset($args[0]))
			{
				$this->put($args[0], $method);
				$this->reset();
			}
			else
			{
				$this->style($method);
			}
		}
		return $this;
	}

	//--------------------------------------------------------------------------
}