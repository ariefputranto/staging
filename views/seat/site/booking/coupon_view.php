<div class="woo_product-code">
 	<div id="coupon_section">
	<input type="text" name="code" id="coupon_code_box"   value="" placeholder="Coupon Code...">
	<button type="button" class="mj_apply_coupenbtn" id="ccbtn" onclick="applyCoupon();">Apply Coupon</button>
	</div>
 
	<div class="form-group">
		<p class="cupon" id="ccmsg"></p>
	</div>
</div>
<script>
function applyCoupon() {
	coupon_code = $('#coupon_code_box').val();
	if(coupon_code == '')
	{
		msg = 'Code cannot be empty';
		$('#ccmsg').html('<strong style="color:red">'+msg+'</strong>').fadeIn(3000).fadeOut(7000);	
		return;
	}

	$.ajax({
		type: 'POST',
		async: false,
		cache: false,
		url: "<?php echo site_url();?>offers/validatecoupon",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>:'<?php echo $this->security->get_csrf_hash(); ?>',
			'code':coupon_code
			
		},
		beforeSend: function () {
			$("#ccbtn").prop('disabled', true); // disable button
		},
		success: function (data) {		  
			dta = $.parseJSON(data);
			if (dta['status'] == 0)
			{
				$('#coupon_code_box').val('')
				$("#ccmsg").html('<strong style="color:red;" >' + dta['message'] + '</strong>').show().fadeOut(3000);
				$("#ccbtn").prop('disabled', false);
			}
			else {
				var total_fare = dta['result']['total_fare_display'];
				$('#total_fare').html('<strong>'+ dta['result']['currency_symbol'] + total_fare + '</strong>' );
				$("#ccmsg").html('<strong style="color:green;" >' + dta['result']['message'] + '</strong>').show().fadeOut(3000);
				$('#coupon_section').html('');
				$('#coupon_view').fadeOut(6000);
			}
			// enable button
			//$('#popupProject').html(data).fadeIn(300);

   } 
});
}
</script>