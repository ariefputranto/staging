<section class="footer">
<div class="container">
<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"> 
<a href="<?php echo base_url();?>"> <?php if(isset($this->phrases["home"])) echo $this->phrases["home"]; else echo "Home";?> &nbsp; &nbsp; </a> 
<?php foreach($this->dynamicpages as $ind => $page) { ?>
- <a href="<?php echo base_url();?>pages/index/<?php echo $page->page_id;?>">&nbsp; &nbsp; <?php if(isset($this->phrases[strtolower($page->page_title)])) echo $this->phrases[strtolower($page->page_title)]; else echo $page->page_title;?> &nbsp; &nbsp;</a>
<?php } ?>
</a> - &nbsp; &nbsp; 
<a href="<?php echo site_url();?>offers">   Offers    &nbsp; &nbsp; </a> -
<a href="<?php echo site_url();?>welcome/faqs">&nbsp; &nbsp;    FAQ’s     </a>
<!--
<a href="#"> Home |</a>   <a href="#"> About Us   |</a> <a href="#">    FAQ’s  | </a> <a href="#">   Offers   | </a> <a href="#">   Pages  | </a> <a href="#">   Terms of Use   |  </a> <a href="#"> 
 Privacy Policy </a>  |  <a href="#">  Contact Us</a> --></div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"> <?php echo $this->config->item('site_settings')->rights_reserved_content;?> </div>
</div>
</div>
</section>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/bootstrap.min.js"></script>
<!--date Picker-->
 <script type="text/javascript" language="javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.dataTables.js"></script>
 <script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
	$('#example').dataTable();
} );
 </script>
 
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css"> -->
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery-ui.js"></script>
<script>
$(function() {
   $( "#pick_date" ).datepicker({
   defaultDate: "+1w",
   minDate: 0, 
   onClose: function( selectedDate ) {
   $( "#return_date" ).datepicker( "option", "minDate", selectedDate );
   }
   });
   $( "#return_date" ).datepicker({
   defaultDate: "+1w",
   onClose: function( selectedDate ) {
   $( "#pick_date" ).datepicker( "option", "maxDate", selectedDate );
   }
   });

   $('#dob').datepicker({
   		defaultDate: "+1w",
   		changeMonth: true,
   		changeYear: true,
		maxDate: 0 //To disable future dates
   });
});
</script>


<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/chosen.jquery.min.js">
</script>

<script>
	$(function() {
		$(".chzn-select").chosen();
	});
	
	
	$(document).click(function(e) {
        if (!$(e.target).is('#pick_point_view, #drop_point_view')) {
            $("#pick_point_list").empty();
			$("#drop_point_list").empty();
        }
    });
	
	<?php if($this->router->fetch_class() == 'bookingseat' && $this->router->fetch_method() == 'passenger_details') { 
		$minutes = $this->session->userdata('minutes');
		$seconds = $this->session->userdata('seconds');
		$booking_time_limit = (isset($this->config->item('site_settings')->booking_time_limit)) ? $this->config->item('site_settings')->booking_time_limit : 10;
	?>
	var mins=60;
	var sec = 60;
	intilizetimer(); //Timer initializer
	function intilizetimer()
	 {
		mins = <?php if($minutes != '' && $minutes >= 0) echo $minutes; else echo $booking_time_limit; ?>;
		$("#mins").text(mins);
		$("#mins1").text(mins);
		sec = <?php if($seconds != '' && $seconds > 0) echo $seconds; else echo "0" ?>;
		startInterval();
	 }
	 function startInterval()
	{
	timer= setInterval("tictac()", 1000);
	}
	function tictac()
	{
		sec--;
		if(sec<=0)
		{
			mins--;
			$("#mins").text(mins);
			$("#mins1").text(mins);
			if(mins<1)
			{
				$("#timerdiv").css("color", "red");
			}
			if(mins<0)
			{
				stopInterval();
				$("#mins").text('0');
				$("#mins1").text('0');

				//$('#submit_btn_name_value').attr({'name' : 'Finish', 'value' : 'Finish' });

				//$('form').submit();
				document.location = '<?php echo base_url();?>bookingseat/clearselection';
			}

			sec=60;
		}
		if(mins>=0) {
		$("#seconds").text(sec);
		$("#seconds1").text(sec);
		}
		else {
		$("#seconds").text('00');
		$("#seconds1").text('00');
		}

		//Ajax
		$.ajax({

			type:'POST',
			url:'<?php echo base_url();?>bookingseat/setInterval',
			data:'minutes='+mins+'&seconds='+sec+'&<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>',
			cache:false,
			success: function(data) {
				
			}
		});

	}
	
	function stopInterval()
	{
		clearInterval(timer);
	}
	<?php } ?>
	
</script>

<?php if(count($this->session->userdata('journey_booking_details')) > 0 || isset($_POST['searchbutt'])) {
	$record = $this->session->userdata('journey_booking_details');
	$pick_point = $record['pick_point'];
	?>
	<script> get_end_locations(<?php echo $pick_point?>);</script>
	<?php
}?>

 

 
  </body>
</html>