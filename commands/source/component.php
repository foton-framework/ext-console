<?php



class COM_{Com} extends SYS_Component
{
	//--------------------------------------------------------------------------

	public $com_name = '{com}';
	public $com_url  = '/{com}/';

	//--------------------------------------------------------------------------

	function init()
	{
		$this->model =& model($this->com_name);
	}

	//--------------------------------------------------------------------------

	function index()
	{
		// Get data
		$data = $this->model->result();

		// Template
		$this->template->title($this->model->name);
		$this->template->h1($this->model->name);

		// VIEW
		$this->view = 'list';
		$this->data['data'] =& $data;
	}

	//--------------------------------------------------------------------------

	function router($id)
	{
		$key = is_numeric($id) ? 'id' : 'id_key';

		$data = $this->model->$key($id)->row();

		// 404
		if ( ! $data) return sys::error_404();

		// 301 redirect
		if ($data->id_key && $data->id_key != $data->id && $key == 'id')
		{
			hlp::redirect($data->full_link, 301);
			exit;
		}

		// Template
		$this->template->title($data->title);
		$this->template->h1($data->title);

		// VIEW
		$this->view = 'full';
		$this->data['data'] =& $data;
	}

	//--------------------------------------------------------------------------

}