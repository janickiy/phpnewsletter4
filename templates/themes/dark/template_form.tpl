<script type="text/javascript" src="./js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">

function add_attach_file(bl_name, num)
{
	var addF = document.forms['addF'];
	prev_num = parseInt(num)-1;
	bl_name += "_";
	par_div = document.getElementById(bl_name+prev_num).parentNode;
	adding_block = document.createElement("div");
	adding_block.id = bl_name+num;

	if(bl_name == "loadfile_") adding_block.innerHTML = "<div id=loadfile_"+(parseInt(num))+"><div id=\"addf_table_"+(parseInt(num))+"\"><div id=\"Div_File_"+(parseInt(num))+"\"><input type=\"file\" onChange=\"add_attach_file('loadfile', '"+((parseInt(num))+1)+"'); return false;\" class=\"span8\" id=\"file_"+(parseInt(num))+"\" name=\"attachfile[]\"></div>&nbsp;&nbsp;<a onclick=\"del_pole(" + parseInt(num) + ");\" href=\"#\">${STR_REMOVE}</a></div></div></div>";

	par_div.appendChild(adding_block);
}

function del_pole(btn)
{
	document.getElementById ('addf_table_' + btn).parentNode.removeChild (document.getElementById ('addf_table_' + btn));
}

</script>
<!-- IF '${INFO_ALERT}' != '' -->

<div class="alert alert-info"><span class="icon icon-exclamation-sign"></span> ${INFO_ALERT} </div>
<!-- END IF -->
<!-- IF '${ERROR_ALERT}' != '' -->
<div class="alert alert-danger">
  <button class="close" data-dismiss="alert">×</button>
  <span class="icon icon-remove-sign"></span>
  <strong>${STR_ERROR}!</strong> ${ERROR_ALERT} </div>
<!-- END IF -->
<!-- BEGIN show_errors -->
<div class="alert alert-danger"> <a class="close" href="#" data-dismiss="alert">×</a>
<span class="icon icon-remove-sign"></span>
  <h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4>
  <ul>
    <!-- BEGIN row -->
    <li> ${ERROR}</li>
    <!-- END row -->
  </ul>
</div>
<!-- END show_errors -->
<script type="text/javascript">//<![CDATA[
CKEDITOR.config.skin='moono-dark';
window.CKEDITOR_BASEPATH='./js/ckeditor/';
CKEDITOR.lang.languages={"${LANGUAGE}":1};
//]]></script>
<form id="tmplForm" enctype="multipart/form-data" action="${ACTION}" method="post">
  <!-- IF '${ID_TEMPLATE}' != '' -->
  <input type="hidden" name="id_template" value="${ID_TEMPLATE}">
  <!-- END IF -->
  <div class="form-group">
    <label class="control-label" for="name">${STR_FORM_SUBJECT}:</label>
    <input id="tmplName" name="name" type="text" value="${NAME}" class="form-control" />
  </div>
  <div class="form-group">
    <label>${STR_FORM_CONTENT}:</label>
	 <textarea class="form-control form-dark" rows="5" id="tmplBody" name="body">${CONTENT}</textarea>
    <script type="text/javascript">//<![CDATA[
CKEDITOR.replace('tmplBody');
//]]></script>
    <p class="help-block">${STR_FORM_NOTE}: ${STR_SUPPORTED_TAGS_LIST}</p>
  </div>
  <!-- BEGIN attach_list -->
  <div class="form-group">
    <label class="control-label" for="attach_list">${STR_ATTACH_LIST}:</label>
    <div class="controls inline">
      <!-- BEGIN row -->
      ${ATTACHMENT_FILE} <a href="./?task=edit_template&id_template=${ID_TEMPLATE}&remove=${ID_ATTACHMENT}" title="${STR_REMOVE}"> X </a>&nbsp;&nbsp;
      <!-- END row -->
    </div>
  </div>
  <!-- END attach_list -->
  <div class="form-group">
    <label for="attachfile[]" class="control-label">${STR_FORM_ATTACH_FILE}:</label>
    <div class="controls">
      <div id="loadfile_0">
        <input type="file" name="attachfile[]" class="input" id="file_0_input" onChange="add_attach_file('loadfile', '1'); return false;">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="id_cat">${STR_FORM_CATEGORY_SUBSCRIBERS}</label>
    <select for="id_cat" class="form-control form-primary">
        ${OPTION}
    </select>
  </div>
  <div class="form-group">
    <label for="exampleInputFile">${FORM_PRIORITY}:</label>
    <div class="controls">
      <label> <input type="radio" name="prior" value="3" 
        <!-- IF '${PRIOR3_CHECKED}' != '' -->checked="checked"<!-- END IF -->> ${STR_FORM_PRIORITY_NORMAL} </label>
      <label> <input type="radio" name="prior" value="2" <!-- IF '${PRIOR2_CHECKED}' != '' -->checked="checked"<!-- END IF -->> ${STR_FORM_PRIORITY_LOW} </label>
      <label> <input type="radio" name="prior" value="1" <!-- IF '${PRIOR1_CHECKED}' != '' -->checked="checked"<!-- END IF -->> ${STR_FORM_PRIORITY_HIGH} </label>
    </div>
  </div>
  <div class="form-group">
    <div class="controls">
      <input type="submit" class="btn btn-success" name="action" value="${BUTTON}">
    </div>
  </div>
  <h4>${STR_SEND_TEST_EMAIL}:</h4>
  <div class="input-group" style="margin-bottom:20px;">
    <input type="text" value="" id="tmplEmail" name="email" class="span3 form-control" />
    <span class="input-group-btn">
    <input type="button" id="send_test_email" class="btn btn-info" value="${BUTTON_SEND}" />
    </span> </div>
  <div id="resultSend"></div>
</form>
<script type="text/javascript">
$(document).ready(function(){ 
	$('#send_test_email').click(function(){
		var content = CKEDITOR.instances["tmplBody"].getData();		
		
		$("#div1").text($("#tmplForm").serialize());
		var arr = $("#tmplForm").serializeArray();
		var aParams = new Array();
	  
		for (var i=0, count=arr.length; i<count; i++) {
			var sParam = encodeURIComponent(arr[i].name);
		
			if(sParam == 'body'){
				sParam += "=";
				sParam += encodeURIComponent(content);		
			}
			else{		
				sParam += "=";
				sParam += encodeURIComponent(arr[i].value);
			}
		
			aParams.push(sParam);
		}

		var sendData = aParams.join("&");	

		$.ajax({
			type: "POST",
			url: "./?task=sendtest",
			data: sendData,
			dataType: "xml",
			success: function(xml){
				$(xml).find("document").each(function () {
					var result = $(this).find("result").text();	
					var msg = $(this).find("msg").text();
					var alert_msg = '';

					if(result == 'success'){
						alert_msg += '<div class="alert alert-success">';
						alert_msg += '<span class="icon icon-exclamation-sign"></span>';
						alert_msg += '<button class="close" data-dismiss="alert">×</button>';
						alert_msg += msg;
						alert_msg += '</div>';					
					}
					else if(result == 'error'){
						alert_msg += '<div class="alert alert-danger">';
						alert_msg += '<span class="icon icon-remove-sign"></span>';
						alert_msg += '<button class="close" data-dismiss="alert">×</button>';
						alert_msg += '<strong>${STR_ERROR}!</strong>';
						alert_msg += msg;
						alert_msg += '</div>';											
					}
					else if(result == 'errors'){
						alert_msg += '<div class="alert alert-danger">';
						alert_msg += '<span class="icon icon-remove-sign"></span>';
						alert_msg += '<a class="close" href="#" data-dismiss="alert">×</a>';					
						alert_msg += '<strong><h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4></strong>';
						alert_msg += '<ul>';
						
						var arr = msg.split(',');
						
						for (var i = 0; i < arr.length; i++){
							alert_msg += '<li> ' + arr[i] + '</li>';
						}
				
						alert_msg += '</ul>';
						alert_msg += '</div>';
					}				
					
					$("#resultSend").html(alert_msg); 
				});
			}
		});  
	});	
});

</script>