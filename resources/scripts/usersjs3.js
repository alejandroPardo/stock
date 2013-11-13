$(document).ready(function(){

	/* LIST */
	getlist();
	
	
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
			url : 'functionsusers.php?mode=saveDaily',
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
			url : 'functionsusers.php?mode=editDaily&id='+pid+'&sede='+psede,
			success : function (html) {
				$('#form').html(html);
			}
		});
	
	});

	
	function getlist() {
		$.ajax({
			type : 'GET',
			url : 'functionsusers.php?mode=listDaily',
			success : function (html) {
				$('#items').html(html);
				$("tr:nth-child(odd)").addClass("alt");
			}
		});
	}

});
