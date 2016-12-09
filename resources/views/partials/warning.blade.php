<?php $error = Session::get('errorMessage'); ?>
@if($error)
<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  	{{ $error }}
</div>
@endif
<?php $success = Session::get('successMessage'); ?>
@if($success)
<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  	{{ $success }}
</div>
@endif
