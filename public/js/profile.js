$(document).ready(function() {
	$('#avatar-edit').click(function(e) {
		$('.avatar-input').show();
		e.preventDefault();
		return false;
	});

	$('#profile_avatar_file').change(function() {
		$('#profile_avatar_name').remove();
		$('#profile_avatar_extension').remove();
	});
});