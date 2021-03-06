<p>« <a href="./?task=subscribers">${RETURN_BACK}</a></p>
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

<!-- BEGIN show_errors -->
<div class="alert alert-error">
<a class="close" href="#" data-dismiss="alert">×</a>
<h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4>
<ul>
    <!-- BEGIN row -->
   <li> ${ERROR}</li>
    <!-- END row -->
</ul>
</div>
<!-- END show_errors -->

<form class="form-horizontal" action="${PHP_SELF}" method="post">
  <!-- IF '${ID_USER}' != '' -->
  <input type="hidden" name="id_user" value="${ID_USER}">
  <!-- END IF -->
  <div class="control-group">
    <label class="control-label" for="name">${FORM_NAME}:</label>
    <div class="controls">
      <input class="span3 input-xlarge focused" type="text" name="name" value="${NAME}">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="email">${FORM_EMAIL}:</label>
    <div class="controls">
      <input class="span3 input-xlarge focused" type="text" name="email" value="${EMAIL}">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="id_cat[]">${FORM_CATEGORY}:</label>
    <!-- BEGIN row -->
    <label class="checkbox">
    <div class="controls"> <input type="checkbox" value="${ID_CAT}" name="id_cat[]" 
      <!-- IF '${CHECKED}' != '' -->
      checked="checked"
      <!-- END IF -->
      >${CATEGORY_NAME} </div>
    </label>
    <!-- END row -->
  </div>
  <div class="controls">
    <input type="submit" name="action" class="btn btn-success" value="${BUTTON}">
  </div>
</form>