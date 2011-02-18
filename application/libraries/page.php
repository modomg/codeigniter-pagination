<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter - Pagination Library
 *
 * Description:
 * This library helps you work with pagination easier
 *
 * For installation and usage: https://bitbucket.org/modomg/codeigniter-pagination/
 *
 * @copyright	Copyright (c) 2011 Modo Media Group
 * @version 	1.0
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class Page{

	private $ci;
    
    // Main Variables
	var $base_url 		= ''; // The page we are linking to
	var $cur_page 		= 1; // The current page we are on
	var $rows_per_page 	= 10; // The number of rows/items per page
	var $page_limit		= 10; // Only show this many page numbers per page
	var $max_pages	 	= 0; // Max pages for this query
	var $total_rows 	= 0; // Total rows

	// Additional Options
	var $url_type 		= 'd'; // either 'd'=directory or 'q'=query string
	var $show_prev 		= FALSE; // show a previous link if not needed
	var $show_next 		= FALSE; // show a next link if not needed
	var $cur_page_link	= TRUE; // show the current page as a link
	var $stats_title	= 'results'; // will show the title for the page stats
	var $view_all_link	= TRUE; // will show the view all link
	var $view_all_text	= 'View All'; // text for $view_all_link
	var $show_trai_sl	= TRUE; // show trailing slash - for $url_type='d'
	var $add_pars		= array(); //Additional parameters
	var $add_par_str	= ''; //Additional parameters string - filled in automatically
	var $add_par_str_s	= ''; //Additional parameters string - filled in automatically (for sorting)

	// CSS/HTML options
	var $page_tag		= 'li'; // this is the tag that will wrap around all items
	var $page_tag_begin	= ''; // this will fill automatically
	var $page_tag_end	= ''; // this will fill automatically
	var $item_class 	= ''; // CSS class for main items
	var $prev_class 	= ''; // CSS class for previous link
	var $next_class 	= ''; // CSS class for next link
	var $cur_page_class = 'active'; // CSS class for active link

	//AJAX Options
	var $is_ajax		= FALSE; //tells the script to use ajax or not
	var $update_div		= ''; //Div that will be updated with the new information
	var $functions		= 'onLoading: showAjaxLoading, onLoaded: hideAjaxLoading, '; //additional functions

	public function __construct()
	{
		$this->ci =& get_instance();
	}

	public function initialize($params = array())
	{
        if(count($params) > 0)
		{
			foreach($params as $key => $val)
			{
                if(isset($this->$key))
				{
                    $this->$key = $val;
                }
            }
        }

		//run other functions
		$this->findMax();
		$this->fixTags();
		$this->fixPars();
    }

	public function create_links()
    {
		if($this->total_rows > $this->rows_per_page)
		{
			$nav = '';

			$bottom = $this->t_rounddown($this->cur_page, $this->page_limit);
			$top = $this->t_round($this->cur_page, $this->page_limit);

			if($bottom == $top)
			{
				$bottom = $bottom - ($this->page_limit - 1);
			}
			else
			{
				$bottom = $bottom + 1;
			}

			if($this->max_pages > $this->page_limit)
			{
				if($top > $this->max_pages)
				{
					$max = $this->max_pages;
				}
				else
				{
					$max = $top;
				}

				for($page = $bottom; $page <= $max; $page++)
				{
					if($page == $this->cur_page)
					{
						$nav .= $this->create_link($page, $page, $this->cur_page_link, $this->cur_page_class);
					}
					else
					{
						$nav .= $this->create_link($page, $page);
					}
				}
			}
			else
			{
				for($page = 1; $page <= $this->max_pages; $page++)
				{
					if ($page == $this->cur_page)
					{
						$nav .= $this->create_link($page, $page, $this->cur_page_link, $this->cur_page_class);
					}
					else
					{
						$nav .= $this->create_link($page, $page);
					}
				}
			}

			if ($this->cur_page > 1)
			{
				$page = $this->cur_page - 1;
				$prev = $this->create_link('Prev', $page);
			}
			else
			{
				if($this->show_prev) $prev = $this->create_link('Prev', $page);
			}

			if ($this->cur_page < $this->max_pages)
			{
				$page = $this->cur_page + 1;
				$next = $this->create_link('Next', $page);
			}
			else
			{
				if($this->show_next) $prev = $this->create_link('Next', $page);
			}

			if (!isset($prev)) $prev='';
			if (!isset($next)) $next='';
			echo $prev . $nav . $next;
			if($this->view_all_link) echo $this->create_link($this->view_all_text, 'all');
		}
		else
		{
			echo $this->create_link('1', '1', FALSE, $this->cur_page_class);
		}
	}

	public function create_link($text, $page_no, $is_link=TRUE, $css_class='')
	{
		$css_class ? $class = ' class="'.$css_class.'"' : $class = '';

		if($this->url_type == 'd' && $this->show_trai_sl == TRUE && $page_no != 1) $href = $this->base_url.$page_no.'/';
		elseif($this->url_type == 'd' && $page_no == 1) $href = $this->base_url;
		elseif($this->url_type == 'd' && $this->show_trai_sl == FALSE) $href = $this->base_url.$page_no;
        elseif($this->url_type == 'q')
		{
			if(!isset($pars)) $pars = '';
            if($page_no == 1)
            {
                if($this->add_par_str != '') $pars = '?'.$this->add_par_str;
                $href = $this->base_url.$pars;
            }
            else
            {
                if($this->add_par_str != '') $pars = '&'.$this->add_par_str;
                $href = $this->base_url."?page=$page_no".$pars;
            }
		}

		if($this->url_type == 'd' && $this->add_par_str != '') $href = $href."?".$this->add_par_str;

		if($this->is_ajax)
		{
			$ajax = $this->create_ajax_link_page($page_no);
			$href = '#';
		}

		if($is_link)
		{
			if (!isset($ajax)) $ajax='';
			$link = $this->page_tag_begin.'<a href="'.$href.'"'.$class.''.$ajax.'>'.$text.'</a>'.$this->page_tag_end;

		}
		else
		{
			$link = $this->page_tag_begin.$text.$this->page_tag_end;
		}

		return $link;
	}

	public function create_ajax_link_page($page_no)
	{
		if($this->add_par_str > '') $pars = '&'.$this->add_par_str;
        if (!isset($pars)) $pars='';
        
		return " onclick=\"new Ajax.Updater('".$this->update_div."','".$this->base_url."',{".$this->functions."method: 'post', parameters: 'page=$page_no".$pars."'}); return false;\"";
	}

	public function create_sort_link($col, $dir, $link_text='')
	{
		if (!isset($pars)) $pars='';
        if($this->add_par_str_s > '')
		{
			$pars = '&'.$this->add_par_str_s;
		}

		if($this->is_ajax)
		{
			echo "<a href=\"#\" onclick=\"new Ajax.Updater('".$this->update_div."','".$this->base_url."',{".$this->functions."method: 'post', parameters: 'col=$col&dir=$dir".$pars."'}); return false;\">".$link_text."</a>";
		}
		else
		{
			if(!isset($pars)) $pars = '';
			echo "<a href=\"".$this->base_url."?col=$col&dir=$dir".$pars."\">".$link_text."</a>";
		}
	}

	public function t_round($num, $round_to=10)
	{
		return ($num % $round_to >= 5 ? ceil($num / $round_to)*$round_to : ceil($num / $round_to)*$round_to);
	}

	public function t_rounddown($num, $round_to=10)
	{
		return ($num % $round_to >= 5 ? floor($num / $round_to)*$round_to : floor($num / $round_to)*$round_to);
	}

	public function findMax()
	{
		// how many pages we have when using paging?
		if($this->cur_page == 'all')
		{
			$this->cur_page = 1;
			$this->rows_per_page = $this->total_rows;
			$this->max_pages = 1;
		}
		else
		{
			$this->max_pages = ceil($this->total_rows/$this->rows_per_page);
		}
	}

	public function fixTags()
	{
		if($this->page_tag)
		{
			$this->page_tag_begin = '<'.$this->page_tag.'>';
			$this->page_tag_end = '</'.$this->page_tag.'>';
		}
	}

	public function fixPars()
	{
		$total = count($this->add_pars);
		if($total > 0)
		{
			$pars = '';
			$pars2 = '';
			$x = 0;
			foreach($this->add_pars as $key => $value)
			{
				$pars .= "$key=$value";
				if($x < ($total-1)) $pars .= "&";
				if($key != 'col' && $key != 'dir')
				{
					$pars2 .= "$key=$value";
					if($x < ($total-1)) $pars2 .= "&";
				}
				$x++;
			}

			$this->add_par_str = $pars;
			$this->add_par_str_s = $pars2;
		}
	}

	public function pageStats()
	{
		if($this->total_rows == 0)
		{
			echo $this->total_rows." ".$this->stats_title;
		}
		else
		{
			$from = 0;
			$to = 0;
			$from = $this->cur_page-1;

			if(!isset($maxPage)) $maxPage = null;
			if($this->total_rows % $this->rows_per_page != 0 && $maxPage == $this->cur_page) $k = $this->total_rows % $this->rows_per_page;
			else $k = $this->rows_per_page;

			if($this->cur_page == 1)
			{
				$start = $this->cur_page;
				if($this->total_rows < $this->rows_per_page)
				{
					$end = $this->total_rows;
				}
				else
				{
					$end = $this->rows_per_page;
				}
			}
			else
			{
				$start = ($from * $this->rows_per_page) + 1;
				$end = ($from * $this->rows_per_page) + $k;
			}

			if($end > $this->total_rows) $end = $this->total_rows;

			echo "$start to $end (of ".$this->total_rows." ".$this->stats_title.")";
		}
	}
}

?>