<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="footer">
<?php //print_r($this->dynamicpages);?>
<a href="<?php echo base_url();?>"> <?php if(isset($this->phrases["home"])) echo $this->phrases["home"]; else echo "Home";?> &nbsp; &nbsp; </a> 
<?php foreach($this->dynamicpages as $ind => $page) { ?>
	- <a href="<?php echo base_url();?>pages/index/<?php echo $page->page_id;?>">&nbsp; &nbsp; <?php if(isset($this->phrases[strtolower($page->page_title)])) echo $this->phrases[strtolower($page->page_title)]; else echo $page->page_title;?> &nbsp; &nbsp;</a>
<?php } ?>
 - <a href="<?php echo base_url();?>welcome/faqs">&nbsp; &nbsp;FAQ’s</a> <!--<a href="#">&nbsp; &nbsp; Contacts &nbsp; &nbsp; -</a>   <a href="#">&nbsp; &nbsp; Terms</a>-->
<p>© 2015 P2P Transfer Airport	</p>
</div>
</div>
</div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/bootstrap.min.js"></script>

<script>
$('#myCollapsible').on('hidden.bs.collapse', function () {
  // do something…
})
</script>

 <script> 
$('#viewcontrols a').click(function(){
$("#viewcontrols a").each(function() {
		$(this).removeClass('active');
	});
$(this).addClass('active');
});
$('#more_data').hide();
$('#viewmore').click(function(){
	//$('#more_data').slideToggle('slow');
	//$('#viewmore').hide();
	getVehicles('more');
});
</script>


<?php if(isset($css_type) && in_array("datatable",$css_type)) { ?>
<script type="text/javascript" language="javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.dataTables.js"></script> 
<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
	$('#example').dataTable();
	$('.example').dataTable();
	
	
} );
</script> 
<?php } ?>

<?php if(isset($css_type) && in_array("datepicker",$css_type)) { ?>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/bootstrap-datepicker.js">
</script>
<script>

	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	$('.dp').datepicker({
		format: 'dd-mm-yyyy', 
		onRender: function(date) {
					  return date.valueOf() < now.valueOf() ? 'disabled' : '';
				   }
	}).on('changeDate', function(event){
		 $(this).datepicker('hide');
	});

</script>
<?php } ?>


<?php if(isset($css_type) && in_array("timepicker",$css_type)) { ?>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/timepicki.js">
</script>
<script>
	$('.tp').timepicki({
		increase_direction:'up', 
		min_hour_value: 1
	});
</script>
<?php } ?>


<script>
$('.left').click(function(){
	//alert('sasdsdn');
		$('.leftpanel').toggle('slow');
		//$( ".leftpanel" ).slideRight();
		$('.rightpanel').hide('slow');
		});

		$('.right').click(function(){
		$('.leftpanel').hide('slow');
		$('.rightpanel').toggle('slow');
		//$( ".leftpanel" ).slideLeft();
		});
		 
    
</script>

<?php if(isset($css_type) && in_array("gmap",$css_type)) { ?>
<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery-1.6.4.js" >
</script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places">
</script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/gmap3.js">
</script>
<script>

	/* Get Map & Details */
	function get_map(PickLocation, DropLocation)
	{
		//alert(PickLocation+"->"+DropLocation);
	    jQuery("#map_canvas").gmap3({
		   clear: {},
		   getroute:{
		   options:{
					origin:PickLocation,
					destination:DropLocation,
					travelMode: google.maps.DirectionsTravelMode.DRIVING,
					/*Enable If Distance Type is Mile */
					<?php if($site_settings->distance_type=='Mile') {?>
					unitSystem: google.maps.UnitSystem.IMPERIAL,
					<?php } ?>
				   },
			  callback: function(results) {

				var dist = numberWithCommas(Math.round(parseFloat(
							results.routes[0].legs[0].distance.value
							)/1000));

				var dist_txt = dist+" "+(
							   results.routes[0].legs[0].distance.text).
							   split(" ")[1];

				var time = results.routes[0].legs[0].duration.text;

				var time_txt = time+" (Approx.)"; 

				   //alert(dist+"::"+dist_txt+"::"+time+"::"+time_txt);
				   /* Assign and Show Journey Distance & Time Values */
				   $('#dist_time').fadeIn();
				   $('#distance').val(dist);
				   $('#dist_txt').text(dist_txt);
				   $('#ip_dist_txt').val(dist_txt);
				   $('#total_time').val(time);
				   $('#time_txt').text(time_txt);
				   $('#ip_time_txt').val(time_txt);

				  if (!results) return;
					jQuery(this).gmap3({ 
						map:{
							 options:{
									center: [17.4689533,78.3891002],
									   zoom: 12
							 }
						   },
						   directionsrenderer:{
							options:{
							directions:results
						  } 
						}

					 });
			   }
			}
	   });

	}

  </script>

<?php } ?>


<?php if(isset($css_type) && in_array("bxslider",$css_type)) { ?>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/bx-slider.js">
</script>
<script>
	$('.bxslider').bxSlider({
		 minSlides: 1,
		 maxSlides: 3,
		 infiniteLoop: false
   });
</script>
<?php } ?>


<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/chosen.jquery.min.js">
</script>
<script>
	$(function() {
		$(".chzn-select").chosen();
	});
</script>

<script>

	$('document').ready(function() {

		$('.action-next, .action-prev').click(function() {

			setTimeout(
			  function() 
			  {
				//alert($('#pick_time').val());
			  }, 500);

		});

	});


	/* On change of Time */
	//function 
	
	/* Set Commas to Number */
	function numberWithCommas(x) {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parts.join(".");
	}
	
getVehicles('onchg');
</script>



</body>
</html>
