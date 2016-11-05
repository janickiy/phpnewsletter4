<!-- INCLUDE header.tpl -->
<script type="text/javascript" src="./js/jquery.hide_alertblock.js"></script>
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">
${INFO_ALERT}
</div>
<!-- END IF -->
<!-- BEGIN LogList -->
<p><a href="./?task=log&clear_log">${STR_CLEAR_LOG}</a></p>
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">
${INFO_ALERT}
</div>
<!-- END IF -->

<!-- IF '${ERROR_ALERT}' != '' -->
<div class="alert alert-error">
<button class="close" data-dismiss="alert">×</button>
<strong>${STR_ERROR}!</strong>
${ERROR_ALERT}
</div>
<!-- END IF -->

<!-- IF '${MSG_ALERT}' != '' -->
<div class="alert alert-success">
<button class="close" data-dismiss="alert">×</button>
${MSG_ALERT}
</div>
<!-- END IF -->



<table class="table-hover table table-bordered" border="0" cellspacing="0" cellpadding="0" width="100%">
 <thead>
  <tr>
    <th>${TH_TABLE_TIME}</th>
	<th>${TH_TABLE_TOTAL}</th>
	<th>${TH_TABLE_SENT}</th>
    <th>${TH_TABLE_NOSENT}</th>
    <th>${TH_TABLE_READ}</th>
    <th>${TH_TABLE_DOWNLOAD_REPORT}</th>
  </tr>
    </thead>
	<tbody> 
  <!-- BEGIN row -->
  <tr>
    <td>${TIME}</td>
    <td><a href="./?task=log&id_log=${ID_LOG}">${TOTAL}</a></td>
	<td>${TOTAL_SENT}</td>
	<td>${TOTAL_NOSENT}</td>
    <td>${TOTAL_READ}</td>
    <td><span class="IconExcel"></span><a title="${STR_DOWNLOADSTAT}" href="./?task=logstatxls&id_log=${ID_LOG}">${STR_DOWNLOAD}</a></td>
  </tr>
  <!-- END row -->
  </tbody> 
</table> 
  
	<!-- BEGIN pagination -->

        <div class="pagination">
        <ul>
		<!-- IF '${PERVPAGE}' != '' --><li>${PERVPAGE}</li><!-- END IF -->
		<!-- IF '${PERV}' != '' --><li>${PERV}</li><!-- END IF -->
		<!-- IF '${PAGE2LEFT}' != '' --><li>${PAGE2LEFT}</li><!-- END IF -->
		<!-- IF '${PAGE1LEFT}' != '' --><li>${PAGE1LEFT}</li><!-- END IF -->
		<!-- IF '${CURRENT_PAGE}' != '' --><li class="prev disabled">${CURRENT_PAGE}</li><!-- END IF -->
		<!-- IF '${PAGE1RIGHT}' != '' --><li>${PAGE1RIGHT}</li><!-- END IF -->
		<!-- IF '${PAGE2RIGHT}' != '' --><li>${PAGE2RIGHT}</li><!-- END IF -->
		<!-- IF '${NEXTPAGE}' != '' --><li>${NEXTPAGE}</li><!-- END IF -->
		<!-- IF '${NEXT}' != '' --><li>${NEXT}</li><!-- END IF -->	
		</ul>
		</div>
		
	<!-- END pagination -->			
  
<!-- END LogList -->
<!-- BEGIN DetailLog -->
<p>« <a href="./?task=log">${STR_BACK}</a></p>
<table class="table-hover table table-bordered" border="0" cellspacing="0" cellpadding="0" width="100%">
  <thead>
  <tr>
    <th class="${THCLASS_NAME}"><a href="./?task=log&name=${GET_NAME}&id_log=${ID_LOG}">${TH_TABLE_MAILER}</a></th>
    <th class="${THCLASS_EMAIL}"><a href="./?task=log&email=${GET_EMAIL}&id_log=${ID_LOG}">E-mail</a></th>
    <th class="${THCLASS_CATNAME}"><a href="./?task=log&catname=${GET_CATNAME}&id_log=${ID_LOG}">${TH_TABLE_CATNAME}</a></th>
    <th class="${THCLASS_TIME}"><a href="./?task=log&time=${GET_TIME}&id_log=${ID_LOG}">${TH_TABLE_TIME}</a></th>
    <th class="${THCLASS_SUCCESS}"><a href="./?task=log&success=${GET_SUCCESS}&id_log=${ID_LOG}">${TH_TABLE_STATUS}</a></th>
    <th class="${THCLASS_READMAIL}"><a href="./?task=log&readmail=${GET_READMAIL}&id_log=${ID_LOG}">${TH_TABLE_READ}</a></th>
    <th>${TH_TABLE_ERROR}</th>
  </tr>
  </thead>
  <tbody> 
  <!-- BEGIN row -->
  <tr>
    <td>${NAME}</td>
    <td>${EMAIL}</td>
    <td>${CATNAME}</td>
    <td>${TIME}</td>
    <td>${STATUS}</td>
    <td>${READ}</td>
    <td width="30%">${ERRORMSG}</td>
  </tr>
  <!-- END row -->
  </tbody> 
</table>
<!-- END DetailLog -->
<!-- INCLUDE footer.tpl -->