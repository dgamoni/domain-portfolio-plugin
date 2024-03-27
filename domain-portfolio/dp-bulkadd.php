<?php
// Filename: dp-bulkadd.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
function dp_bulkadd() {
	global $wpdb;
	if (isset($_POST['bulk-add']) && isset($_POST['domains']) && $_POST['domains'] != '' && isset($_POST['registrar']) && $_POST['registrar'] != '') {
		$domains = explode("\n", $_POST['domains']);
		if ($_POST['price'] != '') {
			$price = $_POST['price'];
		} else {
			$price = 'N/A';
		}
		foreach($domains as $domain) {
			$part = explode('|', $domain);
			$newdate = date('Y-n-j', strtotime($part[1]));
			$ext = explode('.', $wpdb->escape($part[0]),2);
			$wpdb->query("INSERT INTO `".$wpdb->prefix."dp_domains` (id, name, expiry, price, status, registrar, ext, language) VALUES (NULL, '".$wpdb->escape($part[0])."', '".$part[1]."', '".$wpdb->escape($price)."', '".$wpdb->escape($_POST['status'])."', '".$wpdb->escape($_POST['registrar'])."', '".$ext[1]."', '".$wpdb->escape($part[2])."');");
		}?><div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><?php _e('Bulk Add Successful');?>.</p></div><?php
	}
	?>
    <div class="wrap">
    <h2>Bulk Add Domains</h2>
    <input type="button" value="&laquo; Go Back" onclick="location.href='edit.php?page=dp.php'" class="button" /><br />
<br />
<form method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr valign="top">
        <th scope="row"><?php _e('Domains');?>:</th>
        <td><?php _e('Simply copy and paste the list of your domains with expiry date, each separated by a carriage return');?>.<br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td width="10"><textarea cols="30" rows="10" name="domains"></textarea></td>
    <td>Ex:<br />
    <pre>acorndomains.co.uk|yyyy-mm-dd|language
</pre></td>
  </tr>
</table>
</td>
      </tr>
      <tr>
       	<td colspan="2">&nbsp;</td>
       </tr>
      <tr valign="top">
      	<th scope="row"><?php _e('Default Status'); ?>:</th>
        <td><select name="status">
       		<option value="0"><?php _e('For Sale'); ?></option>
			<option value="1"><?php _e('Make Offer'); ?></option>
			<option value="2"><?php _e('Not for Sale'); ?></option>
        </select><br />
<?php _e('This will be the default status for all domain names.');?></td>
       </tr>
       <tr>
       	<td colspan="2">&nbsp;</td>
       </tr>
       <tr valign="top">
         <th scope="row"><?php _e('Default Price'); ?>:</th>
         <td><input type="text" name="price" />(<?php _e('optional, leave blank to set domains to \'N/A\''); ?>)<br />
<?php _e('All domains will be set to this price, unless left blank.'); ?></td>
       </tr>
       <tr>
       	<td colspan="2">&nbsp;</td>
       </tr>
       <tr valign="top">
         <th scope="row"><?php _e('Registrar'); ?>:</th>
         <td><input type="text" name="registrar" /><br />
<?php _e('Domain registrar where these domains were registered.'); ?></td>
       </tr>
       <tr>
       	<td colspan="2">&nbsp;</td>
       </tr>
       <tr>
        <th scope="row"></th>
        <td><input type="submit" class="button" value="Submit &raquo;" name="bulk-add" /></td>
       </tr>
    </table>
    
</form>
    </div>
	<?php
}
?>