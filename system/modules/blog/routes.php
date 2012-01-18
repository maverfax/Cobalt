<?php namespace blog;

use \Form_Validation;

class Routes extends \Cobalt {

	public function index()
	{
		$posts = $this->blog->model->posts();

		return compact('posts');
	}

	public function post()
	{
		// Redirect if we are not given a numerical ID
		if( ! ($id = $this->request->segment(3)) || ! is_numeric($id))
		{
			redirect();
		}

		// Redirect if the page does not exist
		if( ! ($post = $this->blog->model->post($id)))
		{
			redirect();
		}

		$form = new \Form_Validation(array(
			'content' => array('required'),
		));

		if($form->run())
		{
			$this->blog->model->add_comment($id, $form->input('content'));
		}

		$comments = $this->blog->model->comments($id);

		return compact('form', 'post', 'comments');
	}
}