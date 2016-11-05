<!-- INCLUDE header.tpl -->
<form class="form-horizontal" action="${PHP_SELF}" method="post">
  <input type="hidden" name="action" value="post">
  <div class="control-group">
    <label for="license_key" class="control-label">${STR_LICENSE_KEY}:</label>
    <div class="controls">
      <input class="form-control uneditable-input" disabled="disabled" type="text" name="license_key" value="${LICENSE_KEY}">
    </div>
  </div>
  <div class="controls">
    <input type="submit" class="btn btn-success" disabled="disabled" value="${BUTTON_SAVE}">
  </div>
</form>
<!-- INCLUDE footer.tpl -->