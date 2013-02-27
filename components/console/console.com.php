<?php



class EXT_COM_Console extends SYS_Component
{
	//--------------------------------------------------------------------------

	private $commands = array(
		'components_command',
	);

	//--------------------------------------------------------------------------

	public function init()
	{
		$this->load->extension('Console');

		$dir = realpath(dirname(__FILE__)) . DS;

		foreach ($this->commands as $cmd)
		{
			$this->console->command_class($cmd);
		}
	}

	//--------------------------------------------------------------------------

	function router()
	{
		$this->console->run();
	}

	//--------------------------------------------------------------------------

}