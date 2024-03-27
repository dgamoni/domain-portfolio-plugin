<?php
// Filename: dp-install.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
function dp_install () {
   global $wpdb, $dp_version, $wp_version, $user_ID, $user_identity;
   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	  
   $table_name = $wpdb->prefix . "dp_domains";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name text NOT NULL,
	  expiry text NOT NULL,
	  price VARCHAR(55) NOT NULL,
	  status VARCHAR(55) NOT NULL,
	  registrar VARCHAR(55) NOT NULL,
	  UNIQUE KEY id (id)
	);";
	  
      dbDelta($sql);
   }
   
   if (get_option('dp_version') < 1.0) {
	  	//Updating to 1.0
	  	$wpdb->query("ALTER TABLE ".$table_name." ADD `ext` TEXT NOT NULL ;");
		//Adding values for the new 'ext' column
		$selectval = "SELECT * FROM ".$table_name.";";
		$results = $wpdb->get_results($selectval);
		foreach ($results as $row) {
			$ext = explode('.', $row->name,2);
			$wpdb->query("UPDATE ".$table_name." SET `ext` = '".$ext[1]."' WHERE `id` =".$row->id." LIMIT 1 ;");
		}
	}
	
	if (get_option('dp_version') < 1.1) {
		//Updating to 1.1
	  	$wpdb->query("ALTER TABLE ".$table_name." ADD `language` TEXT NOT NULL ;");
		//Adding values for the new 'ext' column
		$selectval = "SELECT * FROM ".$table_name.";";
		$results = $wpdb->get_results($selectval);
		foreach ($results as $row) {
			$wpdb->query("UPDATE ".$table_name." SET `language` = 'English' WHERE `id` =".$row->id." LIMIT 1 ;");
		}
	}
	
// If it's fresh install
if (get_option('dp_version') == '') {
	if(version_compare($wp_version, '2.1', '<')) {
		//== WP 2.0 VERSION
		$wpdb->query("INSERT INTO ".$wpdb->prefix."posts ( post_status, post_title, post_name, comment_status, ping_status, post_author, post_date, post_modified ) VALUES ( 'static', 'Domain Portfolio, 'dnportfolio', 'closed', 'closed', ".$user_ID." , now('Y-m-d G:i:s') , now('Y-m-d G:i:s'))");
	} else {
		//== WP 2.1 to 2.3 VERSION
		$wpdb->query("INSERT INTO ".$wpdb->prefix."posts ( post_type, post_status, post_title, post_name, comment_status, ping_status, post_author, post_date, post_modified ) VALUES ( 'page', 'publish', 'Domain Portfolio', 'dnportfolio', 'closed', 'closed', ".$user_ID." , now('Y-m-d G:i:s') , now('Y-m-d G:i:s'))");
	}
	// Get the page id
	$id = $wpdb->insert_id;
 	// update the guid for the new page
	$guid = get_permalink($id);
	$wpdb->query("UPDATE ".$wpdb->prefix."posts SET guid='".$guid."' WHERE ID=".$id);
	add_option("dp_page", $id, "Page that Domain Portfolio should appear", "no");
	update_option("dp_page", $id);
}
      add_option("dp_version", $dp_version);
	  update_option("dp_version", $dp_version);
	  if (get_option("dp_currency") == "") {
	  	add_option("dp_currency", "GBP", "Default Price Currency", "no");
	  }
	  if (get_option("dp_dompp") == "") {
		  add_option("dp_dompp", "10");
	  }
	  if (get_option("dp_search") == "") {
		  add_option("dp_search", 1);
	  }
	  if (get_option("dp_visible") == "") {
		  add_option("dp_visible", "name,expiry,registrar,price,status");
	  }
	  if (get_option("dp_contactfields") == "") {
		  add_option("dp_visible", "ext,keywords,google,msn,yahoo");
	  }
	  if (get_option("dp_contact") == "") {
		  add_option("dp_contact", 1);
	  }
	  if (get_option("dp_links") == "") {
		  add_option("dp_links", 0);
	  }
	  if (get_option("dp_intro") == "") {
	  	add_option("dp_intro", "Welcome to my domain name portfolio, I currently own {total} domain names, {forsale} of which are for sale. Feel free to browse through my portfolio and contact me if you are interested in purchasing any that are for sale.

Thank you.");
	  }
	  	 add_option("dp_style", "");
	 	 update_option("dp_style",
"table.dptable {
	width:100%;
	border:0;
}
tr.dphead {
	background-color:#0066CC;
	color:#FFFFFF;
	font-weight:bold;
}
tr.dphead td a {
	color:#FFFFFF;
}
tr.dprow {
	background-color:#FFFFFF;
}
tr.dprowalt {
	background-color:#CCCCCC;
}
#dpcols-contact {
	text-align:center;
}
#intro {
}");
	

}
?>