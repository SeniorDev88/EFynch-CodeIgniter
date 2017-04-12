<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
<title>Efynch</title>
<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="all-ie-only.css" />
<![endif]-->
<!--[if !IE]>
	<link rel="stylesheet" type="text/css" href="not-ie.css" />
 <!--<![endif]-->

<!-- Bootstrap Version 3.3.6 -->
<link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/datetimepicker.css" rel="stylesheet" type="text/css" />
<!-- Latest compiled and minified JavaScript -->


</head>
<body>
<div class="wrapper">
<section class="right-side divheight">
	<div class="container-right pb40">
    <div class="col-sm-9 pl0 pr0 center-block FN">
				<p class="verysmalp">The following form contract is available for use between the Owner and Contractor for the supply of materials and/or services.  No representation is made that this form contract for the sale and installation of improvements complies with the laws of the applicable jurisdiction.  If this contract is for home improvement work, as that term is defined by the applicable jurisdiction, the contract may require additional notices and information.   Remember, a contract is a legally binding document so it is important to understand what you are signing. Consult an attorney before signing it. </p>

			<div class="col-xs-12"><h1>CONTRACT FOR IMPROVEMENTS</h1></div>
			<div class="col-xs-12 pt20 pb20">
				<p class="mb20">This agreement is made  on <strong><?php echo date('m/d/Y'); ?></strong> and between:</p>

				<p class="termhead">(Owner)</p>
				<p class="mb20"><?php echo $ownerDets['firstname']." ".$ownerDets['lastname']; ?></br>
					<?php echo nl2br($ownerDets['address']); ?></br>
					<?php echo $ownerDets['state'] ?> <?php echo $ownerDets['zip'] ?></br>
					Ph : <?php echo $ownerDets['phone'] ?> </br>
					Email : <?php echo $ownerDets['email'] ?>
				</p>
				<p class="mb10">and</p>
				<p class="termhead">(CONTRACTOR)</p>
				<p class="mb20"><?php echo $userDets['firstname']." ".$userDets['lastname']; ?></br>
					<?php echo nl2br($userDets['address']); ?></br>
					<?php echo $userDets['state'] ?> <?php echo $userDets['zip'] ?></br>
					Ph : <?php echo $userDets['phone'] ?> </br>
					Email : <?php echo $userDets['email'] ?>
				</p>

				<p class="mb20">Address of Improvement: <?php echo nl2br($jobDets['address']); ?></p>

				<p class="mb20">The purpose of this Agreement is to establish terms and information for the services to be performed as described below.</p>

				<p class="mb20">Basic Description of work (“Work”):</p>
				<p><?php echo nl2br($jobDets['jobdescription']); ?></p><br /><br />
				<i class="tc">Use Additional Attachments or Exhibits Where Appropriate</i>


				<p class="mb20">Detailed Description of Work, including materials, permits and there relevant information that is included in this Agreement: </p>
				<p><?php echo ($bid['description'] != "") ? nl2br($bid['description']) : '--'; ?></p><br /><br />
				<i class="tc">Use Additional Attachments or Exhibits Where Appropriate</i>

				<p class="mb20">If additional materials and/ or time is required, the rate for these material shall be: <strong>$<?php echo number_format($bid['additionalamount'],2) ?></strong> per hour.</p>

				<p class="mb20">The Work shall commence on : <strong><?php echo ($bid['startdate'] != "" and $bid['startdate'] != "0000-00-00") ? date('m/d/Y',strtotime($bid['startdate'])) : '--'; ?></strong> and is expected to take <strong><?php echo ($bid['exptime']) ? $bid['exptime'] : '--' ?></strong> <?php if($bid['exptype'] == 'h') { echo 'Hours' ;} else if($bid['exptype'] == 'h'){ echo 'Weeks'; }else if($bid['exptype'] == 'm'){ echo 'Months'; }  ?> but may take up to <strong><?php echo ($bid['maxtime'] != "") ? $bid['maxtime'] : '--' ?></strong>.</p>

				<p class="mb20">In exchange for the Work, the Owner hereby agrees to pay the Contractor $<strong><?php echo number_format($bid['bidamount'],2) ?></strong>  (“Contract Price”).   The Contract Price is not due Contractor until the Work is completed to the satisfaction of the Owner.</p>

				<p class="mb20">The Owner has chosen to deposit the Contract Price with EFynch.com after the execution of the contract between Owner and Contractor.   The deposit may not be made prior to or at the time of the execution.  The deposit must be made after each party has executed the contract and prior to the start of the Work.  EFynch.com will hold the Contract Price and pay Contractor the same only upon the Owner’s acceptance of the Work and instruction to EFynch.com to pay Contractor. EFynch.com is NOT an escrow agent. Rather, as part of the web-based solution to connect Owners and Contractors, EFynch.com offers this payment processing service to assist the Owner with processing the Contract Price, including the ability to use credit cards and other forms of payment with ease. EFynch.com will only release the funds at the express direction of the Owner or Court of Law, if applicable.  Upon the Owner’s or, if applicable, Court’s direction, EFynch.com will cause the Contract Price held to be released accordingly.   Contractor and Owner agree that in the event that there is a dispute between them regarding the release of the Contract Price or the Work, each shall hold EFynch.com harmless.  Contractor and Owner, however, shall not be precluded from asserting any other legal or equitable remedy against each other to enforce this contract.</p>

				<p class="termhead">JURISDICTIONAL NOTICES</p>

				<i class="tc">Use Additional Attachments or Exhibits Where Appropriate</i>

				<div class="clearfix mb30"></div>

				<p class="termhead">MARYLAND HOME IMPROVEMENT JURISDICTIONAL NOTICES TO OWNER:</p>
				<p class="mb20">In the State of Maryland, if this Contract is for home improvement services, as that term is defined under applicable law, the Contractor is required to hold a valid and current Maryland Home Improvement License and provide the same in the contract. Each contractor must be licensed by the Maryland Home Improvement Commission (MHIC or Commission), and anyone may ask the MHIC about a contractor. If at any time you have questions you may contact the Maryland Home Improvement Commission at 410-230-6309 and 1-888-218-5925 or write to MHIC 500 North Calvert Street, Baltimore, Maryland 21201</p>
				<p class="mb20">MHIC regulations require that the contract contain the following notice:</p>
				<ul>
					<ol>Formal mediation of disputes between homeowners and contractors is available through the Commission;</ol>
					<ol>The Commission administers the Guaranty Fund, which may compensate homeowners for certain actual losses caused by acts or omissions of licensed contractors; and </ol>
					<ol>A homeowner may request that a contractor purchase a performance bond for additional protection against losses not covered by the Guaranty Fund.</ol>
				</ul>

				<p class="mb20">If this contract creates a mortgage or lien against your property to secure payment and may cause a loss of your property if you fail to pay the amount agreed upon. You have the right to consult an attorney. You have the right to rescind this contract within 3 business days after the date you sign it by notifying the contractor in writing that you are rescinding the contract.</p>

				<p class="mb20">By signing below, the Owner and Contractor hereby agree to the terms listed in this contract. Work shall be completed in a timely manner. If any changes or additions are required, the parties agree to use EFynch.com and submit change orders or additional work through EFynch.com.  Owner will deposit the cost for any change orders or additional work with EFynch.com, which price shall only be released by EFynch.com upon completion of the same by Contractor and the express instruction by Owner to release the funds.</p>

				<p class="mb20">Both parties acknowledge that EFynch.com is a web and app based software system which facilities the interaction between Owners and Contractors. EFynch.com in no way has expressed or supplied recommendations for any home services or any Contractors.</p>

				<p class="mt30 mb80">Electronically Signed:</p>



			</div>
		</div>
	</div>
</section>
</div>




</body>
</html>
