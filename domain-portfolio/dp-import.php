<?php
// Filename: dp-import.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
$import_domains = array();
$parser_error = '';
function start_tag($parser, $name, $attribs) {
	global $import_domains, $parser_error;
	if ($name == "DOMAIN") {
		if (is_array($attribs)) {
			while(list($key,$val) = each($attribs)) {
				if ($key == 'NAME') {
					$domain = strtolower($val);
					$ext = explode(".", $val, 2);
				} elseif ($key == 'EXPIRATIONDATE') {
					$expiry = date('Y-n-j', strtotime($val));
				} elseif ($key == 'REGISTRAR') {
					$registrar = $val;
				} elseif ($key == 'PRICE') {
					$price = $val;
				} elseif ($key == 'SELLSTATUS') {
					$status = $val;
				} elseif ($key == 'LANGUAGE') {
					$language = $val;
				}
			}
		}
		if (!isset($domain) || $domain == '') {
			$parser_error = _("XML Parser Error: Domain Name Missing");
		} elseif (!isset($expiry) || $expiry == '') {
			$parser_error = _("XML Parser Error: Expiry Date Missing");
		} else {
			$array_part1 = array("domain" => $domain, "expiry" => $expiry, "ext" => $ext[1]);
			if (isset($registrar)) {
				$array_part2 = array("registrar" => $registrar);
				$array_part1 = array_merge($array_part1, $array_part2);
			} else {
				if (!isset($_POST['registrar']) || $_POST['registrar'] == '') {
					$array_part2 = array("registrar" => _("None Specified"));
				} else {
					$array_part2 = array("registrar" => $_POST['registrar']);
				}
				$array_part1 = array_merge($array_part1, $array_part2);
			}
			if (isset($price)) {
				if ($status == 1) {
					$array_part3 = array("price" => "N/A");
				} else {
					$array_part3 = array("price" => $price);
				}
				$array_part1 = array_merge($array_part1, $array_part3);
			} else {
				if ($_POST['status'] == 1) {
					$array_part3 = array("price" => "N/A");
				} else {
					$array_part3 = array("price" => $_POST['price']);
				}
				$array_part1 = array_merge($array_part1, $array_part3);
			}
			if (isset($status)) {
				$array_part4 = array("status" => $status);
				$array_part1 = array_merge($array_part1, $array_part4);
			} else {
				$array_part4 = array("status" => $_POST['status']);
				$array_part1 = array_merge($array_part1, $array_part4);
			}
			if (isset($language)) {
				$array_part5 = array("language" => $language);
				$array_part1 = array_merge($array_part1, $array_part5);
			} else {
				$array_part5 = array("language" => "English");
				$array_part1 = array_merge($array_part1, $array_part5);
			}
			$import_domains[] = $array_part1;
		}
	}
} 
function end_tag($parser, $name) {
} 
function tag_contents($parser, $data) {

}
function dp_import() {
	global $wpdb, $import_domains, $parser_error;
	if (isset($_POST['import-domains'])) {
		$target_path = ABSPATH.'wp-content/plugins/domain-portfolio/temp/temp.xml';
		if ($_FILES['file']['type'] == 'text/xml') {
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
				if (! ($xmlparser = xml_parser_create()) ) {
					?><div style="background-color: #FF0000;" id="message" class="updated fade"><p><?php _e('Cannot create parser');?>.</p></div><?php
				} else {
					xml_set_element_handler($xmlparser, "start_tag", "end_tag");
					xml_set_character_data_handler($xmlparser, "tag_contents");
					if (!($fp = fopen($target_path, "r"))) { 
						?><div style="background-color: #FF0000;" id="message" class="updated fade"><p><?php _e('Cannot open file');?>.</p></div><?php
					} else {
						while ($data = fread($fp, 4096)){
							$data=eregi_replace(">"."[[:space:]]+"."< ",">< ",$data);
							if (!xml_parse($xmlparser, $data, feof($fp))) {
								$reason = xml_error_string(xml_get_error_code($xmlparser));
								$reason .= xml_get_current_line_number($xmlparser);
								die($reason);
							}
						}
						xml_parser_free($xmlparser); 
						if ($parser_error != '') {
							?><div style="background-color: #FF0000;" id="message" class="updated fade"><p><?php _e($parser_error);?>.</p></div><?php 
						} else {
							foreach ($import_domains as $domain) {
								$wpdb->query("INSERT INTO `".$wpdb->prefix."dp_domains` (id, name, expiry, price, status, registrar, ext, language) VALUES (NULL, '".$wpdb->escape($domain['domain'])."', '".$domain['expiry']."', '".$wpdb->escape($domain['price'])."', '".$wpdb->escape($domain['status'])."', '".$wpdb->escape($domain['registrar'])."', '".$domain['ext']."', '".$domain['language']."');");
							}
							?><div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><?php _e('Domain Import Successful');?>.</p></div><?php
						}
					}
				}
			} else{
				?><div style="background-color: #FF0000;" id="message" class="updated fade"><p><?php _e('File Upload ERROR');?>.</p></div><?php
			}
		} else {
				?><div style="background-color: #FF0000;" id="message" class="updated fade"><p><?php _e('Invalid File Type');?>.</p></div><?php
		}
	}
	?>
    <div class="wrap">
    <h2><?php _e('Import Domains');?></h2>
    <input type="button" class="button" value="&laquo; Back" onclick="location.href='edit.php?page=dp.php'" /><br /><br />
    <form method="post" enctype="multipart/form-data">
	<strong><?php _e('Select your file to import');?>: </strong><input type="file" name="file" /> <?php _e('XML (.xml) formats only');?><br />
    <strong><?php _e('Default Status');?>: </strong><select name="status">
    <option value="0"><?php _e('For Sale'); ?></option>
			<option value="1"><?php _e('Make Offer'); ?></option>
			<option value="2"><?php _e('Not for Sale'); ?></option>
    </select> (<?php _e('if not specified in the XML file'); ?>)<br />
    <strong><?php _e('Default Price');?>: </strong><input type="text" name="price" /> (<?php _e('if not specified in the XML file'); ?>)<br />
    <strong><?php _e('Default Registrar');?>: </strong><input type="text" name="registrar" /> (<?php _e('if not specified in the XML file'); ?>)<br />
<br /><input type="submit" class="button" value="<?php _e('Import');?> &raquo;" name="import-domains" />
    </form>
    </div>
	<?php
}
?>