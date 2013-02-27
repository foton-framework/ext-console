<?php



class Components_Command extends EXT_Console_Command
{

	//--------------------------------------------------------------------------

	public function init()
	{
		$this->key('com');
		$this->command('make', 'Создать компонент');
		$this->add_argument('make', 'name', 'Имя компонента', true);
	}

	//--------------------------------------------------------------------------

	public function cmd_make($name)
	{
		if ( ! $name) return;

		$com_path       = COM_PATH . $name . DS;
		$com_views_path = COM_PATH . $name . DS . 'views' . DS;

		if (file_exists($com_path))
		{
			$this->console->red('Компонент уже существует!');
			return;
		}

		$src_dir = dirname(__FILE__) . DS . 'source' . DS;

		$dest = array(
			'component' => $src_dir . 'component' . EXT,
			'model'     => $src_dir . 'model' . EXT,
			'list_view' => $src_dir . 'model' . EXT,
			'full_view' => $src_dir . 'model' . EXT,
		);

		$replcaes = array(
			'{com}' => $name,
			'{Com}' => ucfirst($name),
		);

		foreach ($dest as $key => $file)
		{
			$dest[$key] = str_replace(array_keys($replcaes), $replcaes, file_get_contents($file));
		}


		mkdir($com_path);
		mkdir($com_views_path);

		file_put_contents($com_path . $name . COMPONENT_EXT, $dest['component']);
		file_put_contents($com_path . $name . MODEL_EXT, $dest['model']);
		file_put_contents($com_path . DS . 'views' . DS . 'list' . VIEW_EXT, $dest['list_view']);
		file_put_contents($com_path . DS . 'views' . DS . 'full' . VIEW_EXT, $dest['full_view']);

		$this->console->green('Компонент создан!');
	}

	//--------------------------------------------------------------------------


}