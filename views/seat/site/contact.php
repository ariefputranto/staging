<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="login con">
<ul>
<li>
 <?php echo $this->session->flashdata('message'); ?>
<div class="form-feilds">
<?php
  echo form_open('contact', "id='contact_form' name='contact_form' class=''");
  ?>
<div class="form-group">
<label>Your Name</label> <span class="red">*</span>
<input type="text" name="name" value="<?php echo set_value('name');?>" placeholder="Enter Your Name">
  <?php echo form_error('name');?>
</div>

<div class="form-group">
<label>Email Address  </label> <span class="red">*</span>
<input type="text" name="email" value="<?php echo set_value('email');?>" placeholder="Enter Your Email">
 <?php echo form_error('email');?>
</div>

<div class="form-group">
<label>Message</label>
<textarea name="msg"><?php echo set_value('msg');?></textarea>
</div>

 <div class="form-group">
 <input name="cr_tnc" type="checkbox" checked="true"> I agree with the Terms and Conditions <span class="red">*</span>
</div>

  
<div class="form-group">
<button type="submit" class="btn btn-default site-buttos"> Submit</button>
</div>
</form>
 
</div>

 
<?php
$site_settings = $this->config->item('site_settings');
?>
<div class="clearfix"></div>
</li>
<li>
<h2>Contact Info</h2>
<div class="form-feilds">
<P>We are in Melbourne and serving globally. Shoot us an email, give us a call, or fill out our Project Brief if you're interested in getting started. We look forward to learning more about you ! </P>
<?php if($site_settings->lat != '' && $site_settings->lng != '') { ?>
<p><div id="map_canvas_custom_281899" style="width:100%; height:250px" ></div>
<!--
<script type="text/javascript">
(function(d, t) {var g = d.createElement(t),s = d.getElementsByTagName(t)[0];
   //g.src = "http://map-generator.net/en/maps/281899.js?point=New+York%2C+NY%2C+USA";
   s.parentNode.insertBefore(g, s);}(document, "script"));</script>
 -->
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(<?php echo $site_settings->lat;?>,<?php echo $site_settings->lng;?>),
    zoom:9,
    mapTypeId:google.maps.MapTypeId.ROADMAP,
	center:{lat:<?php echo $site_settings->lat;?>,lng:<?php echo $site_settings->lng;?>}
  };
  var map=new google.maps.Map(document.getElementById("map_canvas_custom_281899"),mapProp);
  
  var marker = new google.maps.Marker({
      position: {
        lat: <?php echo $site_settings->lat;?>,
        lng: <?php echo $site_settings->lng;?>
      },
      map: map
    });
	var infowindow = new google.maps.InfoWindow({
    content: '<?php echo $site_settings->google_address;?>'
  });
	marker.addListener('click', function() {
    infowindow.open(marker.get('map_canvas_custom_281899'), marker);
  });
}
google.maps.event.addDomListener(window, 'load', initialize);

</script>
<!-- Do not change code! -->
</p>
<?php } ?>
 <ul>
 <?php if($site_settings->phone != '') { ?>
 <li><i class="flaticon-technology"></i><strong> Phone:</strong> <?php if($site_settings->phone_code != '') { ?>(<?php echo $site_settings->phone_code;?>) <?php } ?> <?php echo $site_settings->phone;?></li>
 <?php } ?>
  
  <?php if($site_settings->portal_email != '') { ?>
  <li><i class="flaticon-sign"></i><strong> Email:</strong> <?php echo $site_settings->portal_email;?></li>
  <?php } ?>
   <!--<li><i class="flaticon-signal"></i> <strong>Chat:</strong> yourname@exploriatrans.com</li>
    <li><i class="flaticon-travel"></i> <strong>Website:</strong> www.exploriatrans.com</li>-->
    
 </ul>
</div>
</li>
</ul>
</div>
</div>
</div>
</div>
 

 <?php /*?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    
    <script type="text/javascript">
        // When the window has finished loading create our google map below
        google.maps.event.addDomListener(window, 'load', init);
    
        function init() {
            // Basic options for a simple Google Map
            // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
            var mapOptions = {
                // How zoomed in you want the map to start at (always required)
                zoom: 11,

                // The latitude and longitude to center the map (always required)
                center: new google.maps.LatLng(40.6700, -73.9400), // New York

                // How you would like to style the map. 
                // This is where you would paste any style found on Snazzy Maps.
                styles: [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"color":"#000000"},{"lightness":13}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#144b53"},{"lightness":14},{"weight":1.4}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#08304b"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#0c4152"},{"lightness":5}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#0b434f"},{"lightness":25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#0b3d51"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"transit","elementType":"all","stylers":[{"color":"#146474"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#021019"}]}]
            };

            // Get the HTML DOM element that will contain your map 
            // We are using a div with id="map" seen below in the <body>
            var mapElement = document.getElementById('map');

            // Create the Google Map using our element and options defined above
            var map = new google.maps.Map(mapElement, mapOptions);

            // Let's also add a marker while we're at it
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(40.6700, -73.9400),
                map: map,
                title: 'Snazzy!'
            });
        }
    </script>
	<?php */?>
<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>
<script>
  (function($,W,D)
   {
      var JQUERY4U = {};

      JQUERY4U.UTIL =
      {
          setupFormValidation: function()
          {

      /* Contact form validation rules */
              $("#contact_form").validate({
                  rules: {
          name: {
                required: true
              },
          email: {
                required: true,
                email: true
            },
            cr_tnc: {
              required: true
            }
                  },

        messages: {
          name: {
                required: "<?php if(!empty($this->phrases["please enter your name"])) echo $this->phrases["please enter your name"]; else echo "Please enter your Name";?>."
              },
          email: {
                required: "<?php if(!empty($this->phrases["please enter your email"])) echo $this->phrases["please enter your email"]; else echo "Please enter your Email";?>."
              },
          cr_tnc: {
                required: "<?php if(!empty($this->phrases["you need to agree our terms and conditions"])) echo $this->phrases["you need to agree our terms and conditions"]; else echo "You need to agree our Terms and Conditions";?>."
              }
        },

                  submitHandler: function(form) {
                      form.submit();
                  }
              });

          }
      }

      //when the dom has loaded setup form validation rules
      $(D).ready(function($) {
          JQUERY4U.UTIL.setupFormValidation();
      });
   
   })(jQuery, window, document);
   

   </script>