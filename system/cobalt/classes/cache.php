<?php

class Cache {

	/**
	 * Loads a cache file
	 *
	 * @param	string
	 * @return	mixed
	 */
	public function get($file, $life = 0)
	{
		return new Cache_Singleton($file, $life);
	}

	// --------------------------------------------------------------------

	/**
	 * Deletes all files within the cache
	 *
	 * @return	void
	 */
	public function clear()
	{
		foreach(glob(SYS_PATH.'cobalt/storage/cache/*.*') as $file)
		{
			@unlink($file);
		}
	}
}

class Cache_Singleton {

	private $file  = NULL;
	private $life  = 0;
	private $valid = NULL;

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct($file, $life)
	{
		$this->file = SYS_PATH.'cobalt/storage/cache/'. md5($file);
		$this->life = $life * 60;
	}

	// --------------------------------------------------------------------

	/**
	 *
	 * Finds if the current cached content, if it exists, is valid
	 *
	 * @return	boolean
	 */
	public function is_valid()
	{
		$this->valid = FALSE;

		if(file_exists($this->file))
		{
			// If the life is 0, a lifetime was never set and the cache
			// will live on forever! :')
			if($this->life == 0)
			{
				$this->valid = TRUE;
			}

			else
			{
				// Check if the last time the file was modified was
				// within the lifetime
				if(filemtime($this->file) > (time() - $this->life))
				{
					$this->valid = TRUE;
				}		
			}
		}

		return $this->valid;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetches the cache's content. If the content is no longer valid,
	 * this will automatically return FALSE
	 *
	 * @return	mixed
	 */
	public function content()
	{
		if(is_null($this->valid))
		{
			$this->is_valid();
		}

		if($this->valid)
		{
			$fh = fopen($this->file, 'r');

			$content = @fread($fh, filesize($this->file));

			fclose($fh);

			return unserialize($content);
		}

		else
		{
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Saves the content to the cache file
	 *
	 * @param	string
	 * @return	void
	 */
	public function save($content)
	{
		$fh = fopen($this->file, 'w+');

		fwrite($fh, serialize($content));
		fclose($fh);		
	}

	/**
	 * Deletes the current cache file
	 *
	 * @return	void
	 */
	public function clear()
	{
		@unlink($this->file);
	}
}