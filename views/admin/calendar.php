<div class="col-md-12">
<h2><?php if(isset($this->phrases["calendar"])) echo $this->phrases["calendar"]; else echo "Calendar";?></h2>
           <div class="responsive-calendar">
                      <div class="controls"> <a class="pull-left" data-go="prev">
                        <div class="btn btn-primary"><?php if(isset($this->phrases["prev"])) echo $this->phrases["prev"]; else echo "Prev";?></div>
                        </a>
                    <h4><span data-head-year></span> <span data-head-month></span></h4>
                    <a class="pull-right" data-go="next">
                        <div class="btn btn-primary"><?php if(isset($this->phrases["next"])) echo $this->phrases["next"]; else echo "Next";?></div>
                        </a> </div>
                      <hr/>
                      <div class="day-headers">
                    <div class="day header"><?php if(isset($this->phrases["mon"])) echo $this->phrases["mon"]; else echo "Mon";?></div>
                    <div class="day header"><?php if(isset($this->phrases["tue"])) echo $this->phrases["tue"]; else echo "Tue";?></div>
                    <div class="day header"><?php if(isset($this->phrases["wed"])) echo $this->phrases["wed"]; else echo "Wed";?></div>
                    <div class="day header"><?php if(isset($this->phrases["thu"])) echo $this->phrases["thu"]; else echo "Thu";?></div>
                    <div class="day header"><?php if(isset($this->phrases["fri"])) echo $this->phrases["fri"]; else echo "Fri";?></div>
                    <div class="day header"><?php if(isset($this->phrases["sat"])) echo $this->phrases["sat"]; else echo "Sat";?></div>
                    <div class="day header"><?php if(isset($this->phrases["sun"])) echo $this->phrases["sun"]; else echo "Sun";?></div>
                  </div>
                      <div class="days" data-group="days"> </div>
                    </div>
        </div>
