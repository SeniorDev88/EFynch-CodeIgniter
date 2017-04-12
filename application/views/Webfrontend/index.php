<?php
$page = 'index';
  ?>

  <section class="banner">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
		<li data-target="#myCarousel" data-slide-to="3"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img src="<?php echo base_url(); ?>img/bannerimage/banner01.jpg" alt="First slide">
        </div>
        <div class="item">
          <img src="<?php echo base_url(); ?>img/bannerimage/banner02.jpg" alt="Second slide">
        </div>
        <div class="item">
          <img src="<?php echo base_url(); ?>img/bannerimage/banner03.jpg" alt="Third slide">
        </div>
		<div class="item">
          <img src="<?php echo base_url(); ?>img/bannerimage/banner04.jpg" alt="Fourth slide">
        </div>
      </div>
    </div>
    <div class="container-fluid hero-text">
      <div class="container">
        <h1>Home Improvement.<br /> On your phone and on your side.</h1>
        <a class="bannerbutton"  href="#homeowner">Homeowner</a>
        <a class="bannerbutton"  href="#contractor">Contractor</a>
      </div>
    </div>
    <div class="curvy-block"></div>
    <a class="mouse-down" href="#whoweare">SCROLL</a>
  </section>
  <section id="onlymob">
    <div class="container p0">
      <h1>Home Improvement.<br /> On your phone and on your side.</h1>
      <a class="bannerbutton"  href="#homeowner">Homeowner</a>
      <a class="bannerbutton"  href="#contractor">Contractor</a>
    </div>
  </section>
  <section class="section-container" id="whoweare">
    <div class="bird-nest"></div>
    <div class="container p0">
        <div class="col-xs-12">
          <h2>Who We Are</h2>
        </div>
        <div class="col-xs-12 WWR-imgfixed hidden-sm hidden-md hidden-lg mb20-xs">
          <img src="<?php echo base_url(); ?>img/whoweare.png"/>
        </div>
        <div class="col-xs-12 col-sm-6">
          <!--<p class="mobimage"><img src="img/whoweare.png" /></p>-->
          <p class="text-content"><strong>EFynch.com</strong> is a Home Improvement Platform which helps to connect homeowners and contractors when projects and work is needed. By providing an efficient and friendly community, we want to empower both sides to help with:</p>
		  <ul>
			<li>
				Collection of bids
			</li>
			<li>
				Reading Reviews
			</li>
			<li>
				Facilitating schedules
			</li>
			<li>
				Performing work
			</li>
			<li>
				Handling payments
			</li>
		  </ul>

          <p class="text-content">We strive to do this efficiently as possible, making most services automated while protecting your privacy.</p>
		  <p class="text-content">There are no sales calls or high pressure tactics. Contractors can forego the strenuous and often expensive sales process, sharing the savings with homeowners.</p>
          <p class="subhead">How We Help:</p>
          <a class="video-thumb mr15" data-video='<iframe width="100%" height="490" src="https://www.youtube.com/embed/wRACV_IS0BI?autoplay=1" frameborder="0" allowfullscreen></iframe>' data-name="Efynch Homeowner Intro"><img src="img/videothumb-homeowner.jpg"/><span>Homeowner</span></a>
          <a class="video-thumb" data-video='<iframe width="100%" height="490" src="https://www.youtube.com/embed/WCe1Y-wk_y8?autoplay=1" frameborder="0" allowfullscreen></iframe>' data-name="Efynch Contractor Intro"><img src="img/videothumb-contractor.jpg"/><span>Contractor</span></a>
        </div>
        <div class="col-xs-12 col-sm-6 WWR-imgfixed hidden-xs">
          <img src="<?php echo base_url(); ?>img/whoweare.png"/>
        </div>
  </div>
  <!--<div class="WWR-imgfixed"><img src="img/whoweare.png" class="FR"/></div>-->
  </section>
  <section class="section-container" id="homeowner">
    <div class="homeownerfixed"><img src="img/homeowner.png" /></div>
    <div class="container p0">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-6">
          <h2>Homeowners</h2>
          <p class="mobimage ml10-sm ml0-xs pb20-xs"><img src="img/homeowner-mob.png" /></p>
          <p class="text-content"><strong>Efynch.com</strong> is here to help Maryland Homeowners. As lifelong property managers and homeowners ourselves, we know the struggle of trying to find the right contractor and collecting bids. We also recognize the comfort that can come when you know “that guy” who is able to help with all the odd jobs and make our homes better. So whether electrical, plumbing, handyman, or even help lifting, assembly, mowing, or digging- EFynch can help you repair and organize your home. We do this all within your custom “Toolbox”, most of the heavy lifting (pun intended) is virtually automated!. . . And it FREE!</p>
          <a class="link-button" href="<?php echo base_url('homeownerregistration'); ?>">Homeowner Registration</a>
        </div>
    </div>
  </section>
  <section id="contractor" class="section-container">
    <div class="container">
      <div class="col-xs-12">
        <h2>Contractors</h2>
      </div>
      <div class="col-xs-12 WWR-imgfixed hidden-sm hidden-md hidden-lg mb20-xs">
        <img src="<?php echo base_url(); ?>img/contractor.png"/>
      </div>
      <div class="col-xs-12 col-sm-6">
          <p class="text-content">As a career long property professionals and the former owner of a construction company, we know how expensive and painstaking the sales process can be. Most sites are only interested in collecting a fee for clicks or leads without any care for your pocketbook- They are nothing more than an overrated and outdated phonebook.</p>
          <p class="text-content">We also know the more you have to pay, the higher you need to charge homeowner and neither of us like that! </p>
		  <p class="text-content"><strong>EFynch</strong> wants to fix this problem by opening our platform 100% free to you. You can browse and bid on real, live projects around your neighborhood. The only time we collect any money is when you do, and only <strong>IF</strong> you do.</p>
		  <p class="strong-light"><strong>*FOR A LIMITED TIME, ALL SUCCESS FEES ARE FREE, EFYNCH BIDDING IS 100% FREE THROUGH THE FALL OF 2016. (* we will email you of any changes well before they happen).</strong></p>
		  <a class="link-button" href="<?php echo base_url('contractorregistration'); ?>">Contractor Registration</a>
      </div>
      <div class="col-xs-12 col-sm-6 WWR-imgfixed hidden-xs">
        <img src="<?php echo base_url(); ?>img/contractor.png"/>
      </div>
    </div>

  </section>
  <section class="section-container" id="help">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12">
          <h2>Trending Projects</h2>
          <p class="text-content">Currently EFynch is open in Maryland for all Home Improvement and repair projects. By registering an account you can post bids, read reviews, schedule and pay for work. We want to be a non-biased resource that helps you gain the understanding and confidence for work being done to your home. All of our information is user generated and verified by various professionals for accuracy and completeness. But it is more than just being about confidence- your home is your castle and we want to help you protect and maintain it.</p>
          <p class="subhead">Below are trending projects in Washington, Baltimore and throughout Maryland</p>
          <div class="row mt20">
            <div class="col-xs-12">
              <div class="col-xs-12 col-sm-4">
                <a class="category-block">
                  <span><img src="<?php echo base_url(); ?>img/category/roofing.jpg"/></span>
                  <p><span class="workicons"><i class="roof"></i></span>Roofing / Gutters</p>
                </a>
              </div>
              <div class="col-xs-12 col-sm-4">
                <a class="category-block">
                  <span><img src="<?php echo base_url(); ?>img/category/painting.jpg"/></span>
                  <p><span class="workicons"><i class="paint"></i></span>Painting</p>
                </a>
              </div>
              <div class="col-xs-12 col-sm-4">
                <a class="category-block">
                  <span><img src="<?php echo base_url(); ?>img/category/hvac.jpg"/></span>
                  <p><span class="workicons"><i class="hvac"></i></span>HVAC</p>
                </a>
              </div>
            </div>
           <?php /* <div class="col-xs-12">
              <a class="viewmore" href="services.php">View Other Projects</a>
            </div> */?>
          </div>
        </div>
      </div>
      <div class="curvy-block-orange"></div>
    </div>
  </section>
  <section class="section-container" id="appstore">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12">
          <h2>Also available in the App Store</h2>
          <div class="col-xs-12">
            <img src="<?php echo base_url(); ?>img/laptop.png"/>
          </div>
          <p class="subhead">Access EFynch straight from your iPhone :-)</p>
          <p class="text-content">EFynch is now available in the App store. Simply take pictures and write a small description of the work to be performed and get back onto your day! Efficiency is key and this is just another way to deliver our awesome Platform!</p>
          <a class="appstore"></a>
        </div>
      </div>
    </div>
  </section>

  <section class="section-container" id="contactus">
    <div class="container">
      <div class="row pt30 pt0-xs">
        <div class="col-xs-12 tc">
          <p class="subhead">Send your inquiries by clicking the following Button</p>
          <a class="viewmore" href="<?php echo base_url('contactus'); ?>">Contact Us</a>
		  <span class="phone_number">(410) 562-9103</span>
        </div>
        <div class="col-xs-12 mt20 tc">
          <a class="facebook" href="https://www.facebook.com/efynch" target="_blank"></a>
          <a class="twitter" href="https://twitter.com/Efynch" target="_blank"></a>
          <!--<a class="linkedin"></a>-->
        </div>
      </div>
    </div>
  </section>
