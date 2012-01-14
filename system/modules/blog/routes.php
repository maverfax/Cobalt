<?php namespace blog;

use \Form_Validation;

class Routes extends \Cobalt {

	public function index()
	{
		$posts = $this->blog->model->posts();

		$this->blog->view('index', compact('posts'));
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

		$this->blog->view('post', compact('post', 'form'));
	}
}