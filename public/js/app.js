$(function(){

	var elementBarang = $('select#containerBarang');


	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	$(document).ready(function(){

		if(elementBarang.length){
			initData();
		}

    var initTable = function(){
  		$.ajax({
  			url : 'http://localhost:8000/admin/api/barang/all',
  			type : 'post',
  			dataType : 'json',
  			success : function(data){
  				$("select#containerBarang").html(data);
  			}
  		});
  	}

});
