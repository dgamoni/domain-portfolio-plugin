<?php
/*
Plugin Name: Domain Portfolio
Plugin URI: http://www.borghunter.com/
Description: A wordpress domain portfolio!
Version: 1.1
Author: Mitchell Bundy
Author URI: http://www.borghunter.com/
*/
/*  Copyright 2008  Mitchell Bundy  (email : me@borghunter.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
	
	Links to the author's website should remain where they are and cannot be
	removed without permission from the author.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
$dp_version = '1.1';
$pageid = get_option("dp_page");

$siteurl = trailingslashit(get_option('siteurl'));
define('DPIMAGES', $siteurl.'wp-content/plugins/'.basename(dirname(__FILE__)).'/images');
define('DPPUNY', 'punycode');
define('DPFOLDER', $siteurl.'wp-content/plugins/'.basename(dirname(__FILE__)));
define('DPEXPORT', $siteurl.'wp-content/plugins/'.basename(dirname(__FILE__)).'/exporter.php');

include_once ("dp-install.php");

function dp_admin_menu() {
   if (function_exists('add_management_page')) {
   		if (isset($_GET['dn'])) {
			$function = 'dp_editdn';
		} elseif (isset($_GET['deletedn'])) {
			$function = 'dp_deletedn';
		} elseif (isset($_GET['bulkadd'])) {
			$function = 'dp_bulkadd';
		} elseif (isset($_GET['export'])) {
			$function = 'dp_export';
		} elseif (isset($_GET['import'])) {
			$function = 'dp_import';
		} else {
			$function = 'dp_panel';
		}
        add_management_page('Domain Portfolio', 'DN Portfolio', 8, basename(__FILE__), $function);
   }
   if (function_exists('add_options_page')) {
   		add_options_page('Domain Portfolio', 'DN Portfolio', 8, basename(__FILE__), 'dp_optionsmenu');
   }
}
if (isset($_GET['dn'])) {
	include_once("dp-edit.php");
}
if (isset($_GET['import'])) {
	include_once("dp-import.php");
}
if (isset($_GET['export'])) {
	include_once("dp-export.php");
}
if (isset($_GET['bulkadd'])) {
	include_once("dp-bulkadd.php");
}
include_once("dp-adminpanel.php");
include_once("dp-options.php");
function currency($currency) {
	switch ($currency) {
		case 'AUD':
		return 'Australian Dollars';
		break;
		case 'CAD':
		return 'Canadian Dollars';
		break;
		case 'EUR':
		return 'Euros';
		break;
		case 'GBP':
		return 'Great British Pounds';
		break;
		case 'JPY':
		return 'Japanese Yen';
		break;
		case 'USD':
		return 'US Dollars';
		break;
	}
}
function currency_sym($currency) {
	switch ($currency) {
		case 'AUD':
		return '$';
		break;
		case 'CAD':
		return '$';
		break;
		case 'EUR':
		return '&euro;';
		break;
		case 'GBP':
		return '&pound;';
		break;
		case 'JPY':
		return '&yen;';
		break;
		case 'USD':
		return '$';
		break;
	}
}
function dp_deletedn () {
	global $wpdb;
	$delete = "DELETE FROM `".$wpdb->prefix."dp_domains` WHERE id = '".$wpdb->escape($_GET['deletedn'])."'";
	$wpdb->query($delete);
	?><script type="text/javascript">
	location.href = 'edit.php?page=dp.php&deleted';
	</script><?php
}
function status($status) {
	switch($status) {
		case 0:
		return 'For Sale';
		break;
		case 1:
		return 'Make Offer';
		break;
		case 2:
		return 'Not for Sale';
		break;
	}
}

function dp_page($oldcontent) {
	global $wpdb, $dp_version, $pageid;
	
	$dp_visible = explode(",", get_option("dp_visible"));
	if (in_array("punycode", $dp_visible)) { 
		require_once(DPPUNY.'/idna_convert.class.php');
		$IDN = new idna_convert();
	}
	?>
	<style type="text/css">
	#dpcols-name, #dphead-name, #dphead-price, #dphead-status, #dpcols-status, #dpcols-price {
		text-align: center;
	}
	</style>

	<style type="text/css">
	<?php echo get_option("dp_style"); ?>
	</style><?php
	
	if (get_option('dp_search') == 0) {
		unset($_GET['search-term']);
	}
	if (isset($_GET['page'])) {
  	if ($_GET['page'] <= 0) {
		$start = 0;
		$page = 1;
	} else {
		$start = get_option('dp_dompp') * $_GET['page'] - get_option('dp_dompp');
		$page = $_GET['page'];
	}
  } else {
  	$start = 0;
	$page = 1;
  }

  if (isset($_GET['orderby'])) {
  	switch($_GET['orderby']) {
		case 'name':
		$orderby = 'name';
		break;
		case 'price':
		$orderby = 'price';
		break;
		case 'status':
		$orderby = 'status';
		break;
		case 'language':
		$orderby = 'language';
		break;
		default:
		$orderby = 'expiry';
		break;
	}
  } else {
  	$orderby = 'name';
  }
  
  if (isset($_GET['dir'])) {
  	switch($_GET['dir']) {
		case 'desc':
		$dir = 'desc';
		break;
		case 'asc':
		$dir = 'asc';
		break;
		default:
		$dir = 'desc';
		break;
	}
  } else {
  	$dir = 'asc';
  }
  if (isset($_GET['search-term'])) {
  	if (isset($_GET['ext']) && $_GET['ext'] != '') {
		$andext = " AND `ext` = '".$_GET['ext']."'";
	} else {
		$andext = "";
	}
	if (isset($_GET['lang']) && $_GET['lang'] != '') {
		$andlang = " AND `language` = '".$_GET['lang']."'";
	} else {
		$andlang = "";
	}
	$sql2 = "SELECT * FROM `".$wpdb->prefix."dp_domains` WHERE `name` LIKE '%".$wpdb->escape($_GET['search-term'])."%'".$andext.$andlang.";";
  } else {
  	$sql2 = "SELECT * FROM `".$wpdb->prefix."dp_domains`";
  }
  $sql4 = "SELECT * FROM `".$wpdb->prefix."dp_domains`";
  $numrows = $wpdb->query($sql2);
  $sql3 = "SELECT * FROM `".$wpdb->prefix."dp_domains` WHERE status != 2";
  $forsale = $wpdb->query($sql3);
  $total = $wpdb->query($sql4);
  $pages = ceil($numrows/get_option('dp_dompp'));
	if (postid() == $pageid) {
	$intro = get_option("dp_intro");
	$intro = str_replace("{total}", $total, $intro);
	$intro = str_replace("{forsale}", (($forsale == $total) ? 'all' : $forsale), $intro);
	$intro = str_replace("\n", "<br />", $intro);
	echo '<div id="intro">'.$intro.'</div>';
		?>
  <div style="text-align:right">

<!-- fix seach  -->

  <?php if (get_option('dp_search') != 0) { ?>
<!--   	<form method="get">
  	<strong>Search My UK Domain Name Portfolio:</strong>
  	<input type="text" name="search-term"<?php echo (isset($_GET['search-term'])) ? ' value="'.$_GET['search-term'].'"' : ''; ?> />
  	<input type="submit" value="Search" class="button" />  -->
<!--   	<a href="?page_id=<?php echo $pageid; ?>&search">[<?php _e('Advanced Search'); ?>]</a>
  	<br /> -->
  
  <?php 
  //------------------- add advansed saeach --------------------
  ?>
  <form method="get" action="?page_id=<?php echo $pageid; ?>">

    <strong><?php _e('Search My UK Domain Name Portfolio');?>:</strong>

    <input style="max-width: 200px;" type="text" name="search-term"<?php echo (isset($_GET['search-term'])) ? ' value="'.$_GET['search-term'].'"' : ''; ?> />

  	<strong><?php _e('Extension'); ?>:</strong>

    <select name="ext">
    <option value="">-<?php _e('All'); ?>-</option>
    <?php 
		$getext = "SELECT `ext` FROM `".$wpdb->prefix."dp_domains`";
		$results = $wpdb->get_results($getext);
		$exts = array();
		foreach ($results as $row) {
			if (!in_array(strtoupper($row->ext), $exts)) {
				$exts[] = strtoupper($row->ext);
			}
		}
		asort($exts);
		foreach ($exts as $ext) {
			echo '<option value="'.$ext.'">.'.$ext.'</option>';
		}
	?>
    </select>

  	<strong><?php _e('Category'); ?>:</strong>

    <select name="lang">
    <option value="">-<?php _e('All'); ?>-</option>
    <?php 
		$getext = "SELECT `language` FROM `".$wpdb->prefix."dp_domains`";
		$results = $wpdb->get_results($getext);
		$exts = array();
		foreach ($results as $row) {
			if (!in_array(strtoupper($row->language), $languages)) {
				$languages[] = strtoupper($row->language);
			}
		}
		asort($languages);
		foreach ($languages as $language) {
			echo '<option value="'.$language.'">'.$language.'</option>';
		}
	?>
    </select>

  	<input style="margin: 5px;" type="submit" value="Search" />

<!-- </form> -->
<?php
  //-----------------------------------end
  
  } ?>

<?php if (isset($_GET['search-term'])) { ?>
<center><strong><?php _e('Search Results for the term'); ?> "<?php echo $_GET['search-term'] . '"  Extension: " '.$_GET['ext'].'"  Category: " '.$_GET['lang'].'"'; ?></strong></center>
<?php } ?>
<strong><?php _e('Pages'); ?>: </strong><?php


 // -----------  new pagination	
	$max_page = 3;
	// var_dump($page);
	// var_dump($pages);
	// var_dump($max_page);

	if ($page > 1) {
		echo "<a href=\"?page_id=$pageid&page=".($page - 1)."&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\" title=\"Previous Page\">&lt;Prev</a> ";
	}

 //  for ($i=1;$i<=$pages;$i++) {
 // 	if ($i == $page) {
	// 	echo "[$i]";
	// } else {
	// 	echo "<a href=\"?page_id=$pageid&page=$i&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\">[$i]</a>";
	// }
 //  }

	if ( ( $pages>10 )  ) {

		  for ( $i=1; $i<=$max_page; $i++ ) {
		 	if ($i == $page) {
				echo "[$i]";
			} else {
				echo "<a href=\"?page_id=$pageid&page=$i&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\">[$i]</a>";
			}
		  }
		echo '...';

		$last = $pages-$max_page;

		//var_dump($last);
		if ( ( ($page)>$max_page ) && ($page<=$last)  ) {

			for ( $i=($page-1); $i<=($page+1); $i++ ) {
				if ($i == $page) {
					echo "[$i]";
				} 
			}
			echo '...';
		}//end center

		for ( $i=$last+1; $i<=$pages; $i++ ) {
			if ($i == $page) {
				echo "[$i]";
			} else {
				echo "<a href=\"?page_id=$pageid&page=$i&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\">[$i]</a>";
		   }
		}

	} //end if >10
	else {

		  for ($i=1;$i<=$pages;$i++) {
		 	if ($i == $page) {
				echo "[$i]";
			} else {
				echo "<a href=\"?page_id=$pageid&page=$i&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\">[$i]</a>";
			}
		  }

	}

  if ($page < $pages) {
		echo " <a href=\"?page_id=$pageid&page=".($page + 1)."&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\" title=\"Next Page\">Next &gt;</a> ";
	}
// end pagination --------------------


?></form>
</div>
        <table class="dptable" cellspacing="0" cellpadding="0">
  <tr class="dphead">
  	<?php if (in_array("name", $dp_visible)) { ?>
    <td id="dphead-name">&nbsp;<img src="<?php echo DPIMAGES; ?>/pre.gif" border="0" alt="&raquo;" /> <a href="?page_id=<?php echo $pageid; ?>&page=<?php echo $page; ?>&dir=<?php echo ($dir == 'asc' ? 'desc' : 'asc'); ?>&orderby=name<?php echo (isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '').((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : ''); ?>"><?php _e('Domain Name'); ?></a> <?php echo ($orderby == 'name') ? ( ($dir == 'asc') ? '<img src="'.DPIMAGES.'/asc.gif" border="0" alt="&and;" />' : '<img src="'.DPIMAGES.'/desc.gif" border="0" alt="&or;" />' ) : '' ; ?></td>
    <?php } ?>

    <?php if (in_array("language", $dp_visible)) { ?>
    <td id="dphead-language"><img src="<?php echo DPIMAGES; ?>/pre.gif" border="0" alt="&raquo;" /> <a href="?page_id=<?php echo $pageid; ?>&page=<?php echo $page; ?>&dir=<?php echo ($dir == 'asc' ? 'desc' : 'asc'); ?>&orderby=language<?php echo (isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '').((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : ''); ?>"><?php _e('Language'); ?></a> <?php echo ($orderby == 'language') ? ( ($dir == 'asc') ? '<img src="'.DPIMAGES.'/asc.gif" border="0" alt="&and;" />' : '<img src="'.DPIMAGES.'/desc.gif" border="0" alt="&or;" />' ) : '' ; ?></td>
    <?php } ?>

    <?php if (in_array("expiry", $dp_visible)) { ?>
    <td id="dphead-expiry"><img src="<?php echo DPIMAGES; ?>/pre.gif" border="0" alt="&raquo;" /> <a href="?page_id=<?php echo $pageid; ?>&page=<?php echo $page; ?>&dir=<?php echo ($dir == 'asc' ? 'desc' : 'asc'); ?>&orderby=language<?php echo (isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '').((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : ''); ?>"><?php _e('Creation'); ?></a> <?php echo ($orderby == 'expiry') ? ( ($dir == 'asc') ? '<img src="'.DPIMAGES.'/asc.gif" border="0" alt="&and;" />' : '<img src="'.DPIMAGES.'/desc.gif" border="0" alt="&or;" />' ) : '' ; ?></td>
    <?php } ?>

    <?php if (in_array("punycode", $dp_visible)) { ?>
    <td id="dphead-punycode"><img src="<?php echo DPIMAGES; ?>/pre.gif" border="0" alt="&raquo;" /> <?php _e('Punycode'); ?></td>
    <?php } ?>

    <?php if (in_array("registrar", $dp_visible)) { ?>
    <td id="dphead-registrar"><img src="<?php echo DPIMAGES; ?>/pre.gif" border="0" alt="&raquo;" /> <a href="?page_id=<?php echo $pageid; ?>&page=<?php echo $page; ?>&dir=<?php echo ($dir == 'asc' ? 'desc' : 'asc'); ?>&orderby=registrar<?php echo (isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '').((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : ''); ?>"><?php _e('Registrar'); ?></a> <?php echo ($orderby == 'registrar') ? ( ($dir == 'asc') ? '<img src="'.DPIMAGES.'/asc.gif" border="0" alt="<img src="'.DPIMAGES.'/asc.gif" border="0" alt="&and;" />" />' : '<img src="'.DPIMAGES.'/desc.gif" border="0" alt="&or;" />' ) : '' ; ?></td>
    <?php } ?>

    <?php if (in_array("price", $dp_visible)) { ?>
    <td id="dphead-price"><img src="<?php echo DPIMAGES; ?>/pre.gif" border="0" alt="&raquo;" /> <a href="?page_id=<?php echo $pageid; ?>&page=<?php echo $page; ?>&dir=<?php echo ($dir == 'asc' ? 'desc' : 'asc'); ?>&orderby=price<?php echo (isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '').((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : ''); ?>"><?php _e('Asking Price'); ?></a> <?php echo ($orderby == 'price') ? ( ($dir == 'asc') ? '<img src="'.DPIMAGES.'/asc.gif" border="0" alt="<img src="'.DPIMAGES.'/asc.gif" border="0" alt="&and;" /> ' : '<img src="'.DPIMAGES.'/desc.gif" border="0" alt="&or;" />' ) : '' ; ?></td>
    <?php } ?>
    <?php if (in_array("status", $dp_visible)) { ?>
    <td id="dphead-status"><img src="<?php echo DPIMAGES; ?>/pre.gif" border="0" alt="&raquo;" /> <a href="?page_id=<?php echo $pageid; ?>&page=<?php echo $page; ?>&dir=<?php echo ($dir == 'asc' ? 'desc' : 'asc'); ?>&orderby=status<?php echo (isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '').((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : ''); ?>"><?php _e('Status');?></a> <?php echo ($orderby == 'status') ? ( ($dir == 'asc') ? '<img src="'.DPIMAGES.'/asc.gif" border="0" alt="&and;" />' : '<img src="'.DPIMAGES.'/desc.gif" border="0" alt="&or;" />' ) : '' ; ?></td>
    <?php } ?>
    <?php if (get_option('dp_contact') != 0) { ?>
    <td id="dphead-contact"><img src="<?php echo DPIMAGES; ?>/pre.gif" border="0" alt="&raquo;" /> <?php _e('Contact');?></td>
    <?php } ?>
  </tr>
  <?php
  if (isset($_GET['search-term'])) {
	  $insert = "SELECT * FROM `".$wpdb->prefix."dp_domains` WHERE `name` LIKE '%".$wpdb->escape($_GET['search-term'])."%'".$andext.$andlang." ORDER BY `$orderby` $dir LIMIT $start, ".get_option('dp_dompp');
  } else {
	  $insert = "SELECT * FROM `".$wpdb->prefix."dp_domains` ORDER BY `$orderby` $dir LIMIT $start, ".get_option('dp_dompp');
  }
  $results = $wpdb->get_results($insert);
  $i = 0;
  foreach ($results as $row) {
  $i++;
  ?>
  <tr <? echo ($i%2 == 1) ? 'class="dprow"' : 'class="dprowalt"'; ?> id="dp-domainrow-<?php echo $row->id; ?>">
  <?php if (in_array("name", $dp_visible)) { ?>
    <td id="dpcols-name"><?php if (get_option('dp_links') != 0) { echo '<a href="http://www.'.$row->name.'">';  } ?><?php echo $row->name; ?><?php if (get_option('dp_links') != 0) { echo '</a>';  } ?> <a href="?page_id=<?php echo $pageid; ?>&contact=<?php echo $row->id; ?>"><img src="<?php echo DPIMAGES;?>/info.gif" border="0" title="Information on <?php echo $row->name; ?>" /></a></td>
    <?php } ?>
    <?php if (in_array("language", $dp_visible)) { ?>
    <td id="dpcols-language"><?php echo $row->language; ?></td>
    <?php } ?>

    <?php if (in_array("expiry", $dp_visible)) { ?>
    <td><?php echo date('F jS, Y', strtotime($row->expiry)); ?></td>
    <?php } ?>


    <?php if (in_array("punycode", $dp_visible)) { ?>
    <td id="dpcols-punycode"><?php 
	$ext = explode('.', $row->name,2);
	echo $IDN->encode($ext[0]).'.'.$ext[1]; ?></td>
    <?php } ?>

    <?php if (in_array("registrar", $dp_visible)) { ?>
    <td><?php echo $row->registrar; ?></td>
    <?php } ?>

    <?php if (in_array("price", $dp_visible)) { ?>
    <td id="dpcols-price"><?php echo (($row->price == 'N/A' || $row->price == '') ? "N/A" : currency_sym(get_option("dp_currency")).sprintf("%01.2f", $row->price)); ?></td>
    <?php } ?>
    <?php if (in_array("status", $dp_visible)) { ?>
    <td id="dpcols-status"><?php echo status($row->status); ?></td>
    <?php } ?>
    <?php if (get_option('dp_contact') != 0) { ?>
    <td id="dpcols-contact"><?php if ($row->status != 2) { ?><a href="?page_id=<?php echo $pageid; ?>&contact=<?php echo $row->id; ?>#offer"><img src="<?php echo DPIMAGES;?>/contact.gif" border="0" /></a><?php } ?></td>
    <?php } ?>
  </tr>
  <?php
  }
  ?>
</table>
<div style="text-align:right">
	<strong><?php _e('Pages');?>: </strong>
<?php
 // -----------  new pagination	
	$max_page = 3;
	// var_dump($page);
	// var_dump($pages);
	// var_dump($max_page);

	if ($page > 1) {
		echo "<a href=\"?page_id=$pageid&page=".($page - 1)."&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\" title=\"Previous Page\">&lt;Prev</a> ";
	}

 //  for ($i=1;$i<=$pages;$i++) {
 // 	if ($i == $page) {
	// 	echo "[$i]";
	// } else {
	// 	echo "<a href=\"?page_id=$pageid&page=$i&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\">[$i]</a>";
	// }
 //  }

	if ( ( $pages>10 )  ) {

		  for ( $i=1; $i<=$max_page; $i++ ) {
		 	if ($i == $page) {
				echo "[$i]";
			} else {
				echo "<a href=\"?page_id=$pageid&page=$i&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\">[$i]</a>";
			}
		  }
		echo '...';

		$last = $pages-$max_page;

		//var_dump($last);
		if ( ( ($page)>$max_page ) && ($page<=$last)  ) {

			for ( $i=($page-1); $i<=($page+1); $i++ ) {
				if ($i == $page) {
					echo "[$i]";
				} 
			}
			echo '...';
		}//end center

		for ( $i=$last+1; $i<=$pages; $i++ ) {
			if ($i == $page) {
				echo "[$i]";
			} else {
				echo "<a href=\"?page_id=$pageid&page=$i&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\">[$i]</a>";
		   }
		}

	} //end if >10
	else {
		
		  for ($i=1;$i<=$pages;$i++) {
		 	if ($i == $page) {
				echo "[$i]";
			} else {
				echo "<a href=\"?page_id=$pageid&page=$i&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\">[$i]</a>";
			}
		  }

	}

  if ($page < $pages) {
		echo " <a href=\"?page_id=$pageid&page=".($page + 1)."&dir=$dir&orderby=$orderby".(isset($_GET['search-term']) ? '&search-term='.$_GET['search-term'] : '')."".((isset($_GET['ext']) && $_GET['ext'] != '') ? '&ext='.$_GET['ext'] : '')."".((isset($_GET['lang']) && $_GET['lang'] != '') ? '&lang='.$_GET['lang'] : '')."\" title=\"Next Page\">Next &gt;</a> ";
	}
// end pagination -------------------- ?>

</div>
<div align="center">All prices in <?php echo currency(get_option('dp_currency')); ?>.</div><br />
<center><?php _e('Powered By'); ?> <a href="http://www.borghunter.com">Wordpress UK Domain Portfolio v<?php echo $dp_version; ?></a><br /> UK Mod by <a href="http://www.acorndomains.co.uk">Acorn Domains</a></center>
        <?php
	} else {
		return $oldcontent;
	}
}
function postid() {
	global $post;
	return $post->ID;
}
function dp_contact($oldcontent) {
	global $wpdb, $dp_version, $pageid;
	if (postid() == $pageid) {
	?><a class="c_main" href="?page_id=<?php echo $pageid; ?>">&laquo; <?php _e('Go Back to my Portfolio'); ?></a><?php
		if (isset($_POST['contact-for-domain'])) {
			$value['fullname'] = ' value="'.$_POST['fullname'].'"';
			$value['email'] = ' value="'.$_POST['email'].'"';
			$value['offer'] = ' value="'.$_POST['offer'].'"';
			$value['message'] = $_POST['message'];
			$md5 = md5(md5($_POST['key']));
			$string = substr($md5,1,5);
			if (!isset($_POST['fullname']) || $_POST['fullname'] == '' || $_POST['email'] == '' || !isset($_POST['email']) || !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['email']) || $string != $_POST['code'] || !isset($_POST['message']) || $_POST['message'] == '' || get_option('dp_contact') == 0 || $row->status == 2) {
				if (!isset($_POST['fullname']) || $_POST['fullname'] == '') {
					$error['fullname'] = '<font color="#FF0000">'._('What\'s your name?').'</font>';
				}
				if ($_POST['email'] == '' || !isset($_POST['email'])) {
					$error['email'] = '<font color="#FF0000">'._('Please include your email').'</font>';
				} elseif (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['email'])) {
					$error['email'] = '<font color="#FF0000">'._('Please include a valid email').'</font>';
				}
				if ($string != $_POST['code']) {
					$error['captcha'] = '<font color="#FF0000">'._('Invalid code').'</font>';
				}
				if (!isset($_POST['message']) || $_POST['message'] == '') {
					$error['message'] = '<font color="#FF0000">'._('What\'s your message?').'</font><br />';
				}
			} else {
				$message = "Offer for Domain: ".$_POST['domain']."
				Offer by: ".$_POST['fullname']."
				Offer for: ".$_POST['offer']." ".currency(get_option('dp_currency'))."
				Their email: ".$_POST['email']."
				Their Message:
				".$_POST['message']."
				
				
				--- This email was automatically generated by UK Wordpress Domain Portfolio v".$dp_version." ---";
				$title = "Offer for Domain: ".$_POST['domain'];
				$headers = "From: ".$_POST['email'];
				if (mail(get_option('admin_email'), $title, $message, $headers)) {
					echo '<span class="c_main c_good">'; _e('Your offer for this domain was sent to the own successfully');
					echo '</span>';
				} else {
					echo '<span class="c_main c_good">'; _e('<font color="#FF0000">There was an error in processing, please try again</font>');
					echo '</span>';
				}
			}
		}
	$insert = "SELECT * FROM `".$wpdb->prefix."dp_domains` WHERE id = '".$wpdb->escape($_GET['contact'])."'";
	$results = $wpdb->get_results($insert);
	$chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
	$randcode = $chars[rand(0,25)].$chars[rand(0,25)].$chars[rand(0,25)].$chars[rand(0,25)].$chars[rand(0,25)].$chars[rand(0,25)];
	include_once('grabber.class.php');
	
  foreach ($results as $row) {
  	$dp_contactfields = explode(',', get_option("dp_contactfields"));
	if (in_array("ext", $dp_contactfields)) {
		$ext = explode('.', $row->name,2);
	}
	$grabber = new Grabber;
	$grabber->keyword = $ext[0];
	$keywords = $grabber->keyword_detection();
	$grabber->keyword = urlencode('"'.$keywords.'"');
	if (in_array("google", $dp_contactfields)) {
		$google = $grabber->google();
	}
	if (in_array("msn", $dp_contactfields)) {
		$msn = $grabber->msnlive();
	}
	if (in_array("yahoo", $dp_contactfields)) {
		$yahoo = $grabber->yahoo();
	}
	?>

	<!-- contact form new style -->
<style>
.c_good {
	background-color: #D6EAD2;
	padding: 5px;
	margin-top: 10px !important;
}
.c_main {
		max-width: 500px;
		margin: auto;
		display: block;
}
#send_domain {
	max-width: 500px;
	margin: auto;
}
#send_domain input[type="text"], #send_domain input[type="password"], #send_domain input[type="email"], #send_domain input[type="search"], #send_domain input.input-text, #send_domain textarea {
	padding: .844em;
	background: rgba(0, 0, 0, 0.05);
	color: #666a76;
	border: 0;
	-webkit-border-radius: 0.201em;
	border-radius: 0.201em;
	outline: none;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 100%;
}
.c_label {
	line-height: 1.777;
	color: #666a76;
	font-size: 1em;
	text-transform: uppercase;
	margin-top: 15px;
	display: inline-block;
	margin-bottom: 0;
	padding: 0;
	font-family: "Source Sans Pro", Helvetica, sans-serif;
}

.c_title {
	font-weight: normal;
	font-family: "Source Sans Pro", Helvetica, sans-serif;
	margin: 10px 0 0 0 !important;
}
.c_small {
	color: #666a76;
	text-transform: lowercase;
	font-size: 12px
}
#send_domain input[type="submit"], #send_domain .button, #send_domain input[type="button"] {
	padding: .844em 1.125em;
	color: #f8f8f9;
	font-weight: 600;
	background: rgba(101, 113, 116, 0.84);
	-webkit-border-radius: 0.201em;
	border-radius: 0.201em;
	border: 0;
	-webkit-transition: all ease 0.238s;
	-moz-transition: all ease 0.238s;
	-o-transition: all ease 0.238s;
	transition: all ease 0.238s;
	width: 100%;
	text-transform: uppercase;
	margin-top: 40px;
	margin-bottom: 20px;
	font-size: 1em;
}
</style>

<div id="send_domain">
    <form method="post">

    <!-- <h3><?php _e('Information'); ?>:</h3> -->
    <!-- <p class="c_label"><?php _e('Domain');?>: <?php echo strtoupper($row->name); ?></p> -->
    
    
    <?php if (in_array("ext", $dp_contactfields)) { ?>
	    <p class="c_label"><?php _e('Extension');?>:</p>
	    <?php echo '.'.strtoupper($row->ext); ?>
    <?php } ?>

    <?php if (in_array("keywords", $dp_contactfields)) { ?>
	  	<p class="c_label"><?php _e('Keywords');?>:</p>
	    <?php echo $keywords; ?>
	<? } ?>

  	<?php if (in_array("google", $dp_contactfields)) { ?>
	  	<p class="c_label"><?php _e('Google');?>:</p>
	    <?php echo '<em>'.$google.'</em>'. _(' listings with the keyword(s) ').'"'.$keywords.'"'; ?>
    <?php } ?>

  	<?php if (in_array("msn", $dp_contactfields)) { ?>
	  	<p class="c_label"><?php _e('MSN Search');?>:</p>
	    <?php echo '<em>'.$msn.'</em>'. _(' listings with the keyword(s) ').'"'.$keywords.'"'; ?>
    <?php } ?>

    <?php if (in_array("yahoo", $dp_contactfields)) { ?>
	  	<p class="c_label"><?php _e('Yahoo');?>:</p>
	    <?php echo '<em>'.$yahoo.'</em>'._(' listings with the keyword(s) ').'"'.$keywords.'"'; ?>
    <?php } ?>

    <?php if (get_option('dp_contact') != 0 && $row->status != 2) { ?>
	    <a id="offer"></a> 
	    <h3 class="c_title"><?php _e('Make an Offer on domain'); ?>: <?php echo strtoupper($row->name); ?></h3>
        
        <p class="c_label"><?php _e('Name');?>:* <?php echo $error['fullname']; ?> </p>
	    <input type="text" name="fullname"<?php echo $value['fullname'];?> /> 
	    
	    <p class="c_label"><?php _e('Email');?>:* <?php echo $error['email']; ?></p>
        <input type="text" name="email"<?php echo $value['email'];?> /> 
        
        <p class="c_label"><?php _e('Offer');?>:</p>
        <?php echo currency_sym(get_option('dp_currency'));?><input type="text" name="offer"<?php echo $value['offer'];?> />
		<span class="c_small">
			(<?php _e('please make your offer in');?> <?php echo currency(get_option('dp_currency')); ?>)
		</span>
		<br />
        
        <p class="c_label"><?php _e('Message');?>:* <?php echo $error['message']; ?></p>
        <textarea name="message" cols="25" rows="4"><?php echo $value['message'];?></textarea>
        
        <p class="c_label">CAPTCHA:* <?php echo $error['captcha']; ?></p>
	    <img style="margin-bottom: 5px;" src="<?php echo DPFOLDER;?>/captcha.php?rand=<?php echo $randcode; ?>" />
	    <!-- <br />
		Enter the code if you are human:<br /> -->
		<input type="text" name="code" /><input type="hidden" name="key" value="<?php echo $randcode;?>" /> 
	
	    <input type="submit" value="Send" class="button" name="contact-for-domain" /><input type="hidden" name="domain" value="<?php echo $row->name; ?>" />
	
	  	<!-- *Required -->


    <?php } ?>
	</form>
</div> 

<?php
// if (mail(get_option('admin_email'), $title, $message, $headers)) {
// 	echo '<span class="c_main c_good">'; _e('Your offer for this domain was sent to the own successfully');
// 	echo '</span>';
// } else {
// 	echo '<span class="c_main c_good">'; _e('<font color="#FF0000">There was an error in processing, please try again</font>');
// 	echo '</span>';
// } ?>

<!-- end contact form -->

<!-- contact old -->
<!-- <form method="post"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2"><h3><?php _e('Information'); ?>:</h3></td>
  </tr>
  <tr>
    <td><strong><?php _e('Domain');?>:</strong></td>
    <td><?php echo strtoupper($row->name); ?></td>
  </tr>
  <?php if (in_array("ext", $dp_contactfields)) { ?>
  <tr>
    <td><strong><?php _e('Extension');?>:</strong></td>
    <td><?php echo '.'.strtoupper($row->ext); ?></td>
  </tr>
  <?php } ?>
  <?php if (in_array("keywords", $dp_contactfields)) { ?>
  <tr>
  	<td><strong><?php _e('Keywords');?>:</strong></td>
    <td><?php echo $keywords; ?></td>
  </tr>
  <? } ?>
  <?php if (in_array("google", $dp_contactfields)) { ?>
  <tr>
  	<td><strong><?php _e('Google');?>:</strong></td>
    <td><?php echo '<em>'.$google.'</em>'. _(' listings with the keyword(s) ').'"'.$keywords.'"'; ?></td>
  </tr>
  <?php } ?>
  <?php if (in_array("msn", $dp_contactfields)) { ?>
  <tr>
  	<td><strong><?php _e('MSN Search');?>:</strong></td>
    <td><?php echo '<em>'.$msn.'</em>'. _(' listings with the keyword(s) ').'"'.$keywords.'"'; ?></td>
  </tr>
  <?php } ?>
  <?php if (in_array("yahoo", $dp_contactfields)) { ?>
  <tr>
  	<td><strong><?php _e('Yahoo');?>:</strong></td>
    <td><?php echo '<em>'.$yahoo.'</em>'._(' listings with the keyword(s) ').'"'.$keywords.'"'; ?></td>
  </tr>
  <?php } ?>
  <?php if (get_option('dp_contact') != 0 && $row->status != 2) { ?>
  <tr>
    <td colspan="2"><a id="offer" />&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h3><?php _e('Make an Offer'); ?>:</h3></td>
  </tr>
  <tr>
    <td width="100"><strong><?php _e('Name');?>:*</strong></td>
    <td><input type="text" name="fullname"<?php echo $value['fullname'];?> /> <?php echo $error['fullname']; ?></td>
  </tr>
  <tr>
    <td><strong><?php _e('Email');?>:*</strong></td>
    <td><input type="text" name="email"<?php echo $value['email'];?> /> <?php echo $error['email']; ?></td>
  </tr>
  <tr>
    <td><strong><?php _e('Offer');?>:</strong></td>
    <td><?php echo currency_sym(get_option('dp_currency'));?><input type="text" name="offer"<?php echo $value['offer'];?> /></td>
    <br />
		<td>(<?php _e('please make your offer in');?> <?php echo currency(get_option('dp_currency')); ?>)<br />
				</td>
  </tr>
  <tr>
    <td valign="top"><strong><?php _e('Message');?>:*</strong></td>
    <td> <?php echo $error['message']; ?><textarea name="message" cols="25" rows="4"><?php echo $value['message'];?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><strong>CAPTCHA:*</strong></td>
    <td><img src="<?php echo DPFOLDER;?>/captcha.php?rand=<?php echo $randcode; ?>" /><br />
Enter the code if you are human:<br />
<input type="text" name="code" /><input type="hidden" name="key" value="<?php echo $randcode;?>" /> <?php echo $error['captcha']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Send" class="button" name="contact-for-domain" /><input type="hidden" name="domain" value="<?php echo $row->name; ?>" /></td>
  </tr>
  <tr>
  	<td colspan="2">*Required</td>
  </tr>
  <?php } ?>
</table></form> -->
<!-- end contact old -->

<br />
<center>Please note the Nominet transfer process for UK domain names <a ref="http://www.nominet.org.uk/registrants/maintain/transfer/" target="_blank">here</a>. Transfer fees are payable by the buyer and not included in the agreed sale price.<br/><br/>
<?php _e('Powered By'); ?> <a href="http://www.borghunter.com">Wordpress UK Domain Portfolio v<?php echo $dp_version; ?></a><br /> UK mod by <a href="http://www.acorndomains.co.uk">Acorn Domains</a></center>
<?php
echo $_SESSION['key'];
}
} else {
		return $oldcontent;
	}
}

function dp_search($oldcontent) {
	global $wpdb, $pageid;
	if (postid() == $pageid) {
		?>    <a href="?page_id=<?php echo $pageid; ?>">&laquo; <?php _e('Go Back to my Portfolio'); ?></a><br /><br />
<form method="get" action="?page_id=<?php echo $pageid; ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100"><strong><?php _e('Search Term');?>:</strong></td>
    <td><input type="text" name="search-term" /></td>
  </tr>
  <tr>
  	<td width="100"><strong><?php _e('Extension'); ?>:</strong></td>
    <td><select name="ext">
    <option value="">-<?php _e('All'); ?>-</option>
    <?php 
		$getext = "SELECT `ext` FROM `".$wpdb->prefix."dp_domains`";
		$results = $wpdb->get_results($getext);
		$exts = array();
		foreach ($results as $row) {
			if (!in_array(strtoupper($row->ext), $exts)) {
				$exts[] = strtoupper($row->ext);
			}
		}
		asort($exts);
		foreach ($exts as $ext) {
			echo '<option value="'.$ext.'">.'.$ext.'</option>';
		}
	?>
    </select></td>
  </tr>
  <tr>
  	<td><strong><?php _e('Category'); ?>:</strong></td>
    <td><select name="lang">
    <option value="">-<?php _e('All'); ?>-</option>
    <?php 
		$getext = "SELECT `language` FROM `".$wpdb->prefix."dp_domains`";
		$results = $wpdb->get_results($getext);
		$exts = array();
		foreach ($results as $row) {
			if (!in_array(strtoupper($row->language), $languages)) {
				$languages[] = strtoupper($row->language);
			}
		}
		asort($languages);
		foreach ($languages as $language) {
			echo '<option value="'.$language.'">'.$language.'</option>';
		}
	?>
    </select>
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  	<td><input type="submit" value="Search" /></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form><?php
	} else {
		return $oldcontent;
	}
}
add_action('admin_menu', 'dp_admin_menu'); 
if (isset($_GET['contact'])) {
	add_filter('the_content', 'dp_contact');
} elseif (isset($_GET['search'])) { 
	add_filter('the_content', 'dp_search');
} else {
	add_filter('the_content', 'dp_page');
}

register_activation_hook(__FILE__,'dp_install');
?>