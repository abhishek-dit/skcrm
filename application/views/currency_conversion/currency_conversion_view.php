<?php
	$this->load->view('commons/main_template',$nestedView); 
?>

	<?php
		echo $this->session->flashdata('response');
		if(@$flg!='')
		{
			if($flg == 1)
			{
				$formHeading = 'Add Currency Conversion';
				?>
				<div class="row"> 
					<div class="col-sm-12 col-md-12">
						<div class="block-flat">
							<div class="header">							
								<h4><?php echo $formHeading;?></h4>
							</div>
							<div class="content">
								<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>insert_currency_conversion"  parsley-validate novalidate method="post">	
									<div class="col-md-1">
										<button type="button" class='btn addline2 btn-primary btn-sm' style="height: 30px; width: 80px;margin-top: 6px;"><i class="fa fa-plus"> Add</i></button>
									</div>
									<div class="table-responsive" >
			                            <div class="col-md-offset-3 col-md-9 col-sm-9" >    
			                                <table id="table1" class="table table-striped table-hover table-bordered" style="width: 70%;">
			                                    <thead>
			                                    	<th width="1%" class="text-center"></th>
			                                        <th class="text-center" width="6%">From Currency</th>
			                                        <th class="text-center" width="6%">To Currency</th>
			                                        <th class="text-center" width="6%">Conversion Rate</th>
			                                        
			                                    </thead>
			                                    <tbody>
			                                    	<?php 
			                                    	if(count(@$currency_transactions)>0)
			                                    	{
			                                    	foreach ($currency_transactions as $key => $value) 
		                                    		{ ?>
			                                    		<tr class="currency_row">
				                                       	<td class="text-center">
				                                             <button type="button" class='btn delete btn-danger' style="padding: 3px;"><i class="fa fa-times"></i></button>
				                                        </td>
				                                        <td>
				                                        	<select disabled class="from_currency_id form-control" style="width:100%">
				                                        		<option value="">- Select Currency -</option>
				                                                <?php
				                                                foreach($currency as $row)
				                                                {
				                                                	if($value['from_currency_id']==@$row['currency_id'])
									                                {
									                                   	$selected ='selected';
									                                }
									                                else
									                                {
									                                 	$selected ='';
									                                }
									                            	echo '<option value="'.$row['currency_id'].'"'.$selected.'>'.$row['code'].'</option>'; 
				                                                }
				                                                
					                                            ?>
				                                            </select>
				                                            <input type='hidden'  name='from_currency[]' value="<?php echo $value['from_currency_id'] ?>" >
				                                         </td>
				                                        <td>
				                                            <input type="text" readonly class='form-control to_currency' value="<?php echo @$value['to_currency_code'];?>" >
				                                            <input type='hidden'  name='to_currency[]' value="<?php echo $company_currency['currency_id'] ?>" >
				                                        </td>
				                                        <td>
				                                            <input type="number" min="0"  class='form-control only-numbers cur_value' name='value[]' autocomplete="off" value="<?php echo @$value['value'];?>">
				                                        </td>
				                                        
				                                    </tr>
			                                    	<?php }} ?>
			                                    	<tr class="currency_row">
				                                       	<td class="text-center">
				                                             <button type="button" class='btn delete btn-danger' style="padding: 3px;"><i class="fa fa-times"></i></button>
				                                        </td>
				                                        <td>
				                                        	<select class="form-control from_currency_id" style="width:100%" name="from_currency[]">
				                                                <option value="">- Select Currency -</option>
				                                                <?php
				                                                foreach($currency as $row1)
				                                                {
				                                                	echo '<option value="'.$row1['currency_id'].'">'.$row1['code'].'</option>';
				                                                }
				                                                ?>
				                                            </select>
				                                        </td>
				                                        <td>
				                                            <input type="text" readonly class='form-control to_currency' value="<?php echo $company_currency['code'] ?>" >
				                                            <input type="hidden" name="to_currency[]" value="<?php echo $company_currency['currency_id'] ?>">
				                                            
				                                        </td>
				                                        <td>
				                                            <input type="number" min="0"  class='form-control cur_value' autocomplete="off" name='value[]'>
				                                            
				                                        </td>
				                                        
				                                    </tr>
			                                    </tbody>
			                                </table>
			                            </div>
			                        </div>
									<div class="form-group" style="margin-top: 15px;">
										<div class="col-sm-offset-5 col-sm-10" style="margin-top: inherit;">
											<button class="btn btn-primary" type="submit" name="submit" value="button"><i class="fa fa-check"></i> Submit</button>
											<a class="btn btn-danger" href="<?php echo SITE_URL;?>currency_conversion"><i class="fa fa-times"></i> Cancel</a>
										</div>
									</div>
								</form>
							</div>
						</div>				
					</div>
				</div> <?php
			}
		}
	?>
<?php
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">
	$(document).on('click', '.addline2', function () {
	    var $tr = $("#table1 tr:first");
	    var $lastTr = $tr.closest('#table1').find('tr:last');

	    var $clone = $lastTr.clone(true);

	    $clone.find('td').each(function() {
	        var el = $(this).find(':first-child');
	        var id = el.attr('id') || null;
	        if (id) {
	            var i = id.substr(id.length - 1);
	            var prefix = id.substr(0, (id.length - 1));
	            el.attr('id', prefix + (+i + 1));
	        }
	    });
	    $('#table1').append($clone);
	    
	    $clone.find('.from_currency_id').prop('disabled',false);
	    $clone.find('.from_currency_id,.cur_value').val('');
	});
	$(document).on('click','.delete', function () {
	  cnt=$(this).parents('table').find('tr').length;
	  var first = $("#table1 tr:first").closest('#table1').find('tr:last');
	  if(cnt> 2)
	  {
	    $(this).parents("tr").remove();
	  }
	  else
	  {
	  	first.find('.from_currency_id').prop('disabled',false);
	    first.find('.from_currency_id,.cur_value').val('');
	  }
	  
	});

	$("form").submit(function( e ) {

	  var check = 0;
	  var n = $('#table1 tbody tr').length;
	  if(n==1)
	  {
	     var first_row = $('#table1 tbody tr').find(':selected').val();
	     if(first_row =='')
	     {
	        alert('Please fill Atleast one Currency Conversion Value to Proceed The Transaction');
	        return false;
	     }
	  }
	  $('#table1 tbody tr').each(function(){
	    var ele = $(this).closest('.currency_row');
	    var from_currency = ele.find('.from_currency_id').find(':selected').val();
	    var to_currency = ele.find('.to_currency').val();
	    var value = ele.find('.cur_value').val();  
	    
	    if(from_currency =='' || to_currency =='' || value=='')
	    {
	    	check++;
	    }
	  });
	  if(check > 0)
	  {
	    alert('Please Fill All Required Fields!');
	    return false;
	  }
	});

	$(document).on('change','.from_currency_id',function(){
		var ele = $(this).closest('.currency_row');
		var from_id = $(this).find(':selected').val();
		var check = 0;
		$('.from_currency_id:selected').each(function(){
		    var each_from_id = $(this).val();
		    if(from_id == each_from_id)
		    {
		      check++;
		    }
		});

		if(check>0)
		{
			if(from_id!='')
			{
				alert("Please Select Other Currency");
				ele.find('.from_currency_id,.cur_value').val('');

			}
			return false;
		}
	});

</script>