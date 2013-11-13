$(document).ready(function(){

	/* LIST */
	getlist();
	
	
	$('#editar').live('submit',function(){
		
		var pname = $('#name').val();
		var pid = $('#id').val();
		var pdisp = $('#disp').val();
		
		var formData = 'name=' + pname + '&id=' + pid + '&disp=' + pdisp; 
		
		$.ajax({
			type : 'POST',
			url : 'functionsusers.php?mode=saveUnit',
			data : formData,
			success : function (html) {
				$('#response').html(html);
				getlist();
			}
		});
		
		return false;
	});
	
	
	$('.edit').live('click',function() {
		
		var pid = $(this).attr('ref');
		var psede = $(this).attr('sede');
		$.ajax({
			type : 'GET',
			url : 'functionsusers.php?mode=editUnit&id='+pid+'&sede='+psede,
			success : function (html) {
				$('#form').html(html);
			}
		});
	
	});

	
	function getlist() {
		$.ajax({
			type : 'GET',
			url : 'functionsusers.php?mode=listUnit',
			success : function (html) {
				$('#items').html(html);
				$("tr:nth-child(odd)").addClass("alt");
			}
		});
	}
	
	$('.check').live('click',function() {
		
		var pid = $(this).attr('ref');
		var pname = $(this).attr('nam');
		var fecha = $(this).attr('dat');
		var units = $(this).attr('uni');
		$c = confirm('Confirma la entrada de '+units+' unidad(es) del producto '+pname+' de la fecha '+fecha+'?');
		
		if ($c) {
			
			var formData = 'id=' + pid+'&name='+pname+'&date='+fecha; 
			
			$.ajax({
				type : 'POST',
				url : 'functionsusers.php?mode=checkUnit',
				data : formData,
				success : function (html) {
					$('#response').html(html);
					getlist();
				}
			});
			
		} // confirmed
	
	});

});
