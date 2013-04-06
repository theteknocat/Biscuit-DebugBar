<?php
/**
 * Provide an expandable/collapsable bar fixed to the bottom of the page for displaying debug information. This extension is automatically loaded when debug mode is enabled
 * unless overridden by the site configuration.
 *
 * @package Modules
 * @author Peter Epp
 * @copyright Copyright (c) 2009 Peter Epp (http://teknocat.org)
 * @license GNU Lesser General Public License (http://www.gnu.org/licenses/lgpl.html)
 * @version 2.0
 */
class DebugBar extends AbstractExtension {
	/**
	 * List of filenames of all the log files used by this extension
	 *
	 * @var string
	 */
	private $_log_files = array('console_log','error_log','query_log','event_log','var_dump_log');

	/**
	 * Register CSS and JS files
	 *
	 * @return void
	 * @author Peter Epp
	 */
	public function run() {
		$this->register_js("footer","debug_bar.js");
		$this->register_css(array('filename' => 'debug_bar.css', 'media' => 'all'));
	}
	/**
	 * Return the HTML code for the DebugBar
	 *
	 * @return string
	 * @author Peter Epp
	 */
	protected function render_bar() {
		$view_vars = array(
			'DebugBar' => $this
		);
		foreach ($this->_log_files as $log_file_name) {
			$log_type = substr($log_file_name,0,-4);
			$view_vars[$log_file_name] = Console::parse_log_file($log_type);
		}
		return Crumbs::capture_include("debug_bar/views/index.php",$view_vars);
	}
	/**
	 * Render the header for a debug output section, including navigation to flip through all the requests
	 *
	 * @param string $debug_type Type of debug output, eg. "console"
	 * @param string $debug_data Array of debug data for output
	 * @return void
	 * @author Peter Epp
	 */
	public function render_debug_header($debug_type,$header_title,$counter,$total_pages) {
		$view_vars = array(
			'debug_type'   => $debug_type,
			'header_title' => $header_title,
			'counter'      => $counter,
			'total_pages'  => $total_pages
		);
		return Crumbs::capture_include("debug_bar/views/debug_header.php",$view_vars);
	}
	/**
	 * Helper method to compose debug header title
	 *
	 * @param array $marker_info 
	 * @param string $item_name 
	 * @param string $total_count 
	 * @return string
	 * @author Peter Epp
	 */
	public function compose_header_title($marker_info,$item_name,$total_count) {
		if ($total_count > 1) {
			$item_name = AkInflector::pluralize($item_name);
		}
		return 'Request for '.$marker_info['request_uri'].' on '.date('F jS, Y',$marker_info['timestamp']).' at '.date('H:i:s',$marker_info['timestamp']).'<br>'.$total_count.' '.$item_name;
	}
	/**
	 * Add the DebugBar to the page footer
	 *
	 * @return void
	 * @author Peter Epp
	 */
	protected function act_on_compile_footer() {
		$this->Biscuit->append_view_var("footer",$this->render_bar());
	}
	/**
	 * Check the last modified dates on the log files
	 *
	 * @return void
	 * @author Peter Epp
	 */
	protected function act_on_check_for_content_updates() {
		if ($this->Biscuit->render_with_template()) {
			$timestamps = array();
			foreach ($this->_log_files as $log_file_name) {
				$log_file_path = SITE_ROOT.'/log/'.$log_file_name;
				if (file_exists($log_file_path)) {
					$timestamps[] = filemtime($log_file_path);
				}
			}
			if (!empty($timestamps)) {
				rsort($timestamps);
				$this->Biscuit->add_updated_timestamp(reset($timestamps));
			}
		}
	}
}
?>