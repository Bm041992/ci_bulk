<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Common Controller for entire application
 */
class MY_Controller extends CI_Controller
{
	/**
	 * Page Title to be set for each page which will be shown in Browser Tab using <title> tag
	 */
	public $page_title;

	/**
	 * Constructor for the class
	 */
	public function __construct()
	{
		parent::__construct();	
	
		if (!is_user_logged_in())
		{
			redirect(base_url('auth'));
		}
	}
}
