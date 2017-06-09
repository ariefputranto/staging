<section class="hil">
<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="login other-form">
<ul>
<li>
 
 <div><?php echo $message;?></div>
<div class="form-feilds">
<?php
echo form_open('bookingseat/search_ticket', "id='print_ticket_form' name='print_ticket_form' class=''");
$value = '';
if(isset($_POST['buttSearch']))
	$value = $this->input->post('booking_ref');
?>
<div class="form-group">
<label><?php echo getPhrase('Ticket Number');?></label>
<input type="text" placeholder="<?php echo getPhrase('Enter Ticket Number');?>" name="booking_ref" id="booking_ref" value="<?php echo $value;?>"  required>
<span class="user"> <i class="flaticon-paper"></i> </span>
</div>

<div class="form-group">
<input type="submit" name="buttSearch" id="buttSearch" value="<?php echo getPhrase('Search');?>" class="btn btn-default site-buttos">
</div>
</form>

<?php 
//print_r($ticket_details);
if(count($ticket_details)) { ?>

<font color="red"><b><div id="message"></div></b></font>
<div class="form-group clearfix">
	<?php 
	$cost_of_journey = ($ticket_details[0]->basic_fare + $ticket_details[0]->service_charge + $ticket_details[0]->insurance_amount) - $ticket_details[0]->discount_amount;
	echo '<h3>'.getPhrase('Ticket Details').'</h3>';
	$str = '<strong>'.getPhrase('Pick-up Location') . ' :</strong> '.$ticket_details[0]->pick_point.'<br>';
	$str .= '<strong>'.getPhrase('Drop-off Location') . ' :</strong> '.$ticket_details[0]->drop_point.'<br>';
	$str .= '<strong>'.getPhrase('Journey Date') . ' :</strong> '.$ticket_details[0]->pick_date.'<br>';
	$str .= '<strong>'.getPhrase('Cost of journey') . ' :</strong> '.number_format($cost_of_journey, 2).'<br>';
	$str .= '<strong>'.getPhrase('Seat') . ' :</strong> '.$ticket_details[0]->seat_no.'<br>';
	$str .= '<strong>'.getPhrase('Shuttle no.') . ' :</strong> '.$ticket_details[0]->shuttle_no.'<br>';
	$str .= '<strong>'.getPhrase('Vehicle') . ' :</strong> '.$ticket_details[0]->name.' ('.$ticket_details[0]->number_plate.')';
	echo $str;
	?>
	
</div>

<div class="form-group clearfix">
 <input id="how-other7" name="action" type="radio" value="print" onclick="show_input(this.value)" checked>
	<label for="how-other7" class="side-label"><?php echo getPhrase('Print ticket');?></label>
	<div id="print_div" style="display:none;">
		<?php echo $this->load->view('email/print_ticket.tpl.php', array('message_text' => $str), true);?>
	</div>
</div>


<div class="form-group clearfix llb">
 <input id="how-other8" name="action" type="radio" value="sms" onclick="show_input(this.value)">
	<label for="how-other8" class="side-label"><?php echo getPhrase('Get mTicket by SMS');?></label>
</div>

<div class="form-group clearfix llb">
 <input id="how-other9" name="action" type="radio" onclick="show_input(this.value)" value="email">
	<label for="how-other9" class="side-label"><?php echo getPhrase('Get Ticket by Email');?></label>
</div>

<div class="form-group clearfix llb" id="email_address_div" style="display:none;">
<input type="text" name="email_address" id="email_address" >
</div>

<div class="form-group llb">
<input type="button" name="buttSearch" id="buttSearch" value="<?php echo getPhrase('Go');?>" class="btn btn-default site-buttos" onclick="ticket()">
</div>
 
 
<script type="text/javascript">
	function show_input(val)
	{
		if(val == 'email')
		{
			$('#email_address_div').show();
		}
		else
		{
			$('#email_address_div').hide();
		}
	}
	function ticket()
	{
		var val = $('input[name=action]:checked').val();
		var booking_ref = $('#booking_ref').val();
		if(booking_ref != '')
		{
			if(val == 'email' && $('#email_address').val() == '')
			{
				alert('<?php echo getPhrase('Please enter email address');?>')
				$('#email_address').focus();
				return false;
			}
			if(val == 'print')
			{
				printDiv('print_div');
			}
			else if(val == 'sms')
			{
				sendsms(booking_ref);
			}
			else if(val == 'email')
			{
				sendemail(booking_ref);
			}
		}
		else
		{
			alert('<?php echo getPhrase('Please enter booking reference');?>')
			$('#booking_ref').focus();
			return false;
		}
	}
	
	function sendsms(booking_ref)
	{
		$.ajax({
		  type: "post",
		  url: "<?php echo base_url();?>bookingseat/send_sms",
		  async: false,
		  data: { 
					booking_ref:booking_ref, 
					<?php echo $this->security->get_csrf_token_name();?>:
					"<?php echo $this->security->get_csrf_hash();?>"
				},
		  cache: false, 
		  success: function(data) {
			var parsed_data = $.parseJSON(data);
			$('#message').html(parsed_data.message);
		  },
		  error: function(){
			alert('Ajax Error');
		  }
		});
	}
	
	function sendemail(booking_ref)
	{
		$.ajax({
		  type: "post",
		  url: "<?php echo base_url();?>bookingseat/sendemail",
		  async: false,
		  data: { 
					booking_ref:booking_ref, 
					<?php echo $this->security->get_csrf_token_name();?>:
					"<?php echo $this->security->get_csrf_hash();?>",
					email_address:$('#email_address').val()
				},
		  cache: false, 
		  success: function(data) {
			var parsed_data = $.parseJSON(data);
			$('#message').html(parsed_data.message);
			$('#email_address').val('');
			$('#email_address_div').hide();
		  },
		  error: function(){
			alert('Ajax Error');
		  }
		});
	}
	
	
	
	
	function printDiv(div_id)
	{
	 var content = $('#'+div_id).html();
	 return printContent(content);
	}

	function printContent(data)
	{
		   //alert(data);
			 var WinPrint = window.open('', '', 'left=0,top=0,width=1200,height=900,toolbar=0,scrollbars=0,status=0');
			 WinPrint.document.write('<html>');
			 WinPrint.document.write('<head>');
			 WinPrint.document.write('<title></title>');
			 WinPrint.document.write('</head>');
			 WinPrint.document.write('<body style="padding:10px;" onLoad="window.print()">');
			 WinPrint.document.write(data);
			 WinPrint.document.write('</body>');
			 WinPrint.document.write('</html>');
			 WinPrint.document.close();  
			 WinPrint.focus();
			 
			 var is_chrome = function () { return Boolean(window.chrome); }
		   if(is_chrome) 
		   {
			  //setTimeout(function(){ WinPrint.print();}, 1500); 
			 setTimeout(function(){  WinPrint.close();}, 10000); 
			  //give them 10 seconds to print, then close
		   }
		   else
		   {
			 WinPrint.print();
			 WinPrint.close();
		   }

			 
	}
</script>
<?php } ?>

</div>

 

<div class="clearfix"></div>
</li>
 
</ul>
</div>
</div>
</div>
</div>
</section>