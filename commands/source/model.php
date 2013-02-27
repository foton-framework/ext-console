<?php



class MODEL_{Com} extends SYS_Model_Database
{
	//--------------------------------------------------------------------------

	public $com_name = '{com}';
	public $com_url  = '/{com}/';

	public $table = '{com}';
	public $name  = '{Com}';

	public $add_action = TRUE;

	public $thumbs = array(
		'thumb' => array(
			'size' => array(100, 100),
			'dist' => 'files/{com}/t_',
			'crop' => TRUE
		),
		'image' => array(
			'size' => array(200, 200),
			'dist' => 'files/{com}/',
			'crop' => FALSE
		)
	);

	//--------------------------------------------------------------------------

	public function init()
	{
		$this->fields[$this->table] = array(
			'id'     => NULL,
			'views'  => NULL,
			'uid'    => array(
				'default' => $this->user->id
			),
			'status' => array(
				'label'      => 'Статус',
				'default'    => 1,
				'field'      => 'radiogroup',
				'options'    => 'status_list',
				'user_group' => array(1)
			),
			'id_key' => array(
				'label' => 'SEO Url',
				'field' => 'input',
				'rules' => 'trim|alpha_dash|translit[title]|max_length[250]|unique['.$this->table.']'
			),
			'postdate' => array(
				'label'   => 'Дата публикации',
				'default' => time(),
			),
			'title' => array(
				'label'  => 'Заголовок',
				'field'  => 'input',
				'rules'  => 'trim|strip_tags|required|max_length[250]'
			),
			'body' => array(
				'label' => 'Текст статьи',
				'field' => 'html',
				'rules' => 'trim|required|min_length[50]',
			),
			'img' => array(
				'label'   => 'Фото',
				'field'   => 'file',
				'rules'   => 'callback[model.'.$this->com_name.'.upload,img]',
			),
		);
	}

	//--------------------------------------------------------------------------

	public function update_views($id)
	{
		$this->db->where('id=?', $id);
		$this->db->set('views=views+1');
		$this->update();
	}

	//--------------------------------------------------------------------------

	public function prepare_row_result($row)
	{
		$row = parent::prepare_row_result($row);

		$row->full_link = $this->com_url . ($row->id_key ? $row->id_key : $row->id) . '/';

		if (isset($row->img))
		{
			foreach ($this->thumbs as $key => $thumb) $row->$key = '/' . $thumb['dist'] . $row->img;
		}

		// if (isset($row->uid))
		// {
		// 	$row->user = $this->user->model->prepare_row_result($row, $row->uid);
		// }

		return $row;
	}

	//--------------------------------------------------------------------------

	public function get($table = NULL, $where = NULL)
	{
		if ($this->user->group_id != 1)
		{
			$this->db->where($this->table . '.status=1');
		}

		return parent::get($table, $where);
	}

	//--------------------------------------------------------------------------

	public function status_list($val = NULL)
	{
		static $list = array(
			0 => 'Отключен',
			1 => 'Включен'
		);

		if ($val !== NULL) return $list[$val];

		return $list;
	}

	//--------------------------------------------------------------------------

	public function upload($value, $callback, $field)
	{
		$key_value = $this->form->value('id');

		if ( ! $value) return TRUE;

		$this->load->library('upload');
		if ($key_value) $this->load->library('image');

		$this->upload->set_allowed_types('jpg');
		$this->upload->set_max_size(3);


		if ($result = $this->upload->run($field))
		{
			if ($key_value)
			{
				$file_name = $key_value . '.jpg';
				$this->image->set_file_name($file_name);
				$this->image->process($result->full_path, $this->thumbs);
				return $file_name;
			}

			return TRUE;
		}

		$this->form->set_error_message($callback, $this->upload->error($field));
		return FALSE;
	}

	//--------------------------------------------------------------------------

	public function insert($table = NULL, $data = NULL)
	{
		if ( ! $data)
		{
			$data = $_POST;
		}

		$id = parent::insert($table, $data);

		if ($data['img'])
		{
			$this->load->library('image');
			$file_name = $id . '.jpg';
			$this->image->set_file_name($file_name);
			$this->image->process($data['img'], $this->thumbs);

			$this->db->where('id=?', $id);
			$this->update(NULL, array('img'=>$file_name));
		}

		return $id;
	}

	//--------------------------------------------------------------------------
}