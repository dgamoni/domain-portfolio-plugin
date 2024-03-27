<?php
// Filename: grabber.class.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 04/02/2008
// www.borghunter.com
class Grabber {
	var $result;
	var $url;
	var $keyword;

	
	function google() {
		$this->url = "http://www.google.co.uk/search?q=".$this->keyword;
		preg_match("~of about <b>([0-9,]+)</b> for <b>~", $this->process(), $preg);
		return $preg[1];
	}
	
	function yahoo() {
		$this->url = "http://search.yahoo.co.uk/search?p=".$this->keyword;
		preg_match("~of about ([0-9,]+) for <strong>~", $this->process(), $preg);
		return $preg[1];
	}
	
	function ask() {
		$this->url = "http://www.ask.com/web?q=".$this->keyword;
		preg_match('~Showing (.*)</span> of ([0-9,]+)~', $this->process(), $preg, PREG_OFFSET_CAPTURE, 1);
		return $preg[2][0];
	}
	
	function altavista() {
		$this->url = "http://www.altavista.com/web/results?q=".$this->keyword;
		preg_match("~AltaVista found ([0-9,]+) results~", $this->process(), $preg);
		return $preg[1];
	}
	
	function msnlive() {
		$this->url = "http://search.live.com/results.aspx?q=".$this->keyword;
		preg_match("~<h2>Web results (.*) of ([0-9,]+)</span></h2>~", $this->process(), $preg, PREG_OFFSET_CAPTURE, 1);
		return $preg[2][0];
	}
	
	function keyword_detection() {
		$this->url = "http://www.google.co.uk/search?q=".$this->keyword;
		preg_match('~<font color="#cc0000" class=p>Did you mean: </font><a (.*) class=p><b><i>(.*)</i>~', $this->process(), $preg, PREG_OFFSET_CAPTURE, 1);
		if (str_replace(' ', '', $preg[2][0]) == $this->keyword) {
			return $preg[2][0];
		} else {
			return $this->keyword;
		}
	}
	
	function process() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
		$data = curl_exec($ch);
		curl_close($ch); 
		return $data;
	}
}
?>