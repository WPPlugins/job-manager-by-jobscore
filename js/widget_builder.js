var widget_builder = function($) {

  $(function() {
    "use strict";

    // trigger a widget reload each time a form element changes
	var live_update = function(e) {
      update_widget();
      window._jobscore_widget_reload();
      e.preventDefault();
      e.stopPropagation();
    };
	
    $("form").on("change", live_update);
	$(".color").on('blur', live_update);

    // render advanced options tab
    var tmpl = _.template($("#template-expert-options").html());
    $('#advanced-options-table').html(tmpl({fields:WIDGET_PARAMS}));

    // expandable/collapseable section headers
    $('.expandable').each(function(index) {
      var ele = $(this);
      var collapsed = $(this).hasClass('collapsed');
      var current_html = $(this).html();
      // TODO: make a template
      ele.html("<div class='clickable'>" +
                   "<div style='float:left;padding-right:6px'>" +
                   "<a class='expand-button' href='#'><img src='//www.jobscore.com/images/icon_plus.gif'></a>" +
                   "<a class='collapse-button' href='#'><img src='//www.jobscore.com/images/icon_minus.gif'></a>" +
                   "</div>" + current_html +
                   "</div>");
    });

    $('.clickable').on('click', function() {
      var ele = $(this);
      var body = ele.parent().parent().find('.section-body');
      var visible = body.css('display') != 'none';
      if (visible) {
        ele.find(".collapse-button").hide();
        ele.find(".expand-button").show();
        body.hide();
      } else {
        ele.find(".collapse-button").show();
        ele.find(".expand-button").hide();
        body.show();
      }
    });

    // handle job sort fields so no duplicates and fill from left
    $('#sort-by').on('change', 'select', function() {
      var SORT_COLUMNS = ['#sort-by-1', '#sort-by-2', '#sort-by-3'];
      var ele_id = "#" + $(this).attr('id');
      var val = $(this).val();
      var check_cols = SORT_COLUMNS.slice(0,2);

      // make sure there are no duplicates
      $.each(SORT_COLUMNS, function(index, id) {
        if (id != ele_id) {
          var current_val = $(id).val();
          if ((current_val == val) || (current_val == val.reversize())) {
            $(id).val('none');
          }
        }
      });

      // shift over any new values if there are blanks to the left
      $.each(check_cols, function(index, id) {
        var ele = $(id);
        var val = ele.val();
        var next_ele = $(SORT_COLUMNS[index+1]);
        var next_val = next_ele.val();
        if ((val == 'none') && (next_val != 'none')) {
          ele.val(next_val);
          next_ele.val('none');
        }
      });
    });

    copy_from_advanced();
	
//	if (!get_account_code(false))
//		$('#proxy-sample-jobs-count').val("0");

    // render the widget with the current job list
    update_widget();

    // ATS button styling - add arrow
    $('.ats-btn.ats-btn-arrow').each(function(index, ele) {
      // css :after doesn't work below IE8 so we're injecting here
      $(ele).append("&nbsp;<img src='//www.jobscore.com/images/icon_arrow_right_green_large.gif' class='arrow'>");
    });

  });

  function copy_from_advanced()
  {
    $.each(WIDGET_PARAMS, function(option, params) {
      var src_id = "#" + option.srcidize();
      var src_ele = $(src_id);
// TODO: make sure all params get copied to advanced, then copied to our overlay
//      var dest_id = "#" + option.destidize();
//      var dest_ele = $(dest_id);
      var param_default = params['value'] ? params['value'] : params['default'];
      // TODO: check types

      if (src_ele) {
        if (params['type'] == 'color') {
          if (param_default == 'transparent') {
            $(src_id + '-transparent').attr('checked', 'checked');
            src_ele.val('#ffffff');
          } else {
            src_ele.val(param_default);
          }
        } else {
          src_ele.val(param_default);
        }
      }
    });
  }

  function get_options()
  {
    var options = {};
    var form = $('#options');
    var val = "";
    var disabled = null;
	var show_logo = $("#proxy-show-logo").is(":checked");

    var list_type = $("#list-type").val();
	
    if (list_type == 'grouped') {
      disabled = $('#group-by').attr("disabled");
      if (disabled == 'disabled') {
		if (show_logo) {
		  $('#group-by').removeAttr("disabled");
	    }
        $('#group-by-row').show();
      }
    } else {
      disabled = $('#group-by').attr("disabled");
      if (disabled != 'disabled') {
        $('#group-by').val('none');
        $('#param-group-by').val('');
        $('#group-by').attr("disabled", "disabled");
        $('#group-by-row').hide();
      }

      if (list_type != 'auto') {
       $('#param-list-type').val(list_type);
      }
    }

    val = $("#group-by").val();
    if (val != 'none') {
      $('#param-group-by').val(val);
      $('#param-list-type').val('');
    } else {
      if (list_type == 'grouped') {
        $('#group-by').val('department');
        $('#param-group-by').val('department');
      }
    }

    // show/hide zebra stripe checkbox depending on list_type
    if (list_type != 'multi') {
      $("#proxy-zebra-stripe").attr("disabled", "disabled");
      $("#zebra-stripe-row").hide();
      $("#select-columns-row").hide();
    } else {
      $("#proxy-zebra-stripe").removeAttr("disabled");
      $("#zebra-stripe-row").show();
      $("#select-columns-row").show();
    }

    // copy proxy- elements to param-
    $.each(WIDGET_PARAMS, function(option, params) {
      var src_id = "#" + option.srcidize();
      var src_ele = $(src_id);
      var dest_id = "#" + option.destidize();
      var dest_ele = $(dest_id);
      var val = null;

      if ((src_ele.length > 0) && (dest_ele.length > 0)) {
        if (params['type'] == 'checkbox') {
          if (src_ele.attr('checked')) {
            dest_ele.attr('checked', 'checked');
          } else {
            dest_ele.removeAttr('checked');
          }
        } else if (params['type'] == 'color') {
          var transparent_id = src_id + "-transparent:checked";
          var transparent_ele = $(transparent_id);
          if (transparent_ele.length > 0) {
            dest_ele.val('transparent');
          } else {
            dest_ele.val(src_ele.val());
          }
        } else {
          val = src_ele.val();
          dest_ele.val(val);
        }
      }
    });

    // manual feature translation

    // display_fields - which columns to show
    var SHOW_COLUMNS = ['department', 'location'];
    var active_columns = ['title'];
    $.each(SHOW_COLUMNS, function(index, col) {
      var ele_id = '#column-' + col + ':checked';
      var ele = $(ele_id);
      if (ele.length > 0) {
        active_columns.push(col);
      }
    });
    if (active_columns.length > 0) {
      $('#param-display-fields').val(active_columns.join(","));
    }

    // live search dropdown/filters
    var LIVE_SEARCH_DROPDOWNS = ['department', 'location'];
    var active_columns = [];
    $.each(LIVE_SEARCH_DROPDOWNS, function(index, col) {
		
      var ele_id = '#dropdown-filter-' + col + ':checked';
      var ele = $(ele_id);
      if (ele.length > 0) {
        active_columns.push(col);
      }
    });
    if (active_columns.length > 0) {
      $('#param-filter-fields').val(active_columns.join(","));
    } else {
      $('#param-filter-fields').val('none');
    }

    // sort_by
    // TODO: enforce unique sort columns
    var SORT_COLUMNS = ['sort-by-1', 'sort-by-2', 'sort-by-3'];
    var sort_cols = [];
    $.each(SORT_COLUMNS, function(index, col) {
      var ele_id = '#' + col;
      var ele = $(ele_id);
      if (ele.length > 0) {
        if (ele.val() != 'none') {
          sort_cols.push(ele.val());
        }
      }
    });
    if (sort_cols.length > 0) {
      $('#param-sort-by').val(sort_cols.join(","));
    }

    // widget preview TODO: disable?
    var ele = $('#widget-preview-container');
    var size = $('#widget-preview-size').val();
    if (size == 'small') {
      ele.css('width', '300px');
    } else if (size == 'medium') {
      ele.css('width', '600px');
    } else {
      ele.css('width', 'auto');
    }

    // create 'options' object from param- form fields
    // we have to handle checkboxes with special care
    $.each(WIDGET_PARAMS, function(option, params) {
      var param_name = '#' + option.domidize();
      var is_checkbox;
      is_checkbox = false;

      if (params['type'] == 'checkbox') {
        param_name = param_name + ":checked";
        is_checkbox = true;
      }
	  
      var id = $(param_name);
      // :checked queries only return elements if the item is checked, if not, no element returned
      var val = is_checkbox ? (id.length > 0) : id.val();

      // null out default values here
      if (blank(val))
        val = null;

      if (val == params['default'])
        val = null;

      options[option] = val;
    });
		
    return options;
  }

  // TODO: share with widget.js
  var FALLBACK_LINK = "<div class='js-fallback' style='color:#888'></div>";

  function generate_code(skip_sample)
  {
	  args = "<div class='jobscore-tempxx' ";
	  opts = get_options();

	  for (var i in opts) {
		  if (skip_sample && (i == 'sample_jobs_count'))
		  continue;

		  if (i == 'show_logo' && opts[i] == false) {
		      opts[i] = 'remove';
		  }

		  if (opts[i] !== null) {
			  
			  if (!get_account_code(false) && (i == 'sample_jobs_count') && opts[i] == 0) {
				  opts[i] = 5;
			  }
			  
			  args = args + "data-" + i.dasherize() + "='" + opts[i] + "' ";
		  }
	  }
	  args = args + " data-wp-plugin='true'>\n" + FALLBACK_LINK + "\n</div><!-- /jobscore-jobs -->";

	  return args;
  }

  $js.register("getOptions", get_options);
  
  function update_widget()
  {				  	
	  init_farbtastic_elements();
	  
	  var code = 'Please create your JobScore Account and enter your account name to see a preview.';
	  
	  if (get_account_code()) {
		  code = generate_code().replace('tempxx', 'jobs');
	  }
	  	  
	  $('#widget').html(code);
  }
  
}(window.jQuery);
