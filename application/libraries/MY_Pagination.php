<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * MY_Pagination Class
 *
 * Extends Pagination library
 *
 */
class MY_Pagination extends CI_Pagination {

    public $add_query_string = FALSE;

    function __construct() {
        parent::__construct();

//	    $this->first_link			= '← First';
//	    $this->next_link			= 'Next →';
//	    $this->prev_link			= '← Previous';
//	    $this->last_link			= 'Last →';
	    $this->first_link			= '&lsaquo;&lsaquo;';
	    $this->next_link			= 'Next';
	    $this->prev_link			= 'Previous';
	    $this->last_link			= '&rsaquo;&rsaquo;';
	    $this->full_tag_open		= '<div class="dataTables_paginate paging_bootstrap"><ul class="pagination">';
	    $this->full_tag_close		= "</ul></div>";
	    $this->first_tag_open		= '<li class="prev">';
	    $this->first_tag_close	    = '</li>';
	    $this->last_tag_open		= '<li class="next">';
	    $this->last_tag_close		= '</li>';
	    $this->first_url			= '';
	    $this->cur_tag_open		    = '<li class="active"><a href="#">';
	    $this->cur_tag_close		= '</a></li>';
	    $this->next_tag_open		= '<li class="prev">';
	    $this->next_tag_close		= '</li>';
	    $this->prev_tag_open		= '<li class="prev">';
	    $this->prev_tag_close		= '</li>';
	    $this->num_tag_open		    = '<li>';
	    $this->num_tag_close		= '</li>';

    }

    // --------------------------------------------------------------------

    /**
     * Generate the pagination links
     *
     * @access	public
     * @return	string
     */

    function create_links() {
		if ($this->total_rows == 0 OR $this->per_page == 0) {
			return '';
		}
		$num_pages = ceil($this->total_rows / $this->per_page);
		if ($num_pages <= 1)
			return '';

		// Set the base page index for starting page number
		$base_page = 1;
		$this->cur_page = $this->cur_page == 0 ? $base_page : $this->cur_page;

		if ($this->cur_page > $num_pages) {
			$this->cur_page = $num_pages;
		}
		$this->num_links = (int)$this->num_links;

		$uri_page_number = $this->cur_page;
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		$this->base_url = rtrim($this->base_url, '/') .'/';

        //set the sufix
        if ($this->add_query_string AND $this->suffix == '') {
            $_ci =& get_instance();
            $qs = $_ci->input->get();

            if ($qs) {
                $add_qs = '/?';
                $num_itens_qs = count($qs);
                foreach ($qs as $k => $v) {
                    $num_itens_qs--;
                    if ($num_itens_qs == 0) {
                        $add_qs .= $k . '=' . $v;
                    } else {
                        $add_qs .= $k . '=' . $v . '&amp;';
                    }
                }
                $this->suffix = $add_qs;
            }
        }

		$output = '';
		// Render the "First" link
		if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1)) {
			$first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
			$output .= $this->first_tag_open.'<a href="'.$first_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1) {
			$i = $uri_page_number - 1;
			if ($i == 0 && $this->first_url != '') {
				$output .= $this->prev_tag_open.'<a href="'.$this->first_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			} else {
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
				$output .= $this->prev_tag_open.'<a href="'.$this->base_url.$i.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}

		}
		for ($loop = $start -1; $loop <= $end; $loop++) {
			$i = $loop;
			if ($i >= $base_page) {
				if ($this->cur_page == $loop) {
					$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
				} else {
					$n = ($i == $base_page) ? '' : $i;
					if ($n == '' && $this->first_url != '') {
						$output .= $this->num_tag_open.'<a href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
					} else {
						$n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;
						$output .= $this->num_tag_open.'<a href="'.$this->base_url.$n.'">'.$loop.'</a>'.$this->num_tag_close;
					}
				}
			}
		}

		// Render the "next" link
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages) {
			$i = $this->cur_page + 1;
			$output .= $this->next_tag_open.'<a href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages) {
			$i = $num_pages;
			$output .= $this->last_tag_open.'<a href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->last_link.'</a>'.$this->last_tag_close;
		}
		$output = preg_replace("#([^:])//+#", "\\1/", $output);
		$output = $this->full_tag_open . $output.$this->full_tag_close;
		return $output;
	}

}

// END Pagination Class

/* End of file Pagination.php */
/* Location: ./system/libraries/Pagination.php */