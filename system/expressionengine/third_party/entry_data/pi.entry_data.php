<?php
if(!defined('BASEPATH')) :
	exit('No direct script access allowed');
endif;

$plugin_info = array(
	'pi_name' => 'Entry Data',
	'pi_version' => '1.6',
	'pi_author' => 'Sean Delaney',
	'pi_author_url' => 'http://www.seandelaney.ie',
	'pi_description' => 'Returns the URL Title or Entry Title for any specified channel Entry ID. Alternatively it can also return the Entry ID for any specified channel URL Title or Entry Title, and retrieval of custom field value based on filed short name. Channel names and Site IDs for MSM are also supported.',
	'pi_usage' => entry_data::usage()
);

/**
 * Entry Data Class
 *
 * @package			ExpressionEngine
 * @category		Plugin
 * @author			Sean Delaney
 * @copyright		Copyright (c) 2011
 * @link			http://www.seandelaney.ie
 */
 
class Entry_Data {
	private $EE;
	private $entry_id;
	private $errors;
	private $query;
	private $results;
	private $url_title;
	private $field_name;
	private $field_id;
	private $site_id;
	private $channel_id;
	public $return_data;

	// Constructor
	function entry_data() {
		$this->EE =& get_instance();
		
		$channel = $this->EE->TMPL->fetch_param('channel');
		
		if(!empty($channel)) :
			$this->results = $this->EE->db->query('SELECT channel_id FROM '.$this->EE->db->dbprefix('channels').' WHERE channel_name = "'.$channel.'" LIMIT 1');
		
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					$this->return_data = '';
				else :
					$this->return_data = 'No matching channel found';
				endif;
			else :
				$this->channel_id = 'AND channel_id = '.$this->results->row('channel_id');
			endif;
		endif;
		
		$this->site_id = $this->EE->TMPL->fetch_param('site_id');
		
		if(!empty($this->site_id)) :
			$this->site_id = 'AND site_id = '.$this->site_id;
		endif;
	}
	
	/**
	 * Usage
	 *
	 * This function returns a channel entries Title based on an Entry ID or URL Title.
	 *
	 * @access	public
	 * @return	string
	 */
	public function title() {
		$this->entry_id = $this->EE->TMPL->fetch_param('entry_id');
		$this->url_title = $this->EE->TMPL->fetch_param('url_title');
		$this->errors = $this->EE->TMPL->fetch_param('errors');
		
		if(!empty($this->entry_id)) :
			$this->results = $this->EE->db->query('SELECT title FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE entry_id = '.$this->entry_id.' '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
			
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
			$this->results = $this->EE->db->query('SELECT title FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE url_title = "'.$this->url_title.'" '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
	
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
	 * This function returns a channel entries URL Title based on an Entry ID or Entry Title.
	 *
	 * @access	public
	 * @return	string
	 */
	public function url_title() {
		$this->entry_id = $this->EE->TMPL->fetch_param('entry_id');
		$this->title = $this->EE->TMPL->fetch_param('title');
		$this->errors = $this->EE->TMPL->fetch_param('errors');
		
		if(!empty($this->entry_id)) :
			$this->results = $this->EE->db->query('SELECT url_title FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE entry_id = '.$this->entry_id.' '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
	
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					$this->return_data = '';
				else :
					$this->return_data = 'No matching channel entry found';
				endif;
			else :
				$this->return_data = $this->results->row('url_title');
			endif;
		endif;
		
		if(!empty($this->title)) :
			$this->results = $this->EE->db->query('SELECT url_title FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE title = "'.$this->title.'" '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
	
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					$this->return_data = '';
				else :
					$this->return_data = 'No matching channel entry found';
				endif;
			else :
				$this->return_data = $this->results->row('url_title');
			endif;
		endif;
		
		return $this->return_data;
	}
	
	/**
	 * Usage
	 *
	 * This function returns a channel entries Entry ID based on an Entry Title or URL Title.
	 *
	 * @access	public
	 * @return	string
	 */
	public function entry_id() {
		$this->url_title = $this->EE->TMPL->fetch_param('url_title');
		$this->title = $this->EE->TMPL->fetch_param('title');
		$this->errors = $this->EE->TMPL->fetch_param('errors');
		
		if(!empty($this->url_title)) :
			$this->results = $this->EE->db->query('SELECT entry_id FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE url_title = "'.$this->url_title.'" '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
	
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					$this->return_data = '';
				else :
					$this->return_data = 'No matching channel entry found';
				endif;
			else :
				$this->return_data = $this->results->row('entry_id');
			endif;
		endif;
		
		if(!empty($this->title)) :
			$this->results = $this->EE->db->query('SELECT entry_id FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE title = "'.$this->title.'" '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
	
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					$this->return_data = '';
				else :
					$this->return_data = 'No matching channel entry found';
				endif;
			else :
				$this->return_data = $this->results->row('entry_id');
			endif;
		endif;
		
		return $this->return_data;
	}
	
	/**
	 * Usage
	 *
	 * This function returns a channel entries custom field data based on an Entry ID or URL Title.
	 *
	 * Add by Ahmad Saad Aldeen.
	 *	 
	 * @access	public
	 * @return	string
	 */
	public function custom_field() {
		$this->entry_id = $this->EE->TMPL->fetch_param('entry_id');
		$this->url_title = $this->EE->TMPL->fetch_param('url_title');
		$this->title = $this->EE->TMPL->fetch_param('title');
		$this->field_name = $this->EE->TMPL->fetch_param('field_name');		
		$this->errors = $this->EE->TMPL->fetch_param('errors');
		
		if(empty($this->field_name)) :
			if($this->errors == 'false') :
				return $this->return_data = '';
			else :
				return $this->return_data = 'Field name is a required parameter, please enter a value';
			endif;			
		else :
			$this->results = $this->EE->db->query('SELECT field_id FROM '.$this->EE->db->dbprefix('channel_fields').' WHERE field_name = "'.$this->field_name.'" '.$this->site_id);	
						
			if($this->results->num_rows() == 0) :
				if($this->errors == 'false') :
					return $this->return_data = '';
				else :
					return $this->return_data = 'No matching channel field found';
				endif;
			else :
				$this->field_id = "field_id_".$this->results->row('field_id');
			endif;				
		endif;
						
		if(empty($this->entry_id)) :	
			if(!empty($this->url_title)) :
				$this->results = $this->EE->db->query('SELECT entry_id FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE url_title = "'.$this->url_title.'" '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
		
				if($this->results->num_rows() == 0) :
					if($this->errors == 'false') :
						$this->return_data = '';
					else :
						$this->return_data = 'No matching channel entry found';
					endif;
				else :
					$this->entry_id = $this->results->row('entry_id');
				endif;		
			elseif(!empty($this->title)) :
				$this->results = $this->EE->db->query('SELECT entry_id FROM '.$this->EE->db->dbprefix('channel_titles').' WHERE title = "'.$this->title.'" '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
		
				if($this->results->num_rows() == 0) :
					if($this->errors == 'false') :
						$this->return_data = '';
					else :
						$this->return_data = 'No matching channel entry found';
					endif;
				else :
					$this->entry_id = $this->results->row('entry_id');
				endif;
			endif;
		endif;
		
		if(!empty($this->entry_id)) :
			$this->results = $this->EE->db->query('SELECT '.$this->field_id.' FROM '.$this->EE->db->dbprefix('channel_data').' WHERE entry_id = "'.$this->entry_id.'" '.$this->channel_id.' '.$this->site_id.' LIMIT 1');
			
			$this->return_data = $this->results->row($this->field_id);			
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
		{exp:entry_data:title entry_id="1" channel="pages" site_id="1"}
		
		{exp:entry_data:title url_title="about-us" channel="pages" site_id="1"}
		
		{exp:entry_data:url_title entry_id="{entry_id}" channel="pages" site_id="1"}
		
		{exp:entry_data:url_title title="About Us" channel="pages" site_id="1"}
		
		{exp:entry_data:entry_id url_title="about-us" channel="pages" site_id="1"}
		
		{exp:entry_data:entry_id title="About Us" channel="pages" site_id="1"}
		
		{exp:entry_data:custom_field url_title="about-us" field_name="about-us-summary" channel="pages" site_id="1"}
		
		<?php
		$buffer = ob_get_contents();

		ob_end_clean(); 

		return $buffer;
	}
	// END
}

/* End of file pi.entry_data.php */ 
/* Location: ./system/expressionengine/third_party/entry_data/pi.entry_data.php */