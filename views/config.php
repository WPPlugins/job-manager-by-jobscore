<script type="text/javascript" charset="utf-8">

jQuery.noConflict();

if (typeof(window.$js) == "undefined") {
  (function(window) {
    var _oldjs = window.$js;
    window._jobscore = {};
    var jobscore = window._jobscore;
    jobscore.fn = {};
    window.$js = jobscore;

    // register methods - can just add them to $js.fn as well
    jobscore.register = function(name, func) {
      jobscore.fn[name] = func;
    }
  })(window);
}
</script>

<script type="text/javascript">

	// make underscore's templates look like mustache/handlebars so we don't have erb conflicts
	// {{ statement }} is a control statement
	// {{= statement }} auto html escapes output (including quotes(??) beware)
	// {{raw stateent }} outputs non-escaped results
	jQuery(function () 
	{ 
		_.templateSettings = {
		  evaluate    : /\{\{([\s\S]+?)\}\}/gim,
		  interpolate : /\{\{raw([\s\S]+?)\}\}/gim,
		  escape      : /\{\{\=([\s\S]+?)\}\}/gim
		};
	});
	
	String.prototype.bool = function() {
	    return (/^true$/i).test(this);
	};
	
	// patch up String to do the right thing
	String.prototype.capitalize = function() {
		return this.replace(/\w\S*/g, function(txt) { return txt.charAt(0).toUpperCase() + txt.slice(1).toLowerCase(); });
	}

	String.prototype.dasherize = function() {
		return this.replace(/[\W_]/g, '-');
	}

	String.prototype.underscoreize = function() {
		return this.replace(/[\W]/g, '_');
	}

	String.prototype.spacerize = function() {
		return this.replace(/[\W_\-]/g, ' ');
	}

	String.prototype.domidize = function() {
		return "param-" + this.dasherize();
	}

	String.prototype.destidize = function() {
		return "param-" + this.dasherize();
	}

	String.prototype.srcidize = function() {
		return "proxy-" + this.dasherize();
	}

	String.prototype.parameterize = function() {
		return this.replace(/param[\-]/, '') + this.underscoreize();
	}

	String.prototype.reversize = function() {
		if (this.indexOf('_reverse') != -1) {
			return this.replace(/_reverse/, '');
		} else {
			return this + "_reverse";
		}
	}

	function blank(s)
	{
		if (s === null)
		return true;
		if (s === "")
		return true;
		if (s instanceof String) {
			var n = s.replace(/\s/g, "");
			if (n === "") {
				return true;
			}
		}
		return false;
	}
	
	function invert_color(color){
	   return (color.replace('#','0x')) > (0xffffff/2) ? 'black' : 'white'
	}
	
	var WIDGET_PARAMS = {
	  text_color:         {'type':'color', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'text_color') ?>', 'default':'#333333'},
	  bg_color:           {'type':'color', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'bg_color') ?>', 'default':"transparent"},
	  font_family:        {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'font_family') ?>', 'default':"Arial, Helvetica, sans-serif"},
	  font_size:          {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'font_size') ?>', 'default':"12px"},
	  line_height:        {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'line_height') ?>', 'default':"15px"},
	  link_text_color:    {'type':'color', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'link_text_color') ?>', 'default':"#3333FF"},
	  link_bg_color:      {'type':'color', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'link_bg_color') ?>', 'default':"transparent"},
	  link_font_weight:   {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'link_font_weight') ?>', 'default':"normal"},
	  link_font_size:     {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'link_font_size') ?>','default':"12px"},
	  header_text_color:  {'type':'color', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'header_text_color') ?>', 'default':"#333333"},
	  header_bg_color:    {'type':'color', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'header_bg_color') ?>', 'default':"#F0F0F0"},
	  header_font_size:   {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'header_font_size') ?>','default':"0.9em"},
	  header_line_height: {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'header_line_height') ?>', 'default':"1.0em"},
	  zebra_stripe:       {'type':'checkbox', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'zebra_stripe') ?>', 'default':false},
	  odd_row_bg_color:   {'type':'color', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'odd_row_bg_color') ?>', 'default':"transparent"},
	  even_row_bg_color:  {'type':'color', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'even_row_bg_color') ?>', 'default':"#F9F9FF"},
	  row_border_bottom:  {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'row_border_bottom') ?>', 'default':"none"},
	  row_border_top:     {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'row_border_top') ?>', 'default':"none"},

	  source_subname:     {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'source_subname') ?>', 'default':null},

	  css_url:            {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'css_url') ?>', 'default':null},
	  width:              {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'width') ?>', 'default':"auto"},

	  display_fields:     {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'display_fields') ?>', 'default':'title,department,location'},
	  sort_by:            {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'sort_by') ?>', 'default':'department,title'},

	  filter_fields:      {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'filter_fields') ?>', 'default':'department,location'},
	  filters:            {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'filters') ?>', 'default':null},
	  group_by:           {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'group_by') ?>', 'default':null},

	  list_type:          {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'list_type') ?>', 'default':"auto"}, // "multicolumn, grouped, single, auto"

	  show_searchbar_count: {'type':'text',  'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'show_searchbar_count') ?>', 'default':10},
	  show_logo:          {'type':'checkbox', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'show_logo') ?>', 'default':true},
	  show_social_sharing: {'type':'text', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'show_social_sharing') ?>', 'default':'none'},
	  show_talent_network: {'type':'checkbox', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'show_talent_network') ?>', 'default':true},
	  show_header:        {'type':'checkbox', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'show_header') ?>', 'default':true},
	  sample_jobs_count:  {'type':'text', 'value': '<?php echo (int) get_option(WPJS_OPT_WIDGET . 'sample_jobs_count') ?>', 'default':  null},

	  feed_url:           {'type':'text', 'value': '<?php echo get_option(WPJS_OPT_WIDGET . 'feed_url') ?>', 'default':null}
	};
		
	function toggle_widget_params(param_val) {

		jQuery.each(WIDGET_PARAMS, function(option, params) 
		{
			
			if (option != "show_logo")
			{
			
				var src_id = "#" + option.srcidize();
				var src_ele = jQuery(src_id);
				
				if (param_val) {
					src_ele.css('opacity', 1)
						   .attr('disabled', false);
					
				} else {
					
					var param_default = params['default'];
					
					if (src_ele) 
					{
						if (params['type'] == 'color') 
						{
							if (param_default == 'transparent') {
								jQuery(src_id + '-transparent').attr('checked', 'checked');
								
								jQuery(src_id).css('background-color', '#ffffff');
								jQuery(src_id).css('color', invert_color('#ffffff'));
								
								src_ele.val('#ffffff');
							} else {

								jQuery(src_id).css('background-color', param_default);
								jQuery(src_id).css('color', invert_color(param_default));
								
								src_ele.val(param_default).trigger('change');
							}
							
						} else if (params['type'] == 'checkbox') {
							src_ele.attr('checked', param_default);
						} else {
							src_ele.val(param_default).trigger('change');
						}
					}
					
					src_ele.css('opacity', 0.5)
					.attr('disabled', true);
				}
				
				
			}
		});
		
		if (param_val) {
			jQuery('.requires-show-logo').css('opacity', 1)
				   .attr('disabled', false);
		} else {
			
			jQuery("#list-type").val("grouped").trigger('change');
			jQuery("#group-by-row").show();
			jQuery("#group-by").val("department");
			jQuery("#sort-by-1").val("department");
			jQuery("#sort-by-2").val("title");
			jQuery("#sort-by-3").val("none");
			jQuery("#proxy-sample-jobs-count").val("0");
			
			jQuery("#dropdown-filter-department, #dropdown-filter-location").attr('checked', true);
			jQuery('#zebra-stripe-row, #select-columns-row').hide();
			
			jQuery('.requires-show-logo').css('opacity', 0.5)
					.attr('disabled', true);
		}
		
	}
				
	jQuery(function () 
	{				
		jQuery("#proxy-show-logo").click(function () {
			var param_val = jQuery(this).is(':checked');
			toggle_widget_params(param_val);
		});

		toggle_widget_params(jQuery('#proxy-show-logo').is(':checked'));
		
		jQuery("form").submit(function () {
			
			jQuery('#ajax-message').hide();
			jQuery('.ajax-feedback').css('visibility', 'visible');
			
			var opts = $js.fn.getOptions();
			var account_name = get_account_code(false);
			var page_id = jQuery('#page-id').val();
			
			jQuery.post(
			   ajaxurl, 
			   {
			      'action':'admin_config',
				  'account_name': account_name,
				  'page_id': page_id,
				  'widget_options': opts
			   }, function(response)
			   {
				   var json = jQuery.parseJSON(response);
				   
				   if (json.success == 1) {
					   jQuery('.ajax-feedback').css('visibility', 'hidden');
					   jQuery('#ajax-message').slideDown('fast');

					   if (jQuery('#permalink_preview').html() == null) {
						   jQuery('.ajax-feedback').before('<a id="permalink_preview" target="_blank" href="'+json.permalink+'">Click here to preview your jobs page</a>')
					   }
				   }
		   });
			
			return false;
		});
	});

	function init_farbtastic_elements () {
		
		jQuery.each(WIDGET_PARAMS, function(option, params) 
		{
			var picker	= '#' + option.srcidize() + '-picker';
			var text	= '#' + option.srcidize();
			
			if (params.type == "color" && jQuery(picker).length > 0) {
				
				jQuery(picker).farbtastic(text);
								
				jQuery(text).focus(function()
				{
					jQuery(picker).fadeIn();
				});
				
				jQuery(text).blur(function()
				{
					jQuery(picker).hide();
				});
			}
		});
	}

	/*
	 * @param [String] careers_site_url An URL. Supported formats: "https://www.jobscore.com/jobs/jobscore", "https://www.jobscore.com/jobs/jobscore/list", "https://www.jobscore.com/jobs2/jobscore", "www.jobscore.com/jobs/jobscore", "jobscore.com/jobs2/jobscore?a=a", "http://jobscore.jobscore.com"
	 * @return [String] account code extracted from the URL
	 */
	function find_account_code(careers_site_url) {
		// Tries to match with the most usual URL version
		var matches = careers_site_url.match(new RegExp("\/jobs[2]?\/([^\/?]+)"));
		accountcode = undefined;
		if(matches == undefined) {
			// Might be using future URLs (http://foursquare.jobscore.com), let's try that:
			matches = careers_site_url.match(new RegExp("([^\/\.]+)\.jobscore\.com"));
		}
		if(matches != undefined) accountcode = matches[1];
		return(accountcode);
	}

	function get_account_code(show_sample_jobs_account) 
	{
		var js_account_input= jQuery('#jobscore-account-name').val();
		var js_account_code = find_account_code(js_account_input);
		
		js_account_code = js_account_code ? js_account_code : js_account_input;
				
		show_sample_jobs_account = typeof show_sample_jobs_account == 'undefined' ? true : show_sample_jobs_account;
		
		return js_account_code ? js_account_code : (show_sample_jobs_account ? 'jobscore' : '');		
	}

</script>

<div class="wrap">
	
	<div id="ajax-message" class="update-nag" style="padding: 5px; display: none">
		Settings saved successfully
	</div>
	
	<form action="" method="post">
		<div style="overflow: hidden">
			<div style="float: left; min-width: 400px">
				<div id="icon-options-general" class="icon32"></div>
				<h2><?php _e('JobScore Plugin Configuration'); ?></h2>
			</div>

		
			<div style="float: right; padding: 10px 0">
					<?php if ($permalink != '') { ?>
					<a id="permalink_preview" target="_blank" href="<?php echo $permalink; ?>">Click here to preview your jobs page</a>
					<?php } ?>

					<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-feedback" title="" alt="" />
					
					<button type="submit" class='button-primary'>Save configuration</button>
			</div>
		</div>
		
		<table class='layout'>
			<tr>
				<td id='options-pane' valign='top' class="metabox-holder">

					<div class='postbox-container'>
						
						<div class='postbox'>
							<h3 class="hndle"> 
								Basic Controls
							</h3>
							<div class='inside'>

								<table class='options'>
									
									<tr>
										<td>
											<label for="jobscore-account-name">JobScore Careers Site URL:</label>
										</td>
										<td>
											<input value="<?php echo $account_name ?>" id='jobscore-account-name' placeholder="Your account name" style="width: 160px">
										</td>
										<td width="100">
											<a  href="https://www.jobscore.com/employer/admin/edit_account/" target="_blank">Get your Careers Site URL</a>
										</td>
									</tr>
									
									<tr>
										<td>
											<label for="jobscore-account-name">Display your jobs on this page:</label>
										</td>
										<td>
											<?php echo $page_list ?> <br />
											<em>The jobs will be shown at the bottom of the page.</em>
										</td>
									</tr>
									
									
									<tr>
										<td>
											Powered By:
										</td>
										<td>
											<input type='checkbox' <?php echo !get_option(WPJS_OPT_WIDGET . 'show_logo') ? 'checked' : ''  ?>  id='proxy-show-logo'></input><label class='checkbox' for='proxy-show-logo'>Show powered by JobScore</label>
										</td>
									</tr>
									
									<tr>
										<td class='requires-show-logo'>
											Layout Type:
										</td>
										<td>
											<select class='requires-show-logo' id='list-type'>
												<option <?php echo get_option(WPJS_OPT_WIDGET . 'list_type') == 'auto' ? 'selected' : '' ?> value='auto'>Auto</option>
												<option <?php echo get_option(WPJS_OPT_WIDGET . 'list_type') == 'single' ? 'selected' : '' ?> value='single'>Single Column</option>
												<option <?php echo get_option(WPJS_OPT_WIDGET . 'list_type') == 'multi' ? 'selected' : '' ?> value='multi'>Multi Column</option>
												<option <?php echo get_option(WPJS_OPT_WIDGET . 'list_type') == 'grouped' || !get_option(WPJS_OPT_WIDGET . 'list_type') ? 'selected' : '' ?> value='grouped'>Grouped</option>
											</select>
										</td>
									</tr>
									<tr id='zebra-stripe-row'>
										<td class='requires-show-logo'>
											Zebra Stripe:
										</td>
										<td>
											<input class='requires-show-logo' type='checkbox' <?php echo get_option(WPJS_OPT_WIDGET . 'zebra_stripe') == 'true' ? 'checked="checked"' : "" ?> name="zebra_stripe" id='proxy-zebra-stripe'></input><label class='checkbox' for='proxy-zebra-stripe'>Alternating Row Colors</label>
										</td>
									</tr>
									<tr id='group-by-row' style='display:none;'>
										<td class='requires-show-logo'>
											Group by:
										</td>
										<td>
											<select class='requires-show-logo' id='group-by' disabled='disabled'>
												<option <?php echo get_option(WPJS_OPT_WIDGET . 'group_by') == 'none' ? 'selected="selected"' : "" ?> value='none'>None</option>
												<option <?php echo get_option(WPJS_OPT_WIDGET . 'group_by') == 'department' ? 'selected="selected"' : "" ?> value='department'>Department</option>
												<option <?php echo get_option(WPJS_OPT_WIDGET . 'group_by') == 'location' ? 'selected="selected"' : "" ?> value='location'>Location</option>
											</select>
										</td>
									</tr>
									<tr id='select-columns-row'>
										<td class='requires-show-logo'>
											Show Columns:
										</td>
										<?php
											
										$display_fields = get_option(WPJS_OPT_WIDGET . 'display_fields');

										?>
										<td>
											<input class='requires-show-logo' <?php echo preg_match("/department/i", $display_fields) || !$display_fields ? "checked" : "" ?> type='checkbox'  id='column-department'></input><label class='checkbox' for='column-department'>Department</label>
											<input class='requires-show-logo' <?php echo preg_match("/location/i", $display_fields) || !$display_fields ? "checked" : "" ?> type='checkbox'  id='column-location'></input><label class='checkbox' for='column-location'>Location</label>
										</td>
									</tr>
									<tr id='sort-columns-row'>
										<td class='requires-show-logo' valign='top'>
											Sort Jobs By:
										</td>
										
										<?php 
										
										$sort_by_1 = $sort_by_2 = $sort_by_3 = '';
										
										$sort_by_option = get_option(WPJS_OPT_WIDGET . 'sort_by');
										
										$sort_by_option_array = explode(",", $sort_by_option); 
										
										if (isset($sort_by_option_array[0])) {
											$sort_by_1 = $sort_by_option_array[0];
										}
										
										if (isset($sort_by_option_array[1])) {
											$sort_by_2 = $sort_by_option_array[1];
										}
										
										if (isset($sort_by_option_array[2])) {
											$sort_by_3 = $sort_by_option_array[2];
										}
										
										?>
										
										<td id='sort-by'>
											<select class='requires-show-logo' id='sort-by-1'>
												<option value='none'>---</option>
												<option <?php echo $sort_by_1 == 'department' || !$sort_by_1 ? 'selected' : '' ?> value='department'>Department</option>
												<option <?php echo $sort_by_1 == 'department_reverse' ? 'selected' : '' ?> value='department_reverse'>Reverse Department</option>
												<option <?php echo $sort_by_1 == 'location' ? 'selected' : '' ?> value='location'>Location</option>
												<option <?php echo $sort_by_1 == 'location_reverse' ? 'selected' : '' ?> value='location_reverse'>Reverse Location</option>
												<option <?php echo $sort_by_1 == 'title' ? 'selected' : '' ?> value='title'>Title</option>
												<option <?php echo $sort_by_1 == 'title_reverse' ? 'selected' : '' ?> value='title_reverse'>Reverse Title</option>
												<option <?php echo $sort_by_1 == 'country' ? 'selected' : '' ?> value='country'>Country</option>
												<option <?php echo $sort_by_1 == 'country_reverse' ? 'selected' : '' ?> value='country_reverse'>Reverse Country</option>
												<option <?php echo $sort_by_1 == 'city' ? 'selected' : '' ?> value='city'>City</option>
												<option <?php echo $sort_by_1 == 'city_reverse' ? 'selected' : '' ?> value='city_reverse'>Reverse City</option>
												<option <?php echo $sort_by_1 == 'state' ? 'selected' : '' ?> value='state'>State</option>
												<option <?php echo $sort_by_1 == 'state_reverse' ? 'selected' : '' ?> value='state_reverse'>Reverse State</option>
												<option <?php echo $sort_by_1 == 'opened_date' ? 'selected' : '' ?> value='opened_date'>Date Posted</option>
												<option <?php echo $sort_by_1 == 'opened_date_reverse' ? 'selected' : '' ?> value='opened_date_reverse'>Reverse Date</option>
											</select> <span class='requires-show-logo'>&nbsp;then&nbsp;by</span>
											<br>
											<select class='requires-show-logo' id='sort-by-2'>
												<option value='none'>---</option>
												<option <?php echo $sort_by_2 == 'department' || !$sort_by_2 ? 'selected' : '' ?> value='department'>Department</option>
												<option <?php echo $sort_by_2 == 'department_reverse' ? 'selected' : '' ?> value='department_reverse'>Reverse Department</option>
												<option <?php echo $sort_by_2 == 'location' ? 'selected' : '' ?> value='location'>Location</option>
												<option <?php echo $sort_by_2 == 'location_reverse' ? 'selected' : '' ?> value='location_reverse'>Reverse Location</option>
												<option <?php echo $sort_by_2 == 'title' || !$sort_by_2 ? 'selected' : '' ?> value='title'>Title</option>
												<option <?php echo $sort_by_2 == 'title_reverse' ? 'selected' : '' ?> value='title_reverse'>Reverse Title</option>
												<option <?php echo $sort_by_2 == 'country' ? 'selected' : '' ?> value='country'>Country</option>
												<option <?php echo $sort_by_2 == 'country_reverse' ? 'selected' : '' ?> value='country_reverse'>Reverse Country</option>
												<option <?php echo $sort_by_2 == 'city' ? 'selected' : '' ?> value='city'>City</option>
												<option <?php echo $sort_by_2 == 'city_reverse' ? 'selected' : '' ?> value='city_reverse'>Reverse City</option>
												<option <?php echo $sort_by_2 == 'state' ? 'selected' : '' ?> value='state'>State</option>
												<option <?php echo $sort_by_2 == 'state_reverse' ? 'selected' : '' ?> value='state_reverse'>Reverse State</option>
												<option <?php echo $sort_by_2 == 'opened_date' ? 'selected' : '' ?> value='opened_date'>Date Posted</option>
												<option <?php echo $sort_by_2 == 'opened_date_reverse' ? 'selected' : '' ?> value='opened_date_reverse'>Reverse Date</option>
												
											</select>
											<span class='requires-show-logo'>then by</span>
											<br>
											<select class='requires-show-logo' id='sort-by-3'>
												<option value='none' selected='selected'>---</option>
												<option value='none'>---</option>
												<option <?php echo $sort_by_3 == 'department' ? 'selected' : '' ?> value='department'>Department</option>
												<option <?php echo $sort_by_3 == 'department_reverse' ? 'selected' : '' ?> value='department_reverse'>Reverse Department</option>
												<option <?php echo $sort_by_3 == 'location' ? 'selected' : '' ?> value='location'>Location</option>
												<option <?php echo $sort_by_3 == 'location_reverse' ? 'selected' : '' ?> value='location_reverse'>Reverse Location</option>
												<option <?php echo $sort_by_3 == 'title'  ? 'selected' : '' ?> value='title'>Title</option>
												<option <?php echo $sort_by_3 == 'title_reverse' ? 'selected' : '' ?> value='title_reverse'>Reverse Title</option>
												<option <?php echo $sort_by_3 == 'country' ? 'selected' : '' ?> value='country'>Country</option>
												<option <?php echo $sort_by_3 == 'country_reverse' ? 'selected' : '' ?> value='country_reverse'>Reverse Country</option>
												<option <?php echo $sort_by_3 == 'city' ? 'selected' : '' ?> value='city'>City</option>
												<option <?php echo $sort_by_3 == 'city_reverse' ? 'selected' : '' ?> value='city_reverse'>Reverse City</option>
												<option <?php echo $sort_by_3 == 'state' ? 'selected' : '' ?> value='state'>State</option>
												<option <?php echo $sort_by_3 == 'state_reverse' ? 'selected' : '' ?> value='state_reverse'>Reverse State</option>
												<option <?php echo $sort_by_3 == 'opened_date' ? 'selected' : '' ?> value='opened_date'>Date Posted</option>
												<option <?php echo $sort_by_3 == 'opened_date_reverse' ? 'selected' : '' ?> value='opened_date_reverse'>Reverse Date</option>
												
											</select>
										</td>
									</tr>

									<tr>
										<td colspan='2' style='padding:0;margin:0;'>
											<div style='border-bottom:#ccc 1px solid'></div>
										</td>
									</tr>
									<tr>
										<td class='requires-show-logo'>
											Preview With:
										</td>
										
										<?php
											
										$sample_jobs_count = get_option(WPJS_OPT_WIDGET . 'sample_jobs_count');
											
										?>
										
										<td>
											<select id='proxy-sample-jobs-count'>
												<option <?php echo $sample_jobs_count === '0' || !$sample_jobs_count ? 'selected' : '' ?> value='0'>Open Jobs</option>
												<option <?php echo $sample_jobs_count == '5' ? 'selected="selected"' : '' ?> value='5'>5 Sample Jobs</option>
												<option <?php echo $sample_jobs_count == '10' ? 'selected' : '' ?> value='10'>10 Sample Jobs</option>
												<option <?php echo $sample_jobs_count == '20' ? 'selected' : '' ?> value='20'>20 Sample Jobs</option>
												<option <?php echo $sample_jobs_count == '40' ? 'selected' : '' ?> value='40'>40 Sample Jobs</option>
												<option <?php echo $sample_jobs_count == '100' ? 'selected' : '' ?> value='100'>100 Sample Jobs</option>
											</select>
										</td>
									</tr>
								</table>
							</div>
						</div>
					<!-- postbox -->

					<!-- postbox -->
					<div class='postbox'>
						
						<h3 class="hndle">
							Advanced Controls
						</h3>
						<div class='inside'>

								<div id="message" class="update-nag">
							       Advanced controls are only available for employers on the JobScore Premium or Enterprise Plan <a target="_blank"  href="//www.jobscore.com/employer/plans/upgrade?missing_feature=widget_advanced_controls">Upgrade here  &raquo;</a>
							    </div>

								<table class='options'>
									<tr>
										<td class='requires-show-logo'>
											Text Color:
										</td>
										<td>
											<input value="" type='text' id='proxy-text-color' class='color {hash: true}'></input>
											<div id="proxy-text-color-picker" class="picker"></div>
										</td>
									</tr>
									<tr>
										<td class='requires-show-logo'>
											Background Color:
										</td>
										<td>
											<input type='text' id='proxy-bg-color' class='color {hash: true}'></input>
											<input class='requires-show-logo' type='checkbox' id='proxy-bg-color-transparent'></input> <span class='requires-show-logo'>transparent</span>
											<div id="proxy-bg-color-picker" class="picker"></div>
										</td>
									</tr>
									<tr>
										<td class='requires-show-logo'>
											Link Text Color:
										</td>
										<td>
											<input type='text' id='proxy-link-text-color' class='color {hash: true}'></input>
											<div id="proxy-link-text-color-picker" class="picker"></div>
										</td>
									</tr>
									<tr>
										<td class='requires-show-logo'>
											Header Text Color:
										</td>
										<td>
											<input type='text' name="header_text_color" id='proxy-header-text-color'></input>
											<div id="proxy-header-text-color-picker" class="picker"></div>
										</td>
									</tr>
									<tr>
										<td class='requires-show-logo'>
											Header Bkgnd Color:
										</td>
										<td>
											<input type='text' id='proxy-header-bg-color' class='color {hash: true}'></input>
											<div id="proxy-header-bg-color-picker" class="picker"></div>
										</td>
									</tr>
									<tr>
										<td class='requires-show-logo' valign='top'>
											Font:
										</td>
										<td>
											<select id='proxy-font-size'>
												<?php
													
												$i = 6;
												for ($i; $i <= 24; $i++) {
													$v = $i . "px";
													?>
													<option value="<?php echo $v ?>"><?php echo $v ?></option>
													<?php
												}
													
												?>
											</select>
											<select id='proxy-font-family'>
												<?php 
												
												foreach ($font_family_list as $font) { 

													$font_display_name = array_shift($font);
													$font_value = array_shift($font);
													?>
												<option <?php echo $font_family == $fid ? "selected" : NULL ?> value="<?php echo $font_value ?>"><?php echo str_replace("'", '', $font_display_name) ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td class='requires-show-logo'> 
											Sharing Icons:
										</td>
										<td>
											<select id='proxy-show-social-sharing'>
												<option value='none' default='default'>Don't Display</options>
													<option value='top'>Display on Top</options>
														<option value='bottom'>Display on Bottom</options>
															<option value='both'>Display on Top and Bottom</options>
															</select>
														</td>
													</tr>
													<tr>
														<td class='requires-show-logo'>
															Show Filters For:
														</td>
														<?php
															
														$filter_fields = get_option(WPJS_OPT_WIDGET . 'filter_fields');
															
														?>
														<td>
															<input class='requires-show-logo' <?php echo preg_match("/department/i", $filter_fields) || !$filter_fields ? "checked" : "" ?> type='checkbox'  id='dropdown-filter-department'></input><label class='requires-show-logo checkbox' for='dropdown-filter-department'>Department</label>
															<input class='requires-show-logo' <?php echo preg_match("/location/i", $filter_fields) || !$filter_fields ? "checked" : "" ?> type='checkbox'  id='dropdown-filter-location'></input><label class='checkbox requires-show-logo' for='dropdown-filter-location'>Location</label>
														</td>
													</tr>
													<tr>
														<td class='requires-show-logo'>
															Show Header:
														</td>
														<td>
															<input type='checkbox' <?php echo !get_option(WPJS_OPT_WIDGET . 'show_header') ? 'checked' : ''  ?> id='proxy-show-header'></input><label class='checkbox requires-show-logo' for='proxy-show-header'>show table header</label>
														</td>
													</tr>
																									
													<tr>
														<td class='requires-show-logo'> 
															Show Talent Network:
														</td>
														<td>
															<input type='checkbox' <?php echo !get_option(WPJS_OPT_WIDGET . 'show_talent_network') ? 'checked' : ''  ?> checked='checked' id='proxy-show-talent-network'></input><label class='checkbox requires-show-logo' for='proxy-show-talent-network'>show network apply link</label>
														</td>
													</tr>
													
													
													<tr>
														<td class='requires-show-logo'>
															Show Search Bar:
														</td>
														<td>
															<?php
																
															$searchbar_count = get_option(WPJS_OPT_WIDGET . 'show_searchbar_count');
																
															?>
															<select id='proxy-show-searchbar-count'>
																<option <?php echo $searchbar_count == '-1' ? "selected" : "" ?> value='-1'>Never Show</options>
																<option <?php echo $searchbar_count == '0' ? "selected" : "" ?> value='0'>Always Show</options>
																<option <?php echo $searchbar_count == '10' || !$searchbar_count ? "selected" : "" ?> value='10'>After 10 Jobs Shown</options>
																<option <?php echo $searchbar_count == '20' ? "selected" : "" ?> value='20'>After 20 Jobs Shown</options>
																<option <?php echo $searchbar_count == '30' ? "selected" : "" ?> value='30'>After 30 Jobs Shown</options>
																<option <?php echo $searchbar_count == '50' ? "selected" : "" ?> value='50'>After 50 Jobs Shown</options>
																<option <?php echo $searchbar_count == '100' ? "selected" : "" ?> value='100'>After 100 Jobs Shown</options>
																						</select>
																					</td>
																				</tr>
																				<tr>
																					<td class='requires-show-logo'>
																						CSS URL:
																					</td>
																					<td>
																						<input type='text' id='proxy-css-url' class='' size='30'></input>
																					</td>
																				</tr>
																				<tr>
																					<td>
																						Developer Info:
																					</td>
																					<td>
																						<a href='https://support.jobscore.com/entries/20655207-embedding-the-jobscore-jobs-widget-on-your-careers-page-beta-release'>Advanced Integration Documentation &raquo;</a>
																					</td>
																				</tr>
																			</table>
																			
																		</div><!-- panel -->

																	</div><!--section-body -->
																																		
																	
																</div>
															</div>
														</div>

													</td>
													<td valign='top' class="metabox-holder" style='padding-left:14px'>

														<div style="width: 100%" class='postbox-container'>
															<div class='postbox'>
																<div class='border'>
																	<h3 class="hndle">
																		Careers Page Preview
																	</h3>
																	<div class='inside'>

																		<div id='widget-preview-container' class='panel'>
																			<div id='widget'>
																			</div>
																		</div>


																	</div><!--section-body -->
																</div><!-- border -->
															</div>
														</div>
																
													</td>
												</tr>
											</table>



		<div class='shadow-outer' style='display:none;'>
			<div class='shadow'>
				<div class='border'>
					<div class='sectionHead_md expandable collapsed'>
						Advanced Options
					</div>
					<div class='section-body'>

						<table id='advanced-options-table' class='options'>
							<!-- advanced options get rendered here -->
						</table>
					</div>
				</div>
			</div>
		</div>

	
		
	</form>
		
</div>

<script type='text/javascript' src='<?php echo WPJS_URL . '/js/widget_builder.js' ?>'></script>
<script type='text/javascript' src='<?php echo WPJS_URL . '/js/widget_base.js' ?>' id='widget-loader-script'></script>

<script id='template-expert-options' type='text/x-template-underscore'>
  {{ var default_val = ''; }}
  {{ jQuery.each(fields, function(option, params) { }}
    {{ default_val = params['default'] ? 'value="' + params['default'] + '"' : ''; }}
  <tr>
    <td>{{= option.spacerize().capitalize() }}:</td>
    <td>
    {{ if (params.type == 'text') { }}
      <input id='{{= option.domidize() }}' {{raw default_val }} type='text' size='44'></input>
    {{ } else if (params.type =='checkbox') { }}
        {{ if (params['default'] == true) { }}
          <input id='{{raw option.domidize() }}' type='checkbox' checked='checked'></input>
        {{ } else { }}
          <input id='{{raw option.domidize() }}' type='checkbox'></input>
        {{ } }}
    {{ } else if (params.type =='color') { }}
      <input id='{{raw option.domidize() }}' {{raw default_val }} type='text'></input>
    {{ } else { }}
      <input id='{{raw option.domidize() }}' {{raw default_val }} type='text'></input>
    {{ } }}
    </td>
  </tr>
  {{ }); }}
</script>
