<script type='text/javascript'>
(function(d, s, c) {
  if (window._jobscore_loader) { return; } else { window._jobscore_loader = true; }
  var o = d.createElement(s); o.type = 'text/javascript'; o.async = true;
  var sc = d.getElementsByTagName(s)[0]; sc.parentNode.insertBefore(o, sc);
  o.src = ('https:' == d.location.protocol ? 'https:' : 'http:') + '//www.jobscore.com/jobs/' + c + '/widget.js';
})(document, 'script', '<?php echo get_option(WPJS_OPT_CONFIG . 'account_name') ?>');
</script>
<?php
	
$options = $wpdb->get_results( "SELECT * FROM $wpdb->options WHERE option_name LIKE '".WPJS_OPT_WIDGET."%'" );

$params = '';

foreach ($options as $option) {
	$key = str_replace(array(WPJS_OPT_WIDGET, '_'), array('data-', '-'), $option->option_name);
	$params .= ($key . '="' . $option->option_value . '" ');
}

?>

<div <?php echo $params ?> data-wp-plugin='true' class='jobscore-jobs'><div class='js-fallback' style='color:#888'></div></div>
