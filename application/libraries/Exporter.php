<?php

class Exporter  {
	protected $CI;

	public function __construct() {
		$this->CI = &get_instance();
	}

	public function exportCSV($headers, $items = array(), $fileName = 'data.csv') {
		$delim = ",";
		$newline = "\r\n";
		$enclosure = '"';

		$out = '';
		// First generate the headings from the table column names
		foreach ($headers as $key => $col) {
			$out .= $enclosure . str_replace($enclosure, $enclosure . $enclosure, is_array($col) ? $col['label'] : $col) . $enclosure . $delim;
		}
		$out = rtrim($out);
		$out .= $newline;

		foreach ($items as $row) {
			foreach ($row as $v) {
				$out .= $enclosure . str_replace($enclosure, $enclosure . $enclosure, $v) . $enclosure . $delim;
			}
			$out = rtrim($out);
			$out .= $newline;
		}
		$this->CI->load->helper('download');
		force_download($fileName, $out);
	}
}

?>