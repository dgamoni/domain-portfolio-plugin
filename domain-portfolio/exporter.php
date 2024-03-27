<?php 
// Filename: exporter.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
require_once('../../../wp-config.php');
header("Content-Type: text/xml");
header("Content-Disposition: attachment;filename=\"domains-" . date("Y-m-d") . ".xml\";");
if (isset($_POST['export-domains'])) {
		$xmlout = $_POST['encoding']."\n";
		$xmlout .= "<".$_POST['maintag'].">\n";
		$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."dp_domains");
		foreach ($results as $row) {
			$xmlouttemp = $_POST['itemtag']."\n";
			$find = array("{domain}", "{expiry}", "{registrar}", "{status}", "{price}", "{language}");
			$replace = array( $row->name, trim($row->expiry), $row->registrar, $row->status, (($row->price== "N/A") ? '' : $row->price), $row->language);
			$xmlouttemp = str_replace($find, $replace, $xmlouttemp);
			$xmlout .= "	".$xmlouttemp;
		}
		$xmlout .= '</'.$_POST['maintag'].">";
		echo stripslashes($xmlout); 
}
?>