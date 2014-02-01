<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CodeIgniter Pagination</title>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/styles.css" />
</head>
<body>
<p><strong>Demos:</strong></p>
<div><a href="<?=base_url()?>pagination_test/">Query String</a> | <a href="<?=base_url()?>pagination_test/page_directory/">Directory Structure</a> | <a href="<?=base_url()?>pagination_test/page_ajax/">Ajax</a></div>

<div class="pager">
    <ul>
        <li class="results">Pages:</li>
        <?=$this->page->create_links();?>
    </ul>
</div>
<div class="clr"></div>
<div id="browse_table">
    <p>Displaying <?=$this->page->page_stats();?></p>
    
    <table cellspacing="0">
    <thead>
        <tr class="headers">
            <th class="first" scope="col"><div>Last Name<ul class="sort"><li class="up"><?=$this->page->create_sort_link('last_name', 'asc'); ?></li><li class="down"><?=$this->page->create_sort_link('last_name', 'desc'); ?></li></ul></div></th>
            <th scope="col"><div>First Name<ul class="sort"><li class="up"><?=$this->page->create_sort_link('first_name', 'asc'); ?></li><li class="down"><?=$this->page->create_sort_link('first_name', 'desc'); ?></li></ul></div></th>
            <th scope="col"><div>E-Mail<ul class="sort"><li class="up"><?=$this->page->create_sort_link('email', 'asc'); ?></li><li class="down"><?=$this->page->create_sort_link('email', 'desc'); ?></li></ul></div></th>
        </tr>
    </thead>
    <? 
    if($query->num_rows() > 0)
    {
        $x=0;
        foreach($query->result() as $row)
        {
    ?>
    <tr>
        <td><?=$row->last_name?></td>
        <td><?=$row->first_name?></td>
        <td><?=$row->email?></td>
    </tr>
    <?
            $x++;
        }
    }
    else
    {
    ?>
    <tr>
        <td colspan="3">Sorry, there are no records.</td>
    </tr>
    <?
    }
    ?>
    </table>
</div>
</body>
</html>