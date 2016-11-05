<!-- INCLUDE header.tpl -->
<p>« <a href="./?task=subscribers">${STR_BACK}</a></p>
<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info">
${INFO_ALERT}
</div>
<!-- END IF -->

<form class="form-horizontal" action="${PHP_SELF}" target=_blank method="post">
  <div class="control-group">
    <label class="control-label" for="export_type">${STR_EXPORT}:</label>
    <div class="controls">
      <label class="radio inline">
        <input type="radio" value="1" checked name="export_type">
        ${STR_EXPORT_TEXT} </label>
      <label class="radio inline">
        <input type="radio" value="2" name="export_type">
        ${STR_EXPORT_EXCEL} </label>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="zip">${STR_COMPRESSION}:</label>
    <div class="controls">
      <label class="radio inline">
        <input type="radio" checked value="1" name="zip">
        ${STR_COMPRESSION_OPTION_1} </label>
      <label class="radio inline">
        <input type="radio" value="2" name="zip">
        ${STR_COMPRESSION_OPTION_2} </label>
    </div>
  </div>
  <div class="controls">
    <input type="submit" class="btn btn-success" name="action" value="${BUTTON_APPLY}">
  </div>
</form>
<!-- INCLUDE footer.tpl -->