  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<?php echo $this->session->flashdata('message');?>
				<?php 
				  $attributes = array('name' => 'site_settings_form', 'id' => 'site_settings_form');
				  echo form_open_multipart('settings/siteSettings',$attributes);
				  //print_r($site_settings);
				  ?> 

			<div class="col-md-6">
				<div class="form-group">
					<label><?php if(isset($this->phrases["site title"])) echo $this->phrases["site title"]; else echo "Site Title";?></label>
					 <input type="text" name="site_title" id="site_title" value="<?php echo set_value('site_title', (isset($site_settings->site_title)) ? $site_settings->site_title : '');?>"/>
					<?php echo form_error('site_title'); ?>
				</div>
				
				<div class="form-group">
					<label><?php if(isset($this->phrases["home page title"])) echo $this->phrases["home page title"]; else echo "Home Page Title";?></label>
					 <input type="text" name="homepage_title" id="homepage_title" value="<?php echo set_value('homepage_title', (isset($site_settings->homepage_title)) ? $site_settings->homepage_title : '');?>"/>
					<?php echo form_error('homepage_title'); ?>
				</div>
				<div class="form-group">
					<label><?php if(isset($this->phrases["home page sub title"])) echo $this->phrases["home page sub title"]; else echo "Home Page Sub Title";?></label>
					 <input type="text" name="homepage_subtitle" id="homepage_subtitle" value="<?php echo set_value('homepage_subtitle', (isset($site_settings->homepage_subtitle)) ? $site_settings->homepage_subtitle : '');?>"/>
					<?php echo form_error('homepage_subtitle'); ?>
				</div>

				<div class="form-group">       
                     <label><?php if(isset($this->phrases["address"])) echo $this->phrases["address"]; else echo "Address";?></label></label>				
                     <input type="text" name="address" id="address" value="<?php echo set_value('address', (isset($site_settings->address)) ? $site_settings->address : '');?>"/>  
                     <?php echo form_error('address');?>
					 <input type="hidden" name="google_address" id="google_address" value="<?php echo set_value('google_address', (isset($site_settings->google_address)) ? $site_settings->google_address : '');?>"/> 
					 
					 <input type="hidden" name="lat" id="lat" value="<?php echo set_value('lat', (isset($site_settings->lat)) ? $site_settings->lat : '');?>"/> 
					 <input type="hidden" name="lng" id="lng" value="<?php echo set_value('lng', (isset($site_settings->lng)) ? $site_settings->lng : '');?>"/> 
                  </div>

                  <div class="form-group">      
                     <label><?php if(isset($this->phrases["city"])) echo $this->phrases["city"]; else echo "City";?></label>
                     <input type="text" name="city" id="city" value="<?php echo set_value('city', (isset($site_settings->city)) ? $site_settings->city : '');?>" onfocus="javascript:callAutocompleteAddress()"/>
                     <?php echo form_error('city');?>
                  </div>

                  <div class="form-group">     
                     <label><?php if(isset($this->phrases["state"])) echo $this->phrases["state"]; else echo "State";?></label>		
                     <input type="text" name="state" id="state" value="<?php echo set_value('state', (isset($site_settings->state)) ? $site_settings->state : '');?>"/>
                     <?php echo form_error('state');?>				  
                  </div>

                  <div class="form-group">        
                     <label><?php if(isset($this->phrases["country"])) echo $this->phrases["country"]; else echo "Country";?></label>					
                     <input type="text" name="country" id="country" value="<?php echo set_value('country', (isset($site_settings->country)) ? $site_settings->country : '');?>"/> 
                     <?php echo form_error('country');?>
                  </div>

                  <div class="form-group">             
                     <label><?php if(isset($this->phrases["zip code"])) echo $this->phrases["zip code"]; else echo "Zip Code";?></label>
                     <input type="text" name="zip" id="zip" value="<?php echo set_value('zip', (isset($site_settings->zip)) ? $site_settings->zip : '');?>"/>  
                     <?php echo form_error('zip');?>
                  </div>

				  <div class="form-group">
                     <label><?php if(isset($this->phrases["phone code"])) echo $this->phrases["phone code"]; else echo "Phone Code";?></label>				
                     <input type="text" name="phone_code" value="<?php echo set_value('phone_code', (isset($site_settings->phone_code)) ? $site_settings->phone_code : '');?>"/>
                     <?php echo form_error('phone_code');?>
                  </div>

                  <div class="form-group">
                     <label><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?></label>
                     <input type="text" name="phone" value="<?php echo set_value('phone', (isset($site_settings->phone)) ? $site_settings->phone : '');?>"/>
                     <?php echo form_error('phone');?>
                  </div>

                  <div class="form-group">   
                     <label><?php if(isset($this->phrases["land line"])) echo $this->phrases["land line"]; else echo "Land Line";?></label>				
                     <input type="text" name="land_line" value="<?php echo set_value('land_line', (isset($site_settings->land_line)) ? $site_settings->land_line : '');?>"/>  
                     <?php echo form_error('land_line');?>
                  </div>
                  <div class="form-group">
                     <label><?php if(isset($this->phrases["fax"])) echo $this->phrases["fax"]; else echo "Fax";?></label>
                     <input type="text" name="fax" value="<?php echo set_value('fax', (isset($site_settings->fax)) ? $site_settings->fax : '');?>"/>  
                     <?php echo form_error('fax');?>
                  </div>
				  
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["facebook"])) echo $this->phrases["facebook"]; else echo "Facebook";?></label>
                     <input type="text" name="facebook" value="<?php echo set_value('facebook', (isset($site_settings->facebook)) ? $site_settings->facebook : '');?>"/>  
                     <?php echo form_error('facebook');?>
                  </div>
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["twitter"])) echo $this->phrases["twitter"]; else echo "Twitter";?></label>
                     <input type="text" name="twitter" value="<?php echo set_value('twitter', (isset($site_settings->twitter)) ? $site_settings->twitter : '');?>"/>  
                     <?php echo form_error('twitter');?>
                  </div>
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["instagram"])) echo $this->phrases["instagram"]; else echo "Instagram";?></label>
                     <input type="text" name="instagram" value="<?php echo set_value('instagram', (isset($site_settings->instagram)) ? $site_settings->instagram : '');?>"/>  
                     <?php echo form_error('instagram');?>
                  </div>
				  
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["call center"])) echo $this->phrases["call center"]; else echo "Call Center";?></label>
                     <textarea name="call_center"><?php echo set_value('call_center', (isset($site_settings->call_center)) ? $site_settings->call_center : '');?></textarea>
                     <?php echo form_error('call_center');?>
                  </div>
				  
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["email support"])) echo $this->phrases["email support"]; else echo "Email Support";?></label>
                     <textarea name="email_support"><?php echo set_value('email_support', (isset($site_settings->email_support)) ? $site_settings->email_support : '');?></textarea>   
                     <?php echo form_error('email_support');?>
                  </div>
				  
				  <div class="form-group">
                     <label><?php echo getPhrase('Transition time type');?></label>
                     <select name="transition_time_units" id="transition_time_units" onchange="addvalues(this.value);">
					 <?php
					 $transition_time_units = set_value('transition_time_units', (isset($site_settings->transition_time_units)) ? $site_settings->transition_time_units : '');
					 if($transition_time_units == 'hours')
						 echo '<option value="hours" selected>Hours</option>';
					 else
					 echo '<option value="hours">Hours</option>';
				 
				 if($transition_time_units == 'minutes')
					 echo '<option value="minutes" selected>Minutes</option>';
				 else
					 echo '<option value="minutes">Minutes</option>';
					 ?>
					 </select>					 
                     <?php echo form_error('transition_time_units');?>
                  </div>
				  <script type="text/javascript">
					function addvalues(val)
					{
						var str = '';
						if(val == 'minutes')
						{
							for(var i = 0; i < 60; i++)
							{
								str += '<option value="'+i+'">'+i+'</option>'
							}
						}
						else
						{
							for(var i = 0; i < 24; i++)
							{
								str += '<option value="'+i+'">'+i+'</option>'
							}
						}
						document.getElementById('transition_time').innerHTML = str;
					}
				  </script>
				  
				  <div class="form-group">
                     <label><?php echo getPhrase('Transition time');?></label>
                     <select name="transition_time" id="transition_time">
					 <?php
					 $transition_time = set_value('transition_time', (isset($site_settings->transition_time)) ? $site_settings->transition_time : '');
					 $counter = 60;
					 if($transition_time_units == 'hours')
						 $counter = 24;
					 for($i = 1; $i < $counter; $i++)
					 {
						if($transition_time == $i)
						{
						echo '<option value="'.$i.'" selected>'.$i.'</option>';
						}
						else
						{
						echo '<option value="'.$i.'">'.$i.'</option>'; 
						}
					 }
					 ?>
					 </select>					 
                     <?php echo form_error('transition_time');?>
                  </div>
				  
				  <?php /*?>
				  <!--Display vehicles after-->
				  <div class="form-group">
                     <label><?php echo getPhrase('Display vehicles after');?></label>
                     <select name="display_after_units" id="display_after_units" onchange="addvalues2(this.value);">
					 <?php
					 $display_after_units = set_value('display_after_units', (isset($site_settings->display_after_units)) ? $site_settings->display_after_units : '');
					 if($display_after_units == 'hours')
					 echo '<option value="hours" selected>Hours</option>';
				 else
					 echo '<option value="hours">Hours</option>';
					 
					 if($display_after_units == 'minutes')
					 echo '<option value="minutes" selected>Minutes</option>';
				 else
					 echo '<option value="minutes">Minutes</option>';
					 ?>
					 </select>					 
                     <?php echo form_error('display_after_units');?>
                  </div>
				  <script type="text/javascript">
					function addvalues2(val)
					{
						var str = '';
						if(val == 'minutes')
						{
							for(var i = 0; i < 60; i++)
							{
								str += '<option value="'+i+'">'+i+'</option>'
							}
						}
						else
						{
							for(var i = 0; i < 24; i++)
							{
								str += '<option value="'+i+'">'+i+'</option>'
							}
						}
						document.getElementById('display_after').innerHTML = str;
					}
				  </script>
				  
				  <div class="form-group">
                     <label><?php echo getPhrase('Time');?></label>
                     <select name="display_after" id="display_after">
					 <?php
					 $display_after = set_value('display_after', (isset($site_settings->display_after)) ? $site_settings->display_after : '');
					 $counter = 60;
					 if($display_after_units == 'hours')
						 $counter = 24;
					 for($i = 1; $i < $counter; $i++)
					 {
						if($display_after == $i)
						{
						echo '<option value="'.$i.'" selected>'.$i.'</option>';
						}
						else
						{
						echo '<option value="'.$i.'">'.$i.'</option>'; 
						}
					 }
					 ?>
					 </select>					 
                     <?php echo form_error('display_after');?>
                  </div>
				  <?php */?>
				  
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["booking time limit"])) echo $this->phrases["booking time limit"]; else echo "Booking time limit";?></label>
                     <input type="text" name="booking_time_limit" value="<?php echo set_value('booking_time_limit', (isset($site_settings->booking_time_limit)) ? $site_settings->booking_time_limit : 10);?>"/>  
                     <?php echo form_error('booking_time_limit');?>
                  </div>

               </div>

				<div class="col-md-6">
				
				<div class="form-group">           
                     <label><?php if(isset($this->phrases["site theme"])) echo $this->phrases["site theme"]; else echo "Site Theme";?></label>			
                     <?php 					 
                        $site_theme_select = set_value('site_theme', (isset($site_settings->site_theme) ? $site_settings->site_theme : ''));
						
						$site_theme_options = array('vehicle' => (isset($this->phrases['Vehicle Booking'])) ? $this->phrases['Vehicle Booking'] : 'Vehicle Booking',
						'seat' => (isset($this->phrases['Seat Booking'])) ? $this->phrases['Seat Booking'] : 'Seat Booking',
						);
	
                         echo form_dropdown('site_theme',$site_theme_options,$site_theme_select,'class = "chzn-select"');?>
                  </div>
				  
				<?php if($site_theme == 'seat') { ?>
				<div class="form-group">  
					<label><?php echo getPhrase('Max Seats to Book');?></label>
					<select name="max_seats_to_book" id="max_seats_to_book">
					<?php 
					$max_seats_to_book = (isset($site_settings->max_seats_to_book)) ? $site_settings->max_seats_to_book : 5;
					for($i = 1; $i <= 10; $i++) {
					$selected = '';
					if($max_seats_to_book == $i)
					$selected = ' selected';
					?>
					<option value="<?php echo $i?>" <?php echo $selected;?>><?php  echo $i;?></option>
					<?php
					}?>
					</select>
					<?php echo form_error('max_seats_to_book'); ?>
				</div>
				
				<div class="form-group">  
				 <label><?php echo getPhrase('Can Cancel Seat Before');?> (Hours)</label>
				  <select name="canncel_before_hours" id="canncel_before_hours">
					<?php 
					$canncel_before_hours = (isset($site_settings->canncel_before_hours)) ? $site_settings->canncel_before_hours : 2;
					for($i = 0; $i <= 24; $i++) {
						$selected = '';
						if($canncel_before_hours == $i)
							$selected = ' selected';
						?>
						<option value="<?php echo $i?>" <?php echo $selected;?>><?php  echo $i;?></option>
						<?php
					}?>
				  </select>
				  <?php echo form_error('canncel_before_hours'); ?>
			   </div>
			   
			   <div class="form-group">  
				 <label><?php echo getPhrase('Max Times can send SMS');?></label>
				  <select name="max_times_sms_cansend" id="max_times_sms_cansend">
					<?php 
					$max_times_sms_cansend = (isset($site_settings->max_times_sms_cansend)) ? $site_settings->max_times_sms_cansend : 3;
					for($i = 0; $i <= 24; $i++) {
						$selected = '';
						if($max_times_sms_cansend == $i)
							$selected = ' selected';
						?>
						<option value="<?php echo $i?>" <?php echo $selected;?>><?php  echo $i;?></option>
						<?php
					}?>
				  </select>
				  <?php echo form_error('max_times_sms_cansend'); ?>
			   </div>
			   
			   <div class="form-group">
                     <label><?php echo getPhrase('Insurance (Per person)');?></label>
                     <input name="insurance_value" id="insurance_value" type="text" value="<?php echo set_value('insurance_value', (isset($site_settings->insurance_value)) ? $site_settings->insurance_value : '');?>"><?php echo form_error('insurance_value');?>
                     <?php
                     		/*
							$options = array('value' => getPhrase('Value'), 'percent' => getPhrase('Percent'));
                     		$sel = set_value('insurance_type', (isset($site_settings->insurance_type)) ? $site_settings->insurance_type : '');

                     		echo form_dropdown('insurance_type', $options, $sel, 'id="insurance_type"');
							*/
							echo '<input type="hidden" name="insurance_type" value="value">';
							echo '<input type="hidden" name="insurance_appliedto" value="per_person">';

                     ?>
                  </div>
				  
				  <!--
				  <div class="form-group">
                     <label><?php echo getPhrase('Insurance applied on');?></label>      
					<?php
					$options = array('basic' => getPhrase('Basic Fare'), 'total' => getPhrase('Total Fare'));
					$sel = set_value('insurance_appliedon', (isset($site_settings->insurance_appliedon)) ? $site_settings->insurance_appliedon : '');
					echo form_dropdown('insurance_appliedon', $options, $sel, 'id="insurance_appliedon"').form_error('insurance_appliedon');
					?>
                  </div>-->
				  
				  <!--<div class="form-group">
                     <label><?php echo getPhrase('Insurance applied to');?></label>       
					<?php
					$options = array('per_person' => getPhrase('Per Person'), 'total' => getPhrase('Total Fare'));
					$sel = set_value('insurance_appliedto', (isset($site_settings->insurance_appliedto)) ? $site_settings->insurance_appliedto : '');
					echo form_dropdown('insurance_appliedto', $options, $sel, 'id="insurance_appliedto"').form_error('insurance_appliedto');	
					
					?>
                  </div>-->
				  
				<?php } ?>
				
				<div class="form-group">  
				 <label><?php if(isset($this->phrases["portal email"])) echo $this->phrases["portal email"]; else echo "Portal Email";?></label>
				  <input type="text" name="portal_email" id="portal_email" value="<?php echo set_value('portal_email', (isset($site_settings->portal_email)) ? $site_settings->portal_email : '');?>"/>
					<?php echo form_error('portal_email'); ?>	  
			   </div>

			   <div class="form-group">           
                     <label><?php if(isset($this->phrases["site country"])) echo $this->phrases["site country"]; else echo "Site Country";?></label>			
                     <?php 					 
                        $country_select = set_value('site_country', (isset($site_settings->site_country) ? $site_settings->site_country : ''));
	
                         echo form_dropdown('site_country',$country_options,$country_select,'class = "chzn-select"');?>
                  </div>

                  <div class="form-group">
                     <label><?php if(isset($this->phrases["time zone"])) echo $this->phrases["time zone"]; else echo "Time Zone";?></label>			
                     <?php 					 
                        $time_zone_options_select = set_value('site_time_zone', (isset($site_settings->site_time_zone) ? $site_settings->site_time_zone : ''));

                         echo form_dropdown('site_time_zone',$time_zone_options,$time_zone_options_select,'class = "chzn-select"');?>
                  </div>

                  <?php if($site_theme == 'vehicle') { ?>
				  <div class="form-group">           
                     <label><?php if(isset($this->phrases["distance type"])) echo $this->phrases["distance type"]; else echo "Distance Type";?></label>			
                     <?php 					 

                        $first_opt = (isset($this->phrases["kilometer"])) ? $this->phrases["kilometer"] : "Kilometer";
                        $sec_opt   = (isset($this->phrases["mile"])) ? $this->phrases["mile"] : "Mile";

                        $distance_type_options = array(
														'Kilometer' => $first_opt, 
														'Mile' 		=> $sec_opt
													   );

                        $distance_type_selected = set_value('distance_type', (isset($site_settings->distance_type) ? $site_settings->distance_type : ''));

                         echo form_dropdown('distance_type',$distance_type_options,$distance_type_selected,'class = "chzn-select"');?>
                  </div>
				  <?php } ?>

                  <div class="form-group">
                     <label><?php if(isset($this->phrases["currency symbol"])) echo $this->phrases["currency symbol"]; else echo "Currency Symbol";?></label>
                     <input type="text" name="currency_symbol"  value="<?php echo set_value('currency_symbol', (isset($site_settings->currency_symbol)) ? $site_settings->currency_symbol : '');?>"/>
                     <?php echo form_error('currency_symbol');?>				  
                  </div>

                  <?php if($site_theme == 'vehicle') { ?>
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["cost for meet & greet"])) echo $this->phrases["cost for meet & greet"]; else echo "Cost for Meet & Greet";?></label>					 
                     <input type="text" name="cost_for_meet_greet"   value="<?php echo set_value('cost_for_meet_greet', (isset($site_settings->cost_for_meet_greet)) ? $site_settings->cost_for_meet_greet : '');?>"/>
                     <?php echo form_error('cost_for_meet_greet');?>				  
                  </div>
				  <?php } ?>

			   <div class="form-group">   
				  <label><?php if(isset($this->phrases["rights reserved content"])) echo $this->phrases["rights reserved content"]; else echo "Rights Reserved Content";?></label>
				  <input type="text" name="rights_reserved_content" id="rights_reserved_content" value="<?php echo set_value('rights_reserved_content', (isset($site_settings->rights_reserved_content)) ? $site_settings->rights_reserved_content : '');?>"/>
					<?php echo form_error('rights_reserved_content'); ?>
			   </div>

			   <div class="form-group">
                 <label><?php if(isset($this->phrases["site logo"])) echo $this->phrases["site logo"]; else echo "Site Logo";?></label>
                  <input name="userfile" type="file" id="image" title="<?php if(isset($this->phrases["site logo"])) echo $this->phrases["site logo"]; else echo "Site Logo";?>"  onchange="readURL(this, 'site_logo')" >
				  <?php echo form_error('userfile');?>
                  <br/>
                  <?php 
                     $src = "";
                     $style="display:none;";

                     if(isset($site_settings->site_logo) && file_exists($site_theme.'/assets/system_design/images/'.$site_settings->site_logo)) {
						$src = base_url().$site_theme.'/'."assets/system_design/images/".$site_settings->site_logo;
                     	$style="";
                     }
                     ?>
                  <img id="site_logo" src="<?php echo $src;?>" height="120" style="<?php echo $style;?>" />
               </div>
			   
			   <div class="form-group">
                 <label><?php if(isset($this->phrases["home page banner"])) echo $this->phrases["home page banner"]; else echo "Home page banner";?></label>
                  <input name="homepage_banner" type="file" id="homepage_banner" title="<?php if(isset($this->phrases["home page banner"])) echo $this->phrases["home page banner"]; else echo "Home page banner";?>"  onchange="readURL(this, 'homepage_banner_preview')" >
				  <?php echo form_error('homepage_banner');?>
                  <br/>
                  <?php 
                     $src = "";
                     $style="display:none;";

                     if(isset($site_settings->homepage_banner) && file_exists($site_theme.'/assets/system_design/images/'.$site_settings->homepage_banner)) {
						$src = base_url().$site_theme.'/'."assets/system_design/images/".$site_settings->homepage_banner;
                     	$style="";
                     }
                     ?>
                  <img id="homepage_banner_preview" src="<?php echo $src;?>" height="120" style="<?php echo $style;?>" />
               </div>
			   
			   <div class="form-group">
                 <label><?php if(isset($this->phrases["search page banner"])) echo $this->phrases["search page banner"]; else echo "Search page banner";?></label>
                  <input name="search_banner" type="file" id="search_banner" title="<?php if(isset($this->phrases["search page banner"])) echo $this->phrases["search page banner"]; else echo "Search page banner";?>"  onchange="readURL(this, 'search_banner_preview')" >
				  <?php echo form_error('search_banner');?>
                  <br/>
                  <?php 
                     $src = "";
                     $style="display:none;";

                     if(isset($site_settings->search_banner) && file_exists($site_theme.'/assets/system_design/images/'.$site_settings->search_banner)) {
						$src = base_url().$site_theme.'/'."assets/system_design/images/".$site_settings->search_banner;
                     	$style="";
                     }
                     ?>
                  <img id="search_banner_preview" src="<?php echo $src;?>" height="120" style="<?php echo $style;?>" />
               </div>
			   
			   <div class="form-group">
                 <label><?php if(isset($this->phrases["support ticket banner"])) echo $this->phrases["support ticket banner"]; else echo "Support Ticket Banner";?></label>
                  <input name="support_ticket_banner" type="file" id="support_ticket_banner" title="<?php if(isset($this->phrases["home page banner"])) echo $this->phrases["home page banner"]; else echo "Home page banner";?>"  onchange="readURL(this, 'support_ticket_banner_preview')" >
				  <?php echo form_error('support_ticket_banner');?>
                  <br/>
                  <?php 
                     $src = "";
                     $style="display:none;";

                     if(isset($site_settings->support_ticket_banner) && file_exists($site_theme.'/assets/system_design/images/'.$site_settings->support_ticket_banner)) {
						$src = base_url().$site_theme.'/'."assets/system_design/images/".$site_settings->support_ticket_banner;
                     	$style="";
                     }
                     ?>
                  <img id="support_ticket_banner_preview" src="<?php echo $src;?>" height="120" style="<?php echo $style;?>" />
               </div>
			   
			   <div class="form-group">   
				  <label><?php if(isset($this->phrases["google_plus"])) echo $this->phrases["google_plus"]; else echo "Google+";?></label>
				  <input type="text" name="google_plus" id="google_plus" value="<?php echo set_value('google_plus', (isset($site_settings->google_plus)) ? $site_settings->google_plus : '');?>"/>
					<?php echo form_error('google_plus'); ?>
			   </div>
			   <div class="form-group">   
				  <label><?php if(isset($this->phrases["pinterest"])) echo $this->phrases["pinterest"]; else echo "Pinterest";?></label>
				  <input type="text" name="pinterest" id="pinterest" value="<?php echo set_value('pinterest', (isset($site_settings->pinterest)) ? $site_settings->pinterest : '');?>"/>
					<?php echo form_error('pinterest'); ?>
			   </div>
			   
			   <div class="form-group">
                     <label><?php if(isset($this->phrases["FAQ page"])) echo $this->phrases["FAQ page"]; else echo "FAQ Page";?></label>
                     <?php 
					 $faq_page_selected = '';
					 if(isset($_POST['submit'])) 
						 $faq_page_selected = $this->input->post('faq_page');
					 elseif(isset($site_settings->faq_page))
						$faq_page_selected = $site_settings->faq_page;
					$faq_pages_array = array();
					foreach($faq_pages as $fpage)
					$faq_pages_array[$fpage->page_id] = $fpage->page_title;					
					 echo form_dropdown('faq_page',$faq_pages_array,$faq_page_selected,'class = "chzn-select"');?>
                     <?php echo form_error('faq_page');?>
                  </div>
				  
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["why us"])) echo $this->phrases["why us"]; else echo "Why Us";?></label>
                     <textarea name="whyus" id="whyus" class="editor"><?php echo set_value('whyus', (isset($site_settings->whyus)) ? $site_settings->whyus : '');?></textarea>   
                     <?php echo form_error('whyus');?>
                  </div>
				  
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["about company"])) echo $this->phrases["about company"]; else echo "About Company";?></label>
                     <textarea name="about_company" id="about_company" class="editor"><?php echo set_value('about_company', (isset($site_settings->about_company)) ? $site_settings->about_company : '');?></textarea>   
                     <?php echo form_error('about_company');?>
                  </div>
				  
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["support ticket"])) echo $this->phrases["support ticket"]; else echo "Support Ticket";?></label>
                     <textarea name="support_ticket"><?php echo set_value('support_ticket', (isset($site_settings->support_ticket)) ? $site_settings->support_ticket : '');?></textarea>   
                     <?php echo form_error('support_ticket');?>
                  </div>
				  
				  <div class="form-group">
                     <label><?php if(isset($this->phrases["live chat"])) echo $this->phrases["live chat"]; else echo "Live Chat";?></label>
                     <textarea name="live_chat"><?php echo set_value('live_chat', (isset($site_settings->live_chat)) ? $site_settings->live_chat : '');?></textarea>   
                     <?php echo form_error('live_chat');?>
                  </div>
				  
				  
				  
				  

				 <input type="hidden" name="update_rec_id" value="<?php if(isset($site_settings->id)) echo $site_settings->id;?>" />

			</div>
			<div class="form-group">	  
					<input type="submit" name="submit" value="<?php if(isset($this->phrases["update"])) echo $this->phrases["update"]; else echo "Update";?>" class="btn btn-success">
				</div>
				</form>

		  </div>
      </div>
    </div>
  </div>
</section>

<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>

<script src="http://maps.google.com/maps/api/js?v=3.13&sensor=false&libraries=places" ></script>
<script>
function callAutocompleteAddress()
{
	var selected_country = 'IND';
	//console.log(selected_country)
	options = {
	  language: 'en-GB',
	  types: ['(regions)'],
	  componentRestrictions: { country: selected_country }
	}
	input = $('#city')
	var autocomplete = new google.maps.places.Autocomplete(input[0], options);
	
	/*We can use in this way also
	autocomplete.addListener('place_changed', function(){
		//Code here
	});
	*/
	google.maps.event.addListener(autocomplete, 'place_changed', function() {		  
	  place = autocomplete.getPlace()
	  if (place.address_components)
		city = place.address_components[0] && place.address_components[0].long_name || '';
	//console.log(place.address_components.length);
	//console.log(place.geometry.location);
		input.blur()
		//setTimeout(input.val(city), 10);
		input.val(city);
		
		var length = place.address_components.length;
		if(length == 3)
		{
			//1-state, 2-COuntry
			var state = place.address_components[1] && place.address_components[1].long_name || '';
			$('#state').val(state);
					
			var country = place.address_components[2] && place.address_components[2].long_name || '';
			$('#country').val(country);
		}
		else if(length == 5)
		{
			//1-District, 2-State, 3-COuntry, 4-ZIP
			var state = place.address_components[2] && place.address_components[2].long_name || '';
			$('#state').val(state);
					
			var country = place.address_components[3] && place.address_components[3].long_name || '';
			$('#country').val(country);
			
			var zip = place.address_components[4] && place.address_components[4].long_name || '';
			$('#zip').val(zip);
		}
		$('#google_address').val(place.formatted_address);
		
		$('#lat').val(place.geometry.location.lat());
		$('#lng').val(place.geometry.location.lng());
	}
	)
}				
//google.maps.event.addDomListener(window, 'load', callAutocomplete);				
</script>
<script>
	(function($,W,D)
   {
      var JQUERY4U = {};

      JQUERY4U.UTIL =
      {
          setupFormValidation: function()
          {

			//Additional Methods
                 $.validator.addMethod("phoneNumber", function(uid, element) {
                     return (this.optional(element) || uid.match(/^([0-9 +-]*)$/));
                 }, "<?php if(isset($this->phrases["please enter valid number"])) echo $this->phrases["please enter valid number"]; else echo "Please enter valid number";?>.");

                 $.validator.addMethod("proper_value", function(uid, element) {
					return (this.optional(element) || uid.match(/^((([0-9]*)[\.](([0-9]{1})|([0-9]{2})))|([0-9]*))$/));
				}, "<?php if(isset($this->phrases["please enter valid value"])) echo $this->phrases["please enter valid value"]; else echo "Please enter valid value";?>.");

			/* Change Password form validation rules */
              $("#site_settings_form").validate({
                  rules: {
                site_title: {
                          required: true      
                      },
                address: {
					 required: true
				 },
				 city: {
					 required: true
				 },
				 state: {
					 required: true
				 },
				 country: {
					 required: true
				 },
				 zip: {
					 required: true
				 },
				 phone: {
					 required: true,
					 phoneNumber: true
				 },
				 land_line: {
					 phoneNumber: true
				 },
				portal_email: {
					  required: true,
					  email: true
					},
				"currency_symbol": {
					  required: true
					},
				cost_for_meet_greet: {
					  required: true, 
					  proper_value: true
					},
   				rights_reserved_content: {
                          required: true
                    },
				userfile: {
						extension: "png|jpg|jpeg"
					}
                  },

				messages: {
					site_title: {
							  required: "<?php if(isset($this->phrases["please enter your site title"])) echo $this->phrases["please enter your site title"]; else echo "Please enter your Site Title";?>."
						  },
					address: {
						 required: "<?php if(isset($this->phrases["please enter address"])) echo $this->phrases["please enter address"]; else echo "Please enter Address";?>."
					 },
					 city: {
						 required: "<?php if(isset($this->phrases["please enter city"])) echo $this->phrases["please enter city"]; else echo "Please enter City";?>."
					 },
					 state: {
						 required: "<?php if(isset($this->phrases["please enter state"])) echo $this->phrases["please enter state"]; else echo "Please enter State";?>."
					 },
					 country: {
						 required: "<?php if(isset($this->phrases["please enter country"])) echo $this->phrases["please enter country"]; else echo "Please enter Country";?>."
					 },
					 zip: {
						 required: "<?php if(isset($this->phrases["please enter zip code"])) echo $this->phrases["please enter zip code"]; else echo "Please enter Zip code";?>."
					 },
					 phone: {
						 required: "<?php if(isset($this->phrases["please enter phone number"])) echo $this->phrases["please enter phone number"]; else echo "Please enter Phone number";?>."
					 },
					portal_email: {
						 required: "<?php if(isset($this->phrases["please enter your portal email"])) echo $this->phrases["please enter your portal email"]; else echo "Please enter your Portal Email";?>."
					},
					"currency_symbol": {
						  required: "<?php if(isset($this->phrases["please enter currency symbol"])) echo $this->phrases["please enter currency symbol"]; else echo "Please enter Currency Symbol";?>."
					},
					cost_for_meet_greet: {
						  required: "<?php if(isset($this->phrases["please enter cost for meet & greet"])) echo $this->phrases["please enter cost for meet & greet"]; else echo "Please enter cost for Meet & Greet";?>."
					},
					rights_reserved_content: {
						required: "<?php if(isset($this->phrases["please enter content for rights reserved"])) echo $this->phrases["please enter content for rights reserved"]; else echo "Please enter content for Rights Reserved";?>."
					},
					userfile: {
						extension: "<?php if(isset($this->phrases["WORDS"])) echo $this->phrases["please upload your site logo with the extension jpg|jpeg|png"]; else echo "Please upload your Site Logo with the extension jpg|jpeg|png";?>."
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


   /* Read File Input */
   function readURL(input, id) {
   
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {

            input.style.width = '100%';
			$('#'+id)
                    .attr('src', e.target.result);
			$('#'+id).fadeIn();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
   

   </script>
