<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="./styles/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="./styles/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<link href="./styles/styles.css" rel="stylesheet" media="screen">
<link href="./styles/DT_bootstrap.css" rel="stylesheet" media="screen">
<link type="text/css" href="./styles/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<title>PHP Newsletter | ${TITLE_PAGE}</title>
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/jquery.hide_alertblock.js"></script>
</head>
<body>
<script type="text/javascript">
	$(document).ready(function(){  
		$.ajax({
			type: "GET",
			url: "./?task=alert_update",
			dataType: "xml",
			success: xmlParser
		});
	});

	function xmlParser(xml) {
		$(xml).find("DOCUMENT").each(function () {
		$('.alert-block').fadeIn('700');
			$("#alert_warning_msg").append($(this).find("warning").text());
		});
	}

</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span3" id="sidebar">
	<span class="logo"></span>
	<span class="version">${SCRIPT_VERSION}</span>
  <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
    <li<!-- IF '${ACTIVE_MENU}' == '' --> class="active"<!-- END IF -->><a href="./" title="${MENU_TEMPLATES_TITLE}"><span class="icon-menu icon-envelope"></span>${MENU_TEMPLATES}</a></li>
    <li<!-- IF '${ACTIVE_MENU}' == 'create_template' --> class="active"<!-- END IF -->><a href="./?task=create_template" title="${MENU_CREATE_NEW_TEMPLATE_TITLE}"><span class="icon-menu icon-plus"></span>${MENU_CREATE_NEW_TEMPLATE}</a><span class="menu-create-tmpl-icon"></span></li>
    <li<!-- IF '${ACTIVE_MENU}' == 'subscribers' --> class="active"<!-- END IF -->><a href="./?task=subscribers" title="${MENU_SUBSCRIBERS_TITLE}"><span class="icon-menu icon-user"></span>${MENU_SUBSCRIBERS}</a></li>
    <li<!-- IF '${ACTIVE_MENU}' == 'category' --> class="active"<!-- END IF -->><a href="./?task=category" title="${MENU_CATEGORY_TITLE}"><span class="icon-menu icon-folder-open"></span>${MENU_CATEGORY}</a></li>
    <li<!-- IF '${ACTIVE_MENU}' == 'log' --> class="active"<!-- END IF -->><a href="./?task=log" title="${MENU_LOG_TITLE}"><span class="icon-menu icon-book"></span>${MENU_LOG}</a></li>
    <li<!-- IF '${ACTIVE_MENU}' == 'settings' --> class="active"<!-- END IF -->><a href="./?task=settings" title="${MENU_SETTINGS_TITLE}"><span class="icon-menu icon-wrench"></span>${MENU_SETTINGS}</a></li>
    <li<!-- IF '${ACTIVE_MENU}' == 'security' --> class="active"<!-- END IF -->><a href="./?task=security" title="${MENU_SECURITY_TITLE}"><span class="icon-menu icon-exclamation-sign"></span>${MENU_SECURITY}</a></li>
    <li<!-- IF '${ACTIVE_MENU}' == 'update' --> class="active"<!-- END IF -->><a href="./?task=update" title="${MENU_UPDATE_TITLE}"><span class="icon-menu icon-refresh"></span>${MENU_UPDATE}</a></li>
	<li<!-- IF '${ACTIVE_MENU}' == 'faq' --> class="active"<!-- END IF -->><a href="./?task=faq" title="FAQ"><span class="icon-menu icon-question-sign"></span>FAQ</a></li>
  </ul>
</div>
<div class="span9" id="content">

<div class="row-fluid">

<!-- BEGIN page-alert-error -->
<div class="alert alert-error alert-block">
<a class="close" href="#" data-dismiss="alert">×</a>
<h4 class="alert-heading">${STR_ERROR}!</h4>
${PAGE_ALERT_ERROR_MSG}
</div>
<!-- END page-alert-error -->

<div class="alert alert-block" style="display:none">
<a class="close" href="#" data-dismiss="alert">×</a>
<h4 class="alert-heading">${STR_WARNING}!</h4>
<span id="alert_warning_msg">${PAGE_ALERT_WARNING_MSG}</span>
</div>

</div>

<div class="row-fluid">
<!-- block -->
<div class="block">
<div class="navbar navbar-inner block-header">
  <div class="muted pull-left"><strong>${TITLE}</strong></div>
</div>
<div class="block-content collapse in">
<div class="span12">