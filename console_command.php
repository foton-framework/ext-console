<?php


class EXT_Console_Command
{
	//--------------------------------------------------------------------------

	protected $key      = '';
	public $description = '';
	public $commands    = '';

	//--------------------------------------------------------------------------

	public function __construct($command)
	{
		$this->console =& $command->console;
	}

	//--------------------------------------------------------------------------

	public function key($key = NULL, $description = NULL)
	{
		if ($key)
		{
			$this->key = $key;
			if ($description) $this->description = $description;
		}

		return $this->key;
	}

	//--------------------------------------------------------------------------

	public function command($cmd, $cmd_description)
	{
		$this->commands[$cmd] = array(
			'description' => $cmd_description,
			'arguments'   => array(),
			'required'    => array()
		);
	}

	//--------------------------------------------------------------------------

	public function add_argument($cmd, $arg, $arg_description, $requiered = FALSE)
	{
		$this->commands[$cmd]['arguments'][$arg] = $arg_description;
		$this->commands[$cmd]['required'][$arg]  = (bool)$requiered;
	}

	//--------------------------------------------------------------------------

}