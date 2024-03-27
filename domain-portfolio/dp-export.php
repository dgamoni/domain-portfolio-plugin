<?php
// Filename: dp-export.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
function dp_export() {
	global $wpdb;
	?><div class="wrap">
    <h2>Export Domains</h2>
    <input type="button" class="button" value="&laquo; Back" onclick="location.href='edit.php?page=dp.php'" /><br />
<br />
<form method="post" action="<?php echo DPEXPORT; ?>">
After clicking "Export" your domain portfolio will be saved in an XML file, which can be used to import into another domain portfolio.<br />
Also, alternatively, you can reform how you want the XML file to be formatted in the boxes below. However, by changing these parameters you may not be able to import the domain names back into the Domain Portfolio for Wordpress.<br />It is recommended that the following settings are left asis so that it may be imported back into the DN portfolio.<br />
<br />
<strong>Encoding/XML Version:</strong> <input type="text" name="encoding" size="100" value="&lt;?xml version=&quot;1.0&quot; encoding=&quot;utf-8&quot; standalone=&quot;no&quot;?&gt;" /><br />
<strong>Opening/Closing Main Tag:</strong> <input type="text" name="maintag" value="DomainList" /> will be bracketed automatically (&lt;xxx&gt; and &lt;/xxx&gt;)<br />
<strong>Item Tag:</strong> <input type="text" name="itemtag" size="100" value="&lt;Domain Name=&quot;{domain}&quot; ExpirationDate=&quot;{expiry}&quot; Registrar=&quot;{registrar}&quot; SellStatus=&quot;{status}&quot; Price=&quot;{price}&quot; Language=&quot;{language}&quot; /&gt;" /> used for each domain name in your portfolio
<br />
<input type="submit" class="button" value="Export &raquo;" name="export-domains" />
    </form>
	</div><?php
	
}
?>