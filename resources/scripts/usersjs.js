$(document).ready(function(){

	/* LIST */
	getlist();
	
	$('#getproduct').live('submit',function(){
		
		var pname = $('#name').val();
		var psede = $('#sede').val();
		
		var formData = 'name=' + pname + '&sede=' + psede
		
		$.ajax({
			type : 'POST',
			url : 'functionsusers.php?mode=prod',
			data : formData,
			success : function (html) {
				$('#response').html(html);
				getlist();
			}
		});
		
		return false;
	});
	
	$('#getdate').live('submit',function(){
		
		var pdate = $('#date').val();
		var psede = $('#sede').val();
		
		var formData = 'date=' + pdate + '&sede=' + psede
		
		$.ajax({
			type : 'POST',
			url : 'functionsusers.php?mode=date',
			data : formData,
			success : function (html) {
				$('#response').html(html);
				getlist();
			}
		});
		
		return false;
	});
	
	/* submit */
	
	
	$('#editar').live('submit',function(){
		
		var pname = $('#name').val();
		var pid = $('#id').val();
		var pdisp = $('#disp').val();
		var pent = $('#ent').val();
		var ppeso = $('#realpeso').val();
		var pmerma = $('#merma').val();
		
		var formData = 'name=' + pname + '&id=' + pid + '&ent=' + pent + '&disp=' + pdisp + '&peso=' + ppeso + '&merma=' + pmerma; 
		
		$.ajax({
			type : 'POST',
			url : 'functionsusers.php?mode=editsave',
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
			url : 'functionsusers.php?mode=edit&id='+pid+'&sede='+psede,
			success : function (html) {
				$('#form').html(html);
			}
		});
	
	});

	
	function getlist() {
		$.ajax({
			type : 'GET',
			url : 'functionsusers.php?mode=list',
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
		
		$c = confirm('Confirma la entrada del producto '+pname+' de la fecha '+fecha+'?');
		
		if ($c) {
			
			var formData = 'id=' + pid+'&name='+pname+'&date='+fecha; 
			
			$.ajax({
				type : 'POST',
				url : 'functionsusers.php?mode=check',
				data : formData,
				success : function (html) {
					$('#response').html(html);
					getlist();
				}
			});
			
		} // confirmed
	
	});

});
