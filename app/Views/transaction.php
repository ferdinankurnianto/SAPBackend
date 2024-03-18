<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple Database Transaction</title>
	<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
	<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    </head> 
<body>
    <div class="container-fluid">
        <h1 style="font-size:20pt">Database Transaction</h1>
 
        <h3>Transaction</h3>
        <br />
        <nav class="row navbar navbar-expand navbar-light pr-5 mr-0" id="nav">
			<div class="my-3">
				<button id="btnAdd" class="btn btn-primary" data-toggle="modal" data-target="#mdEdit"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add</button>
			</div>
		</nav>

        <table id="table" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Transaction Code</th>
                    <th>Customer Name</th>
                    <th>Product Name</th>
                    <th>Qty Out</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
 
            <tfoot>
                <tr>
                    <th>Transaction Code</th>
                    <th>Customer Name</th>
                    <th>Product Name</th>
                    <th>Qty Out</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
		
		<div id="mdEdit" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  
			  <div class="modal-header">
				<h5 class="modal-title">Transaction</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  
			  
			  <div class="modal-body">
				<form id="formEdit" method="POST" action="" enctype="multipart/form-data">

				  <input type="hidden" id="id" name="id" value="">

				  <div class="form-group">
					<label>Customer Name</label>
					<select class="form-control" id="customer_name" name="customer_name">
						<?php
							print_r($customer_name);
							foreach($customer_name as $name){
								echo	"<option value='".$name["customer_name"]."'>".$name["customer_name"]."</option>";
							}
						?>
					</select>
				  </div>
				  <div class="form-group">
					<label>Product Name</label>
					<select class="form-control" id="product_name" name="product_name">
						<?php
							foreach($product_name as $name){
								echo	"<option value=".$name["product_name"].">".$name["product_name"]."</option>";
							}
						?>
					</select>
				  </div>
				  <div class="form-group">
					<label>Qty Out</label>
					<input type="type" class="form-control" id="qty_out" name="qty_out" value="">
				  </div>
				</form>
			  </div>
			  
			  <div class="modal-footer">
				<button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="btnSubmit" type="button" class="btn btn-primary">Submit</button>
			  </div>
			</div>
		  </div>
		</div>

		<div id="mdDelete" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title2">Delete</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body2">
					  <form id="formDelete" method="POST" action="" enctype="multipart/form-data">
				
					  <input type="hidden" id="idDel" name="transaction_code" value="">
					  </form>
					  <a class="m-1">Are you sure you want to delete this row?</a>
				  </div>
				  <div class="modal-footer">
					<button id="btnCls" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button id="btnYes" type="button" class="btn btn-primary">Yes</button>
				  </div>
				</div>
			</div>
		</div>
		
    </div>

<script type="text/javascript">
 
var table;
 
$(document).ready(function() {
	var writepath = 'localhost/testsapx/writable'
    table = $('#table').DataTable({
		
        ajax: `<?php echo base_url('transaction/getall') ?>`,
        columnDefs: [
			{
                targets: -1, render: function(data, type, row, meta){
					var id = row[0];
					return '<button id ="edit" data-id="'+id+'" class="btn btn-primary" data-toggle="modal" data-target="#mdEdit">Edit</button> '+
					'<button id ="delete" data-id="'+id+'" class="btn btn-primary" data-toggle="modal" data-target="#mdDelete">Delete</button>'
				}
            },
        ],
    });
	
	$(document).on('click', '#edit', function(){
		var id = $(this).attr("data-id");
		$('.modal-title').html('Edit '+id+' Transaction');
		$.ajax({
			data: {id},
            url:'<?php echo base_url('transaction/get') ?>',
            method: 'GET',
            dataType: 'json', 
            success: function(result){
					$('#id').attr("value", id);
					$('#transaction_code').attr("value", result.transaction_code);
					$('#customer_name').val(result.customer_name);
					$('#product_name').val(result.product_name);
					$('#qty_out').attr("value", result.qty_out);
			}
		});
	});
	
	$(document).on('click', '#delete', function(){
		var id = $(this).attr("data-id");
		$("#idDel").attr("value", id);
	});
	
	$('#btnSubmit').click(function(){
		var myForm = document.getElementById('formEdit');
		var dataSend = new FormData(myForm);
		console.log(dataSend);
		var id = $('#id').val();
		if(id){
			$.ajax({
				url:'<?php echo base_url('transaction/edit') ?>',
				data:dataSend,
				processData: false,
				contentType: false,
				method:'POST',
				dataType:'JSON',
				success:function(result){
					var alert = '<div class="alert alert-danger" role="alert">';
					if(result.status){
						$('.modal-body').html('<div class="alert alert-primary" role="alert">'
						+result.message+'</div>');
						$('#btnSubmit').hide();
						$('#btnClose').attr('onclick','window.location.reload()');
					} else {
						$('.modal-body').prepend(alert+result.message+'</div>');
					}
				}
			})
		} else {
			$.ajax({
				url:'<?php echo base_url('transaction/add') ?>',
				data:dataSend,
				processData: false,
				contentType: false,
				method:'POST',
				dataType:'JSON',
				success:function(result){
					var alert = '<div class="alert alert-danger" role="alert">';
					if(result.status){
						$('.modal-body').html('<div class="alert alert-primary" role="alert">'
						+result.message+'</div>');
						$('#btnSubmit').hide();
						$('#btnClose').attr('onclick','window.location.reload()');
					} else {
						$('.modal-body').prepend(alert+result.message+'</div>');
					}
				}
			})
		}
	});
	
	$('#btnYes').click(function(){
		var id = $("#idDel").val();
		
        $.ajax({	
            data: {id},
            url:'<?php echo base_url('transaction/delete') ?>',
            method: 'POST',
            dataType: 'json', 
            success: function(result){
				var alert = '<div class="alert alert-danger" role="alert">';
				if(result.status){
					$('.modal-body2').html('<div class="alert alert-primary" role="alert">'
					+result.message+'</div>');
					$('#btnYes').hide();
					$('#btnCls').attr('onclick','window.location.reload()');
				} else {
					$('.modal-body').prepend(alert+result.message+'</div>');
				}
            }
        });
    });
	
	$('#btnAdd').click(function(){
		$('#formEdit :input').attr('value', '');
		$('.modal-title').html('Add Transaction');
	});
});
</script>
 
</body>
</html>