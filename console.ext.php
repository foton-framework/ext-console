<?php



class EXT_Console
{
	//--------------------------------------------------------------------------

	private $commands = array();

	//--------------------------------------------------------------------------

	public function __construct()
	{
		require_once 'console_command' . EXT;
		require_once 'stdout' . EXT;

		$this->console = new stdOut();
	}

	//--------------------------------------------------------------------------

	public function command_class($class, $path = NULL)
	{
		if ($path === NULL)
		{
			$path = EXT_PATH . 'console/commands/';
		}

		require_once $path . $class . EXT;

		$this->register_command(new $class($this));
	}

	//--------------------------------------------------------------------------

	public function register_command($cmd)
	{
		$cmd->init();
		$this->commands[$cmd->key()] = $cmd;
	}

	//--------------------------------------------------------------------------

	public function run()
	{
		$args = array_splice($GLOBALS['argv'] , 1);

		$this->console->hr();

		if ( ! count($args))
		{
			$this->display_commands();
		}
		else
		{
			@list($key, $cmd) = explode(':', $args[0]);
			$args = array_slice($args, 1);

			if ( ! isset($this->commands[$key]) || !isset($this->commands[$key]->commands[$cmd]))
			{
				$this->console->red('Команда не найдена')->br()->hr();
				exit;
			}

			$required = $this->commands[$key]->commands[$cmd]['required'];

			if (count($required) > count($args))
			{
				$this->console->red('Недостаточно аргументов')->br()->hr();
				foreach ($this->commands[$key]->commands[$cmd]['arguments'] as $arg => $desc)
				{
					$optional = empty($this->commands[$key]->commands[$cmd]['required'][$arg]);
					$this->console->green($optional ? "[$arg]" : $arg)->put(' - ' . $desc . ($optional ? ' (не обязателно)' : ''));
					$this->console->br();
				}
				$this->console->hr();
				exit;
			}

			$cmd = 'cmd_' . $cmd;
			call_user_func_array(array($this->commands[$key], $cmd), $args);
			$this->console->br()->hr();
		}

		$this->console->reset();
	}

	//--------------------------------------------------------------------------

	public function display_commands()
	{
		foreach ($this->commands as $key => &$command)
		{
			$this->console->bg_green()->black(" {$key} ")->br();

			foreach ($command->commands as $cmd => $opt)
			{
				$this->console->green($key)->put(':')->magenta($cmd)->put(' - ' . $opt['description'])->br();
			}
		}
	}

	//--------------------------------------------------------------------------

}