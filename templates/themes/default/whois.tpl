<!-- INCLUDE header.tpl -->
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">
${INFO_ALERT}
</div>
<!-- END IF -->
<p>« <a href="./?task=subscribers">${RETURN_BACK}</a></p>
<!-- BEGIN whois -->
<table class="table-hover table table-bordered" border="0" cellspacing="0" cellpadding="0" width="100%">
  <thead>
    <tr>
      <th class="catmenu menu">${TH_TABLE_IP_INFO}</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN row -->
    <tr class="trcat">
      <td>${SOCK}<br></td>
    </tr>
    <!-- END row -->
  <tbody>
</table>
<!-- IF '${STR_ERROR}' != '' -->
<span class="error">${STR_ERROR}</span>
<!-- END IF -->
<!-- END whois -->
<!-- INCLUDE footer.tpl -->