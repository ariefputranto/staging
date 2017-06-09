<!--./footer-->
<section class="bottom_footer">
  <div class="container">
    <div class="col-lg-7 col-md-7 col-sm-12 padding-lr">
      <div class="copyright-left">
        <p><?php echo $this->config->item('site_settings')->rights_reserved_content;?></p>
      </div>
    </div>
     
  </div>
</section>
<!--./bottom_footer--> 

<!--script start--> 
<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script> 
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/bootstrap.js"></script> 
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/sidemenu-script.js" type="text/javascript"></script> 


<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery-ui.js"></script>
<script>
$(function() {

   $('#date_key').datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      changeYear: true
   });

});
</script>


<script src="<?php echo base_url().$site_theme.'/';?>/assets/system_design/js/chosen.jquery.min.js"></script>
<script>
$(function() {
	$(".chzn-select").chosen();
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
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/BeatPicker.min.js"></script> 
<?php } ?>

<?php if(isset($css_type) && in_array("calendar",$css_type)) { ?>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/responsive-calendar.js"></script>

<script type="text/javascript">
  $(document).ready(function () {

	$(".responsive-calendar").responsiveCalendar({
	  time: '<?php echo date('Y-m-d');?>',
	  events: {
			<?php if(isset($date_wise_bookings) && count($date_wise_bookings) > 0) {
			  foreach($date_wise_bookings as $row) {		  
			  ?>
				 "<?php echo $row->date_of_booking;?>": {"number": <?php echo $row->no_of_bookings;?>, "url": "<?php echo base_url()."admin/viewBookings/date_wise/".$row->date_of_booking;?>"},
		   <?php } } ?>
		}
	});

  });
</script>
<?php } ?>

<script type="text/javascript" language="javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/ckeditor.js"></script> 
<script>
 var editor;
 
 // The instanceReady event is fired, when an instance of CKEditor has finished
 // its initialization.
 CKEDITOR.on( 'instanceReady', function( ev ) {
	editor = ev.editor;
 
	// Show this "on" button.
	//document.getElementById( 'readOnlyOn' ).style.display = '';
 
	// Event fired when the readOnly property changes.
	editor.on( 'readOnly', function() {
		document.getElementById( 'readOnlyOn' ).style.display = this.readOnly ? 'none' : '';
		document.getElementById( 'readOnlyOff' ).style.display = this.readOnly ? '' : 'none';
	});
 });
 
 function toggleReadOnly( isReadOnly ) {
	// Change the read-only state of the editor.
	// http://docs.ckeditor.com/#!/api/CKEDITOR.editor-method-setReadOnly
	editor.setReadOnly( isReadOnly );
 }
 
</script>


<!--./script end-->
</body></html>
