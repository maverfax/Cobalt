<?php namespace blog;

class Model extends \Cobalt {

	/**
	 * Fetches all of the posts from the database
	 *
	 * @return array
	 */
	public function posts()
	{
		$query  = 'SELECT posts.id, posts.title, posts.body, COUNT(comments.id) AS comments ';
		$query .= 'FROM posts LEFT JOIN comments on posts.id = comments.post_id';

		return $this->db->query($query)->results();
	}

	/**
	 * Returns a specific post
	 *
	 * @param  int   $id
	 * @return array
	 */
	public function post($id)
	{
		$query = $this->db->query('SELECT * FROM posts WHERE id = ?', $id);

		if($query->num_rows() > 0)
		{
			$post = $query->row();

			$query  = $this->db->query('SELECT * FROM comments WHERE post_id = ?', $id);

			$post->comments = $query->results();

			return $post;
		}

		else
		{
			return FALSE;
		}
	}
}