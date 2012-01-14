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
		return $this->db->query('SELECT * FROM posts WHERE id = ?', $id)->row();
	}

	/**
	 * Returns comments for a specific post
	 *
	 * @param  int    $id
	 * @return array
	 */
	public function comments($id)
	{
		$query = $this->db->query('SELECT * FROM comments WHERE post_id = ? ORDER BY id DESC', $id);

		return $query->results();
	}

	/**
	 * Adds a comment to a post
	 *
	 * @param  int     $id
	 * @param  string  $content
	 */
	public function add_comment($id, $content)
	{
		$query = 'INSERT INTO comments (post_id, content) VALUES (?, ?)';

		$this->db->query($query, array($id, $content));
	}
}