<?php
if(!defined('BASEPATH')) :
	exit('No direct script access allowed');
endif;

$plugin_info = array(
	'pi_name' => 'Entry Data',
	'pi_version' => '1.1',
	'pi_author' => 'Sean Delaney',
	'pi_author_url' => 'http://www.seandelaney.co.uk',
	'pi_description' => 'Returns the URL Title or Entry Title for any specified channel Entry ID',
	'pi_usage' => entry_data::usage()
);

/**
 * Entry Data Class
 *
 * @package			ExpressionEngine
 * @category		Plugin
 * @author			Sean Delaney
 * @copyright		Copyright (c) 2011
 * @link			http://www.seandelaney.co.uk
 */
 
class Entry_Data {
	private $EE;
	private $entry_id;
	private $errors;
	private $query;
	private $results;
	private $url_title;
	
	public $return_data;

	// Constructor
	function entry_data() {
		$this->EE =& get_instance();
	}
	
	/**
	 * Usage
	 *
	 * This function returns a channel entries Title based on an Entry ID.
	 *
	 * @access	public
	 * @return	string
	 */
	public function title() {
		$this->entry_id = $this->EE->TMPL->fetch_param('entry_id');
		$this->url_title = $this->EE->TMPL->fetch_param('url_title');
		$this->errors = $this->EE->TMPL->fetch_param('errors');
		
		if(!empty($this->entry_id)) :
			$this->results = $this->EE->db->query('SELECT title FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE entry_id = '.$this->entry_id.' LIMIT 1');
	
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					$this->return_data = '';
				else :
					$this->return_data = 'No matching channel entry found';
				endif;
			else :
				$this->return_data = $this->results->row('title');
			endif;
		endif;
		
		if(!empty($this->url_title)) :
			$this->results = $this->EE->db->query('SELECT title FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE url_title = "'.$this->url_title.'" LIMIT 1');
	
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					$this->return_data = '';
				else :
					$this->return_data = 'No matching channel entry found';
				endif;
			else :
				$this->return_data = $this->results->row('title');
			endif;
		endif;
		
		return $this->return_data;
	}
	
	/**
	 * Usage
	 *
	 * This function returns a channel entries URL Title based on an Entry ID.
	 *
	 * @access	public
	 * @return	string
	 */
	public function url_title() {
		$this->entry_id = $this->EE->TMPL->fetch_param('entry_id');
		$this->errors = $this->EE->TMPL->fetch_param('errors');
		
		if(!empty($this->entry_id)) :
			$this->results = $this->EE->db->query('SELECT url_title FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE entry_id = '.$this->entry_id.' LIMIT 1');
	
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					$this->return_data = '';
				else :
					$this->return_data = 'No matching channel entry found';
				endif;
			else :
				$this->return_data = $this->results->row('url_title');
			endif;
		else :
			if($this->errors == 'false') :
				$this->return_data = '';
			else :
				$this->return_data = 'No entry_id specified';
			endif;
		endif;
		
		return $this->return_data;
	}

	/**
	 * Usage
	 *
	 * This function describes how the plugin is used.
	 *
	 * @access	public
	 * @return	string
	 */
	function usage() {
		ob_start(); 
		?>
		{exp:entry_data:title entry_id="1"}
		
		{exp:entry_data:title url_title="about-us"}
		
		{exp:entry_data:url_title entry_id="{entry_id}"}
		<?php
		$buffer = ob_get_contents();

		ob_end_clean(); 

		return $buffer;
	}
	// END
}

/* End of file pi.entry_data.php */ 
/* Location: ./system/expressionengine/third_party/entry_data/pi.entry_data.php */