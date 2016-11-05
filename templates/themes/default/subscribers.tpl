<!-- INCLUDE header.tpl -->
<script type="text/javascript">

	var DOM = (typeof(document.getElementById) != 'undefined');

	function Check_action()
	{
		if(document.forms[1].action.value == 0) { window.alert('${ALERT_CONFIRM_REMOVE}'); }
	}

	function CheckAll_Activate(Element,Name)
	{
		if(DOM){
			thisCheckBoxes = Element.parentNode.parentNode.parentNode.parentNode.parentNode.getElementsByTagName('input');

			var m = 0;

			for(var i = 1; i < thisCheckBoxes.length; i++){
				if(thisCheckBoxes[i].name == Name){
					thisCheckBoxes[i].checked = Element.checked;
					if(thisCheckBoxes[i].checked == true) m++;
					if(thisCheckBoxes[i].checked == false) m--;
				}
			}

			if(m > 0) { document.getElementById("Apply_").disabled = false; }
			else { document.getElementById("Apply_").disabled = true;  }
		}
	}

	function Count_checked()
	{
		var All = document.forms[1];
		var m = 0;

		for(var i = 0; i < All.elements.length; ++i){
			if(All.elements[i].checked) { m++; }
		}

		if(m > 0) { document.getElementById("Apply_").disabled = false; }
		else { document.getElementById("Apply_").disabled = true; }
	}

</script>
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">
${INFO_ALERT}
</div>
<!-- END IF -->

<ul class="BtnPanelIcon">
  <li><a title="${PROMPT_ADD_USER}" href="./?task=add_user"> <span class="IconAddUser"></span> <span class="IconText">${STR_ADD_USER}</span> </a> </li>
  <li><a title="${PROMPT_REMOVE_SUBSCRIBERS}" onclick="return confirm('${ALERT_CLEAR_ALL}');" href="./?task=subscribers&remove=all"> <span class="IconRemove"></span> <span class="IconText">${STR_REMOVE_ALL_SUBSCRIBERS}</span> </a> </li>
  <li><a title="${PROMPT_IMPORT_SUBSCRIBERS}" href="./?task=import"> <span class="IconImport"></span> <span class="IconText">${STR_IMPORT_USER}</span> </a> </li>
  <li><a title="${PROMPT_EXPORT_SUBSCRIBERS}" href="./?task=export"> <span class="IconExport"></span> <span class="IconText">${STR_EXPORT_USER}</span> </a> </li>
</ul>
<form class="form-inline" id="searchform" method="GET" name="searchform" action="${PHP_SELF}">
  <div class="control-group">
    <input type="hidden" name="task" value="subscribers">
    <input type="text" onfocus="if (this.value == '${FORM_SEARCH_NAME}')
        {this.value = '';}" onblur="if (this.value == '')
        {this.value = '${FORM_SEARCH_NAME}';}" id="story" name="search" value="${FORM_SEARCH_NAME}">
    <input class="btn btn-info" type="submit" value="${BUTTON_FIND}" id="searchsubmit">
  </div>
</form>
<!-- BEGIN show_return_back -->
<p>« <a href="./?task=subscribers">${STR_BACK}</a></p>
<!-- END show_return_back -->
<!-- IF '${ERROR_ALERT}' != '' -->
<div class="alert alert-error">
  <button class="close" data-dismiss="alert">×</button>
  <strong>${STR_ERROR}!</strong> ${ERROR_ALERT} </div>
<!-- END IF -->
<!-- IF '${MSG_ALERT}' != '' -->
<div class="alert alert-success">
  <button class="close" data-dismiss="alert">×</button>
  ${MSG_ALERT} </div>
<!-- END IF -->
<!-- BEGIN row -->
<form class="form-horizontal" action="${PHP_SELF}" onSubmit="if(document.forms[1].action.value == 0){window.alert('${ALERT_SELECT_ACTION}');return false;}if(document.forms[1].action.value == 2){return confirm('${ALERT_CONFIRM_REMOVE}');}" method="post">
  <table class="table table-striped table-bordered table-hover dataTable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <thead>
      <tr>
        <th style="text-align: center;"><input type="checkbox" title="${STR_CHECK_ALLBOX}" onclick="CheckAll_Activate(this,'activate[]');"></th>
        <th class="${TH_CLASS_NAME}"><a href="./?task=subscribers&name=${GET_NAME}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->${PAGENAV}">${TABLE_NAME}</a></th>
        <th class="${TH_CLASS_EMAIL}"><a href="./?task=subscribers&email=${GET_EMAIL}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_EMAIL}</a></th>
        <th class="${TH_CLASS_TIME}"><a href="./?task=subscribers&time=${GET_TIME}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_ADDED}</a></th>
        <th width="250">IP</th>
        <th class="${TH_CLASS_STATUS}"><a href="./?task=subscribers&status=${GET_STATUS}${PAGENAV}<!-- IF '${SEARCH}' != '' -->&search=${SEARCH}<!-- END IF -->">${TABLE_STATUS}</a></th>
        <th width="300">${TABLE_ACTION}</th>
      </tr>
    </thead>
    <tbody>
      <!-- BEGIN column -->
      <tr class="td-middle${STATUS_CLASS}">
        <td><input type="checkbox" onclick="Count_checked();" title="${STR_CHECK_BOX}" value="${ID_USER}" name="activate[]"></td>
        <td style="text-align: left;">${NAME}</td>
        <td style="text-align: left;">${EMAIL}</td>
        <td>${PUTDATE_FORMAT}</td>
        <td><a title="${PROMPT_IP_INFO}" href="./?task=whois&ip=${IP}">${IP}</a></td>
        <td>${STR_STAT}</td>
        <td><a class="btn" href="./?task=edit_user&id_user=${ID_USER}" title="${STR_EDIT}"> <i class="icon-pencil"></i>${STR_EDIT}</a> <a class="btn" href="./?task=subscribers&remove=${ID_USER}" title="${STR_REMOVE}"> <i class="icon-trash"></i> ${STR_REMOVE} </a>
      </tr>
      <!-- END column -->
    </tbody>
  </table>
  <div class="form-inline">
    <div class="control-group">
      <select class="span3 form-control" name="action">
        <option value="0">--${FORM_CHOOSE_ACTION}--</option>
        <option value="1">${FORM_ACTIVATE}</option>
        <option value="2">${FORM_REMOVE}</option>
      </select>
      <span class="help-inline">
      <input type="submit" class="btn btn-success" id="Apply_" value="${BUTTON_APPLY}" disabled="" name="">
      </span> </div>
  </div>
</form>
<!-- BEGIN pagination -->
<div class="pagination">
  <ul>
    <!-- IF '${PERVPAGE}' != '' -->
    <li>${PERVPAGE}</li>
    <!-- END IF -->
    <!-- IF '${PERV}' != '' -->
    <li>${PERV}</li>
    <!-- END IF -->
    <!-- IF '${PAGE2LEFT}' != '' -->
    <li>${PAGE2LEFT}</li>
    <!-- END IF -->
    <!-- IF '${PAGE1LEFT}' != '' -->
    <li>${PAGE1LEFT}</li>
    <!-- END IF -->
    <!-- IF '${CURRENT_PAGE}' != '' -->
    <li class="prev disabled">${CURRENT_PAGE}</li>
    <!-- END IF -->
    <!-- IF '${PAGE1RIGHT}' != '' -->
    <li>${PAGE1RIGHT}</li>
    <!-- END IF -->
    <!-- IF '${PAGE2RIGHT}' != '' -->
    <li>${PAGE2RIGHT}</li>
    <!-- END IF -->
    <!-- IF '${NEXTPAGE}' != '' -->
    <li>${NEXTPAGE}</li>
    <!-- END IF -->
    <!-- IF '${NEXT}' != '' -->
    <li>${NEXT}</li>
    <!-- END IF -->
  </ul>
</div>
<!-- END pagination -->
<p>${STR_NUMBER_OF_SUBSCRIBERS}: ${NUMBER_OF_SUBSCRIBERS}</p>
<!-- END row -->
<!-- IF '${EMPTY_LIST}' != '' -->
<div style="text-align: center;" class="warning_msg">${EMPTY_LIST}</div>
<!-- END IF -->
<!-- BEGIN notfound -->
<div class="alert">
  <button class="close" data-dismiss="alert">×</button>
  ${MSG_NOTFOUND} </div>
<!-- END notfound -->
<!-- INCLUDE footer.tpl -->