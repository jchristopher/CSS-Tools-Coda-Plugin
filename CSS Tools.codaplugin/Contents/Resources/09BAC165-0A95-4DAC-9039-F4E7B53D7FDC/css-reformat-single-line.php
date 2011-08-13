#!/usr/bin/php
<?php

$ext = '';
$input = '';

$ext = strtolower(strrchr($_ENV['CODA_FILEPATH'], '.'));

if($ext==".css") {

	// We need CSSTidy
	require_once($_ENV['CODA_BUNDLE_SUPPORT'] . "/class.csstidy.php");
	require_once($_ENV['CODA_BUNDLE_SUPPORT'] . "/lang.inc.php");

	$fp = fopen("php://stdin", "r");
	while ( $line = fgets($fp, 1024) )
		$input .= $line;
	fclose($fp);

	$css = new csstidy();
	
	$css->set_cfg('lowercase_s',false);
	$css->set_cfg('compress_colors',false);
	$css->set_cfg('compress_font-weight',false);
	$css->set_cfg('remove_bslash',false);
	$css->set_cfg('preserve_css',true);
	$css->set_cfg('sort_selectors',false);
	$css->set_cfg('sort_properties',false);
	$css->set_cfg('remove_last_;',false);
	$css->set_cfg('discard_invalid_properties',false);
	$css->set_cfg('timestamp',false);
	
$template_single_line = '<span class="at">|</span><span class="format"></span>|<span class="selector">|</span><span class="format"> { </span>|<span class="property">|</span><span class="value">|</span><span class="format">; </span>|<span class="format">}</span>
||<span class="format">}
</span>||</span>
<span class="format"></span>|
';
	
	$css->load_template($template_single_line,false);
	$result = $css->parse($input);
	
	if($result) {
		echo $css->print->plain();
	} else {
		echo "/* CSS Reformatting Error */\n\n";
		echo $input;
	}

}

?>