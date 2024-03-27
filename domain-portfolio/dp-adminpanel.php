<?php
// Filename: dp-adminpanel.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
function dp_panel() {
	global $wpdb;
	if (isset($_POST['add-to-portfolio']) && isset($_POST['domain']) && $_POST['domain'] != '' && $_POST['status'] != '' && isset($_POST['status']) && isset($_POST['expiry']) && isset($_POST['registrar'])) {
		if (!isset($_POST['price']) || $_POST['price'] == '') {
			$price = 'N/A';
		} else {
			$price = $_POST['price'];
		}
		$ext = explode('.', $wpdb->escape($_POST['domain']),2);
	$insert = "INSERT INTO `".$wpdb->prefix."dp_domains` (id, name, language, expiry, price, status, registrar, ext) VALUES (NULL, '".$wpdb->escape($_POST['domain'])."', '".$wpdb->escape($_POST['language'])."', '".$wpdb->escape($_POST['expiry']['year'])."-".$wpdb->escape($_POST['expiry']['month'])."-".$wpdb->escape($_POST['expiry']['day'])."', '".$wpdb->escape($price)."', '".$wpdb->escape($_POST['status'])."', '".$wpdb->escape($_POST['registrar'])."', '".$ext[1]."');";
	$results = $wpdb->query($insert);
	?><div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><?php _e('Domain Added');?>.</p></div> <?php
	}
	if (isset($_GET['deleted'])) {
		?><div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><?php _e('Domain Deleted Successfully');?>.</p></div><?php
	}
	if (isset($_POST['delete-selected']) && $_POST['domainopt'] != '') {
		foreach ($_POST['domainopt'] as $id) {
				$wpdb->query("DELETE FROM `".$wpdb->prefix."dp_domains` WHERE id = '".$wpdb->escape($id)."'");
		}
		?><div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><?php _e('Domains Deleted Successfully');?>.</p></div><?php
	}
?>
<div class="wrap">
<h2><?php _e('Domain Portfolio'); ?></h2>
<p class="submit"><input type="button" value="Import &raquo;" onClick="location.href='edit.php?page=dp.php&import'" /><input type="button" value="Export &raquo;" onClick="location.href='edit.php?page=dp.php&export'" /><input type="button" value="Bulk Add &raquo;" onClick="location.href='edit.php?page=dp.php&bulkadd'" /><input type="button" value="Options &raquo;" onClick="location.href='options-general.php?page=dp.php'" /></p>
<form method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td></td>
    <td><?php _e('Domain Name'); ?>...</td>
    <td><?php _e('Category'); ?>...</td>
    <td><?php _e('Price'); ?>...</td>
    <td><?php _e('Creation'); ?>...</td>
    <td><?php _e('Registrar'); ?>...</td>
    <td><?php _e('Status'); ?>...</td>
    <td></td>
  </tr>
  <tr>
    <td><strong><?php _e('Add Domain'); ?>:</strong></td>
    <td><input type="text" name="domain" /></td>
    <td><input type="text" name="language" value="English" size="10" /></td>
    <td><input type="text" name="price" size="5" /></td>
    <td><select name="expiry[month]">
    <option value="1" <?php echo (date('n') == 1) ? 'selected="selected"' : ''; ?>><?php _e('January'); ?></option>
    <option value="2" <?php echo (date('n') == 2) ? 'selected="selected"' : ''; ?>><?php _e('February'); ?></option>
    <option value="3" <?php echo (date('n') == 3) ? 'selected="selected"' : ''; ?>><?php _e('March'); ?></option>
    <option value="4" <?php echo (date('n') == 4) ? 'selected="selected"' : ''; ?>><?php _e('April'); ?></option>
    <option value="5" <?php echo (date('n') == 5) ? 'selected="selected"' : ''; ?>><?php _e('May'); ?></option>
    <option value="6" <?php echo (date('n') == 6) ? 'selected="selected"' : ''; ?>><?php _e('June'); ?></option>
    <option value="7" <?php echo (date('n') == 7) ? 'selected="selected"' : ''; ?>><?php _e('July'); ?></option>
    <option value="8" <?php echo (date('n') == 8) ? 'selected="selected"' : ''; ?>><?php _e('August'); ?></option>
    <option value="9" <?php echo (date('n') == 9) ? 'selected="selected"' : ''; ?>><?php _e('September'); ?></option>
    <option value="10" <?php echo (date('n') == 10) ? 'selected="selected"' : ''; ?>><?php _e('October'); ?></option>
    <option value="11" <?php echo (date('n') == 11) ? 'selected="selected"' : ''; ?>><?php _e('November'); ?></option>
    <option value="12" <?php echo (date('n') == 12) ? 'selected="selected"' : ''; ?>><?php _e('December'); ?></option>
    </select>
    <select name="expiry[day]">
	<?php
	for ($i=1;$i<=31;$i++) {
		if ($i == date('j')) {
			echo '<option selected="selected">'.$i.'</option>
			';
		} else {
			echo '<option>'.$i.'</option>
			';
		}
	}
	?>
    </select>
    <select name="expiry[year]">
    <?php
	$plusten = date('Y')+10;
	for ($i=date('Y');$i<=$plusten;$i++) {
		if ($i == date('Y')) {
			echo '<option selected="selected">'.$i.'</option>
			';
		} else {
			echo '<option>'.$i.'</option>
			';
		}
	}
	?>
    </select>
    </td>
    <td><input type="text" name="registrar" /></td>
    <td><select name="status">
    <option value="0"><?php _e('For Sale'); ?></option>
    <option value="1"><?php _e('Make Offer'); ?></option>
    <option value="2"><?php _e('Not for Sale'); ?></option>
    </select></td>
    <td><input type="submit" value="<?php _e('Add'); ?> &raquo;" class="button"  name="add-to-portfolio" /></td>
  </tr>
</table>
</form>
<?php
  if (isset($_GET['p'])) {
  	if ($_GET['p'] <= 0) {
		$start = 0;
		$page = 1;
	} else {
		$start = get_option('dp_dompp') * $_GET['p'] - get_option('dp_dompp');
		$page = $_GET['p'];
	}
  } else {
  	$start = 0;
	$page = 1;
  }
  if (isset($_GET['search-term'])) {
  	$sql2 = "SELECT * FROM `".$wpdb->prefix."dp_domains` WHERE `name` LIKE '%".$wpdb->escape(urldecode($_GET['search-term']))."%'";
  } else {
  	$sql2 = "SELECT * FROM `".$wpdb->prefix."dp_domains`";
  }
  $numrows = $wpdb->query($sql2);
  
  $pages = ceil($numrows/get_option('dp_dompp'));
  ?><br />
<form method="get" action="edit.php">
<input type="hidden" name="page" value="dp.php" />
<strong><?php _e('Search for a domain'); ?>: </strong><input type="text" name="search-term"<?php echo (isset($_POST['search-term'])) ? ' value="'.$_POST['search-term'].'"' : ''; ?> />
<input type="submit" class="button" value="<?php _e('Search'); ?> &raquo;" />
</form>

<script type="text/javascript"><!--

var formblock;
var forminputs;

function prepare() {
formblock= document.getElementById('mass');
forminputs = formblock.getElementsByTagName('input');
}

function select_all(name, value) {
for (i = 0; i < forminputs.length; i++) {
// regex here to check name attribute
var regex = new RegExp(name, "i");
if (regex.test(forminputs[i].getAttribute('name'))) {
if (value == '1') {
forminputs[i].checked = true;
} else {
forminputs[i].checked = false;
}
}
}
}

if (window.addEventListener) {
window.addEventListener("load", prepare, false);
} else if (window.attachEvent) {
window.attachEvent("onload", prepare)
} else if (document.getElementById) {
window.onload = prepare;
}

function confirmSubmit(message)
{
var agree=confirm(message);
if (agree)
	return true ;
else
	return false ;
}
//--></script>
<form method="post" id="mass">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><input type="button" onClick="select_all('domainopt', '1');" value="<?php _e('Select All'); ?>" /><input type="button" onClick="select_all('domainopt', '0');" value="<?php _e('Deselect All'); ?>" />&nbsp;&nbsp;&nbsp;<input type="submit" name="delete-selected" value="Delete Selected" onclick="return confirmSubmit('Are you sure you want to delete these domains?');" /></td>
    <td valign="bottom">
<div style="text-align:right"><strong><?php _e('Pages'); ?>: </strong><?php
	if ($page > 1) {
		echo "<a href=\"edit.php?page=dp.php&p=".($page - 1)."".((isset($_GET['search-term'])) ? '&search-term='.$_GET['search-term'] : '')."\" title=\"Previous Page\">&lt;Prev</a> ";
	}
  for ($i=1;$i<=$pages;$i++) {
	if ($i == $page) {
		echo "[$i]";
	} else {
		echo "<a href=\"edit.php?page=dp.php&p=$i".((isset($_GET['search-term'])) ? '&search-term='.$_GET['search-term'] : '')."\">[$i]</a>";
	}
  }
  if ($page < $pages) {
		echo " <a href=\"edit.php?page=dp.php&p=".($page + 1)."".((isset($_GET['search-term'])) ? '&search-term='.$_GET['search-term'] : '')."\" title=\"Next Page\">Next &gt;</a> ";
	}
?>
</div>
</td>
  </tr>
</table>
<?php if(isset($_GET['search-term'])) { ?>
<center><strong><?php _e('Results for the term'); ?> '<?php echo  $_GET['search-term']; ?>':</strong></center>
<?php } ?>

<table class="widefat">
<thead>
  <tr>
  	<th scope="col" style="text-align:center"></th>
    <th scope="col" style="text-align: center;">#</th>
    <th scope="col"><?php _e('Domain Name'); ?></th>
    <th scope="col"><?php _e('Category'); ?></th>
    <th scope="col"><?php _e('Creation'); ?></th>
	<th scope="col"><?php _e('Asking Price'); ?></th>
    <th scope="col"><?php _e('Registrar'); ?></th>
	<th scope="col"><?php _e('Status'); ?></th>
    <th scope="col" colspan="2"><?php _e('Action');?></th>
  </tr>
  </thead>
  <tbody id="the-list"><?php
  if (isset($_GET['search-term'])) {
  	  $insert = "SELECT * FROM `".$wpdb->prefix."dp_domains` WHERE `name` LIKE '%".$wpdb->escape(urldecode($_GET['search-term']))."%' LIMIT $start, ".get_option('dp_dompp');
  } else {
  	  $insert = "SELECT * FROM `".$wpdb->prefix."dp_domains` LIMIT $start, ".get_option('dp_dompp');
  }
  $results = $wpdb->get_results($insert);

  $i = 0;
  foreach ($results as $row) {
  $i++;
  ?>
  <tr <? echo ($i%2 == 1) ? 'class="alternate"' : ''; ?>>
  	<th scope="row" style="text-align:center;"><input type="checkbox" name="domainopt[]" value="<?php echo $row->id; ?>" /></th>
    <th scope="row" style="text-align: center;"><?php echo $i; ?></th>
    <td><?php echo $row->name; ?></td>
    <td><?php echo $row->language; ?></td>
    <td><?php echo date('F jS, Y', strtotime($row->expiry)); ?></td>
    <td><?php echo ($row->price == 'N/A') ? $row->price : sprintf("%01.2f", $row->price); ?></td>
    <td><?php echo $row->registrar; ?></td>
    <td><?php echo status($row->status); ?></td>
    <td><a href="edit.php?page=dp.php&amp;dn=<?php echo $row->id; ?>" class="edit"><?php _e('Edit');?></a></td>
    <td><a href="edit.php?page=dp.php&amp;deletedn=<?php echo $row->id; ?>" class="delete" onClick="return confirm('<?php _e('Are you sure you want to delete');?> \'<?php echo $row->name; ?>\'');"><?php _e('Delete');?></a></td>
  </tr>
  <?php
  }
  if ($i == 0) {
  ?><tr class="alternate">
    <td colspan="8" align="center"><?php _e('No Results Found'); ?></td>
  </tr><?php
  }
  ?>
  </tbody>
</table>
</form>
</div>
<?php
}
?>