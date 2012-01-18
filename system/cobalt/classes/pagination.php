<?php

class Pagination {

	/**
	 * Stores the current page
	 *
	 * @var	integer
	 */
	public static $current_page = null;

	/**
	 * Stores the item offset
	 *
	 * @var	integer
	 */
	public static $offset = 0;

	/**
	 * Stores the number of items per page
	 *
	 * @var	integer
	 */
	public static $per_page = 10;

	/**
	 * Stores the total number of pages
	 *
	 * @var	integer
	 */
	public static $total_pages = 0;

	/**
	 * Stores the template for the pagination
	 *
	 * @var array
	 */
	public static $template = array(
		'wrapper_start'  => '<div class="pagination"> ',
		'wrapper_end'    => ' </div>',
		'previous_start' => '<span class="previous"> ',
		'previous_end'   => ' </span>',
		'next_start'     => '<span class="next"> ',
		'next_end'       => ' </span>',
		'active_start'   => '<span class="active"> ',
		'active_end'     => ' </span>',
	);

	/**
	 * Stores the total number of items
	 *
	 * @var	integer
	 */
	protected static $total_items = 0;

	/**
	 * Stores the maximum amount of links to show
	 *
	 * @var	integer
	 */
	protected static $num_links = 5;

	/**
	 * Stores the URI segmetn containing the page number
	 *
	 * @var	integer
	 */
	protected static $uri_segment = 3;

	/**
	 * Stores the pagination URL
	 *
	 * @var	mixed
	 */
	protected static $pagination_url;

	/**
	 * Sets the configuration for pagination
	 *
	 * @param  array  $config
	 * @return void
	 */
	public static function config($config)
	{
		foreach ($config as $key => $value)
		{
			if($key == 'template')
			{
				static::$template = array_merge(static::$template, $config['template']);

				continue;
			}

			static::${$key} = $value;
		}

		static::initialize();
	}

	/**
	 * Prepares the pagination
	 *
	 * @return array  
	 */
	protected static function initialize()
	{
		static::$total_pages = ceil(static::$total_items / static::$per_page) ?: 1;

		static::$current_page = (int) Cobalt_Base::get('request')->segment(static::$uri_segment);

		if (static::$current_page > static::$total_pages)
		{
			static::$current_page = static::$total_pages;
		}

		elseif (static::$current_page < 1)
		{
			static::$current_page = 1;
		}

		// The current page must be zero based so that the offset for page 1 is 0.
		static::$offset = (static::$current_page - 1) * static::$per_page;
	}

	/**
	 * Creates the pagination links
	 *
	 * @return string
	 */
	public static function create_links()
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		$pagination  = static::$template['wrapper_start'];
		$pagination .= static::prev_link('Previous');
		$pagination .= static::page_links();
		$pagination .= static::next_link('Next');
		$pagination .= static::$template['wrapper_end'];

		return $pagination;
	}

	/**
	 * Pagination Page Number links
	 *
	 * @return string
	 */
	public static function page_links()
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		$pagination = '';

		// Let's get the starting page number, this is determined using num_links
		$start = ((static::$current_page - static::$num_links) > 0) ? static::$current_page - (static::$num_links - 1) : 1;

		// Let's get the ending page number
		$end   = ((static::$current_page + static::$num_links) < static::$total_pages) ? static::$current_page + static::$num_links : static::$total_pages;

		for($i = $start; $i <= $end; $i++)
		{
			if (static::$current_page == $i)
			{
				$pagination .= static::$template['active_start'].$i.static::$template['active_end'];
			}
			else
			{
				$url = ($i == 1) ? '' : '/'.$i;
				$pagination .= '<a href="'.rtrim(static::$pagination_url, '/').$url.'">'.$i.'</a>'.PHP_EOL;
			}
		}

		return $pagination;
	}

	/**
	 * Pagination "Next" link
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function next_link($value)
	{
		if (static::$total_pages == 1 || static::$current_page == static::$total_pages)
		{
			return '';
		}

		$next_page = static::$current_page + 1;

		return '<a href="'.rtrim(static::$pagination_url, '/').'/'.$next_page.'">'.$value.'</a>'.PHP_EOL;
	}

	/**
	 * Pagination "Previous" link
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function prev_link($value)
	{
		if (static::$total_pages == 1 || static::$current_page == 1)
		{
			return '';
		}

		$previous_page = static::$current_page - 1;
		$previous_page = ($previous_page == 1) ? '' : '/'.$previous_page;

		return '<a href="'.rtrim(static::$pagination_url, '/').$previous_page.'">'.$value.'</a>'.PHP_EOL;
	}
}