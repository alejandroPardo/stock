$(document).ready(function(){
	
	///// SHOW LOADING IMAGE ON AJAX ACTIVITY ////////////////
	$('.ajax')
	    .hide()  // hide it initially
	    .ajaxStart(function() {
	        $(this).show();
	    })
	    .ajaxStop(function() {
	        $(this).delay(350).fadeOut();
	    });
	
	/* LIST */
	getlist();
	
	$('#addnew').click(function(){
		
		$.ajax({
			type : 'GET',
			url : 'functions.php?mode=add',
			success : function (html) {
				$('#form').html(html);

			}
		});
		
	});
	
	$('#getproduct').live('submit',function(){
		
		var pname = $('#name').val();
		
		var formData = 'name=' + pname 
		
		$.ajax({
			type : 'POST',
			url : 'functions.php?mode=prod',
			data : formData,
			success : function (html) {
				$('#response').html(html);
				getlist();
			}
		});
		
		return false;
	});
	
	$('#editprod').live('submit',function(){
		
		var pname = $('#name').val();
		
		var formData = 'name=' + pname 
		
		$.ajax({
			type : 'POST',
			url : 'functions.php?mode=editprod',
			data : formData,
			success : function (html) {
				$('#response').html(html);
				getlist();
			}
		});
		
		return false;
	});
	
	$('#edituser').live('submit',function(){
		
		var pname = $('#name').val();
		
		var formData = 'name=' + pname 
		
		$.ajax({
			type : 'POST',
			url : 'functions.php?mode=edituser',
			data : formData,
			success : function (html) {
				$('#response').html(html);
				getlist();
			}
		});
		
		return false;
	});
	
	/* submit */
	
	$('#addproduct').live('submit',function(){
		
		var pname = $('#name').val();
		var pprice = $('#price').val();
		
		var formData = 'name=' + pname + '&price='+ pprice; 
		
		$.ajax({
			type : 'POST',
			url : 'functions.php?mode=save',
			data : formData,
			success : function (html) {
				$('#response').html(html);
				getlist();
			}
		});
		
		return false;
	});
	
	
	$('#editproduct').live('submit',function(){
		
		var pname = $('#name').val();
		var pprice = $('#price').val();
		var pid = $('#id').val();
		
		var formData = 'name=' + pname + '&price='+ pprice + '&id=' + pid; 
		
		$.ajax({
			type : 'POST',
			url : 'functions.php?mode=editsave',
			data : formData,
			success : function (html) {
				$('#response').html(html);
				getlist();
			}
		});
		
		return false;
	});
	
	$('.delete').live('click',function() {
		
		var pid = $(this).attr('ref');
		var name = $(this).attr('name');
		
		$c = confirm('Esta seguro de que desea eliminar la entrada de '+name+' ?');
		
		if ($c) {
			
			var formData = 'id=' + pid; 
			
			$.ajax({
				type : 'POST',
				url : 'functions.php?mode=delete',
				data : formData,
				success : function (html) {
					$('#response').html(html);
					getlist();
				}
			});
			
		} // confirmed
	
	});
	
	$('.edit').live('click',function() {
		
		var pid = $(this).attr('ref');
		
		$.ajax({
			type : 'GET',
			url : 'functions.php?mode=edit&id='+pid,
			success : function (html) {
				$('#form').html(html);
			}
		});
	
	});
	
	function getlist() {
		$.ajax({
			type : 'GET',
			url : 'functions.php?mode=list',
			success : function (html) {
				$('#items').html(html);
				$("tr:nth-child(odd)").addClass("alt");
			}
		});
	}

});

