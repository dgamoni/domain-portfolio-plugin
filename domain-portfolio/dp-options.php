<?php
// Filename: dp-options.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
function dp_optionsmenu() {
	if (isset($_POST['save-dp-options'])) {
		update_option("dp_page", $_POST['dp_page']);
		update_option("dp_currency", $_POST['dp_currency']);
		update_option("dp_dompp", $_POST['dp_dompp']);
		update_option("dp_search", $_POST['dp_search']);
		update_option("dp_visible", join(',', $_POST['dp_visible']));
		update_option("dp_contactfields", join(',', $_POST['dp_contactfields']));
		update_option("dp_contact", $_POST['dp_contact']);
		update_option("dp_links", $_POST['dp_links']);
		update_option("dp_style", $_POST['dp_style']);
		update_option("dp_intro", $_POST['dp_intro']);
		?><div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><?php _e('Options Saved Successfully');?>.</p></div><?php
	}
	$dp_visible = explode(",", get_option("dp_visible"));
	$dp_contactfields = explode(",", get_option("dp_contactfields"));
	?>
    <div class="wrap">
    <h2><?php _e('Domain Portfolio Options');?></h2>
<form method="post">
<p class="submit"><input type="submit" name="save-dp-options" value="Update Options &raquo;" /></p>
<table class="optiontable">
<tr valign="top">
<th scope="row"><?php _e('DN Portfolio Page');?>:</th>
<td><input name="dp_page" type="text" value="<?php echo get_option('dp_page'); ?>" size="40" /><br />
<?php _e('The page ID that the portfolio is to be located');?>.</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Default Currency'); ?>:</th>
<td><select name="dp_currency"><?php $currencies = array('AUD','CAD','EUR','GBP','JPY','USD'); 
foreach ($currencies as $currency) {
	if ($currency == get_option('dp_currency')) {
		echo '<option value="'.$currency.'" selected="selected">'.currency($currency).'</option>';
	} else {
		echo '<option value="'.$currency.'">'.currency($currency).'</option>';
	}
}
?>
</select><br /><?php _e('The currency in which you are selling your domains');?>.
</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Domains Per Page');?>:</th>
    <td><input type="text" name="dp_dompp" value="<?php echo get_option('dp_dompp');?>" /><br />
<?php _e('The number of domains you want displayed per page in your portfolio.'); ?></td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Domain Search');?>:</th>
    <td>Yes <input type="radio" name="dp_search" value="1" <?php echo (get_option('dp_search') == 1) ? ' checked="checked"' : ''; ?>/> No <input type="radio" name="dp_search" value="0" <?php echo (get_option('dp_search') == 0) ? ' checked="checked"' : ''; ?>/><br />
<?php _e('Allow visitors to search your domain portfolio.'); ?></td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Visible Columns'); ?>:</th>
    <td><?php _e('The information in your portfolio visible to visitors:'); ?><br />
    <select multiple="multiple" name="dp_visible[]">
    	<option value="name"<?php echo (in_array("name", $dp_visible)) ? ' selected="selected"' : ''; ?>>Domain Name</option>
        <option value="language"<?php echo (in_array("language", $dp_visible)) ? ' selected="selected"' : ''; ?>>Language</option>
        <option value="punycode"<?php echo (in_array("punycode", $dp_visible)) ? ' selected="selected"' : ''; ?>>Punycode</option>
        <option value="expiry"<?php echo (in_array("expiry", $dp_visible)) ? ' selected="selected"' : ''; ?>>Expiry Date</option>
        <option value="registrar"<?php echo (in_array("registrar", $dp_visible)) ? ' selected="selected"' : ''; ?>>Registrar</option>
        <option value="price"<?php echo (in_array("price", $dp_visible)) ? ' selected="selected"' : ''; ?>>Price</option>
        <option value="status"<?php echo (in_array("status", $dp_visible)) ? ' selected="selected"' : ''; ?>>Status</option>
    </select>
</td>
<tr valign="top">
	<th scope="row"><?php _e('Visible Fields'); ?>:</th>
    <td><?php _e('The information in your portfolio &quot;Contact Page&quot; visible to visitors:'); ?><br />
    <select multiple="multiple" name="dp_contactfields[]">
    	<option value="ext"<?php echo (in_array("ext", $dp_contactfields)) ? ' selected="selected"' : ''; ?>>Extension</option>
    	<option value="keywords"<?php echo (in_array("keywords", $dp_contactfields)) ? ' selected="selected"' : ''; ?>>Keywords</option>
    	<option value="google"<?php echo (in_array("google", $dp_contactfields)) ? ' selected="selected"' : ''; ?>>Google</option>
    	<option value="msn"<?php echo (in_array("msn", $dp_contactfields)) ? ' selected="selected"' : ''; ?>>MSN Search</option>
    	<option value="yahoo"<?php echo (in_array("yahoo", $dp_contactfields)) ? ' selected="selected"' : ''; ?>>Yahoo</option>
    </select>
</td>
</tr>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Contact'); ?>:</th>
    <td>Yes <input type="radio" name="dp_contact" value="1" <?php echo (get_option('dp_contact') == 1) ? ' checked="checked"' : ''; ?>/> No <input type="radio" name="dp_contact" value="0" <?php echo (get_option('dp_contact') == 0) ? ' checked="checked"' : ''; ?>/><br />
Allow visitors to contact you to submit offers. Selecting 'No' will disable the Contact column and form.</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Link'); ?>:</th>
    <td>Yes <input type="radio" name="dp_links" value="1" <?php echo (get_option('dp_links') == 1) ? ' checked="checked"' : ''; ?>/> No <input type="radio" name="dp_links" value="0" <?php echo (get_option('dp_links') == 0) ? ' checked="checked"' : ''; ?>/><br />
Make your domains into links for the domains.</td>
</tr>
</table>
<h2>Styling Your Portfolio</h2>
<p class="submit"><input type="submit" name="save-dp-options" value="Update Options &raquo;" /></p>
<table class="optiontable">
<tr valign="top">
<th scope="row"><?php _e('CSS Style');?>:</th>
<td><?php _e('The style of your domain portfolio');?>:<br /><textarea name="dp_style" cols="55" rows="7"><?php echo get_option('dp_style'); ?></textarea><br />
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Intro Text'); ?>:</th>
<td><?php _e('The text that will appear at the top of your portfolio'); ?>:<br /><br />
<span style="font-size:11px;"><strong>Replacement Variables:<br /></strong>
<i>{forsale}</i> = the number of domains you have for sale (with the status "Make Offer" or "For Sale")<br />
<i>{total}</i> = the total number of domains in your portfolio<br /></span>
<textarea name="dp_intro" cols="55" rows="7"><?php echo get_option('dp_intro'); ?></textarea></td>
</tr>
</table>
</form>
    </div>
	<?php
}
?>