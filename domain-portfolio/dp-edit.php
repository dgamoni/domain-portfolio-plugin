<?php
// Filename: dp-edit.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
function dp_editdn () {
	global $wpdb;
	if (isset($_POST['save-domain']) && $_POST['status'] != '' && isset($_POST['status']) && isset($_POST['expiry']) && isset($_POST['registrar'])) {
		if (!isset($_POST['price']) || $_POST['price'] == '') {
			$price = 'N/A';
		} else {
			$price = $_POST['price'];
		}
		$insert = "UPDATE `".$wpdb->prefix."dp_domains` SET `expiry` = '".$wpdb->escape($_POST['expiry']['year'])."-".$wpdb->escape($_POST['expiry']['month'])."-".$wpdb->escape($_POST['expiry']['day'])."', `price` = '".$wpdb->escape($price)."', `status` = '".$wpdb->escape($_POST['status'])."', `registrar` = '".$wpdb->escape($_POST['registrar'])."', `language` = '".$wpdb->escape($_POST['language'])."' WHERE id = ".$wpdb->escape($_GET['dn'])." LIMIT 1;";
  		$results = $wpdb->query($insert);
		?><div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><?php _e('Domain Saved Successfully');?>.</p></div><?php
	}
	$insert = "SELECT * FROM `".$wpdb->prefix."dp_domains` WHERE id = ".$_GET['dn']." LIMIT 1;";
  	$results = $wpdb->get_results($insert);
	foreach ($results as $row) {
	?>
    <div class="wrap">
<h2><?php _e('Edit Domain Name'); ?></h2>
<form method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td colspan="2"><input type="button" class="button" value="&laquo; <?php _e('Go Back'); ?>" onclick="location.href = 'edit.php?page=dp.php';" /></td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="120"><strong><?php _e('Domain');?>:</strong></td>
    <td><?php echo $row->name; ?></td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="120"><strong><?php _e('Language');?>:</strong></td>
    <td><input type="text" value="<?php echo $row->language; ?>" name="language" /></td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php _e('Expiry');?>:</strong></td>
    <td><?php $dates = explode('-',$row->expiry); ?><select name="expiry[month]">
    <option value="1" <?php echo ($dates[1] == 1) ? 'selected="selected"' : ''; ?>><?php _e('January'); ?></option>
    <option value="2" <?php echo ($dates[1] == 2) ? 'selected="selected"' : ''; ?>><?php _e('February'); ?></option>
    <option value="3" <?php echo ($dates[1] == 3) ? 'selected="selected"' : ''; ?>><?php _e('March'); ?></option>
    <option value="4" <?php echo ($dates[1] == 4) ? 'selected="selected"' : ''; ?>><?php _e('April'); ?></option>
    <option value="5" <?php echo ($dates[1] == 5) ? 'selected="selected"' : ''; ?>><?php _e('May'); ?></option>
    <option value="6" <?php echo ($dates[1] == 6) ? 'selected="selected"' : ''; ?>><?php _e('June'); ?></option>
    <option value="7" <?php echo ($dates[1] == 7) ? 'selected="selected"' : ''; ?>><?php _e('July'); ?></option>
    <option value="8" <?php echo ($dates[1] == 8) ? 'selected="selected"' : ''; ?>><?php _e('August'); ?></option>
    <option value="9" <?php echo ($dates[1] == 9) ? 'selected="selected"' : ''; ?>><?php _e('September'); ?></option>
    <option value="10" <?php echo ($dates[1] == 10) ? 'selected="selected"' : ''; ?>><?php _e('October'); ?></option>
    <option value="11" <?php echo ($dates[1] == 11) ? 'selected="selected"' : ''; ?>><?php _e('November'); ?></option>
    <option value="12" <?php echo ($dates[1] == 12) ? 'selected="selected"' : ''; ?>><?php _e('December'); ?></option>
    </select>
    <select name="expiry[day]">
	<?php
	for ($i=1;$i<=31;$i++) {
		if ($i == $dates[2]) {
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
		if ($i == $dates[0]) {
			echo '<option selected="selected">'.$i.'</option>
			';
		} else {
			echo '<option>'.$i.'</option>
			';
		}
	}
	?>
    </select></td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php _e('Asking Price');?>:</strong></td>
    <td><?php if ($row->price == 'N/A') {
		$value = '';
	} else {
		$value = $row->price;
	} ?><input type="text" value="<?php echo $value; ?>" name="price" /></td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php _e('Registrar');?>:</strong></td>
    <td><input type="text" value="<?php echo $row->registrar; ?>" name="registrar" /></td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php _e('Status');?>:</strong></td>
    <td><select name="status">
    <option value="0" <?php echo ($row->status == 0) ? 'selected="selected"' : '';?>><?php _e('For Sale'); ?></option>
    <option value="1" <?php echo ($row->status == 1) ? 'selected="selected"' : '';?>><?php _e('Make Offer'); ?></option>
    <option value="2" <?php echo ($row->status == 2) ? 'selected="selected"' : '';?>><?php _e('Not for Sale'); ?></option>
    </select></td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="2" align="right"><input type="submit" name="save-domain" class="button" value="<?php _e('Save Domain');?> &raquo;" /></td>
  </tr>
</table>
</form>
	</div>
	<?php
	}
}
?>