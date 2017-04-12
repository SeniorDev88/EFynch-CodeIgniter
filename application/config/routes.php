<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'Webfrontend/index';

/**API starts**/
$route['api'] = 'Api/index';
/**API ends*/

$route['makepayment/(:any)'] 	= 'Apipayment/makepayment/$1';
$route['bidconfirm/(:any)'] 		= 'Apipayment/bidconfirm/$1';
$route['apipaymentsuccess/(:any)'] 	= 'Apipayment/apipaymentsuccess/$1';
$route['apipaymentsuccess/(:any)/(:any)'] 	= 'Apipayment/apipaymentsuccess/$1/$2';
$route['apipaymentfail/(:any)'] 	= 'Apipayment/apipaymentfail/$1';

$route['contractagree/(:any)/(:any)'] 	= 'Api/jobcontract/$1/$2';


/* FrontPages*/

$route['info'] 			= 'Webfrontend/info';
$route['community'] 	= 'Webfrontend/community';
$route['contactus'] 	= 'Webfrontend/contactus';

$route['termsandcondition'] 	= 'Webfrontend/termsandcondition';
$route['efynchtermsandcondition'] 	= 'Webfrontend/efynchtermsandcondition';




/** Web App*/
$route['app'] 			= 'Frontend/index';
$route['send_notification'] 			= 'Frontend/notification';
$route['login'] 			= 'Frontend/login';
$route['logout'] 			= 'Frontend/logout';
$route['primaryrole'] 			= 'Frontend/primaryrole';
$route['homeownerregistration'] 			= 'Frontend/homeownerregistration';
$route['contractorregistration'] 			= 'Frontend/contractorregistration';
$route['myprofile'] 	= 'Frontend/myprofile';
//Contractor profile
$route['profile/(:any)'] 	= 'Frontend/profile/$1';
$route['changepassword'] 	= 'Frontend/changepassword';
$route['dashboard'] 	= 'Frontend/dashboard';
$route['checkuserexists'] 	= 'Frontend/checkemailexists';
$route['verifyemail'] 	= 'Frontend/verifyemail';
$route['resendemailverification'] 	= 'Frontend/resendemailverification';

$route['checkexistinguseremail'] 	= 'Frontend/checkexistinguseremail';


$route['owner/(:any)'] 		= 'Frontend/ownerbidding/$1';
$route['worklist/(:any)'] 	= 'Frontend/biddingworkdetails/$1';
$route['rating/(:any)/(:any)'] 	= 'Frontend/rating/$1/$2';
$route['view-rating/(:any)/(:any)'] 	= 'Frontend/view_rating/$1/$2';
$route['private-rating/(:any)/(:any)'] 	= 'Frontend/private_rating/$1/$2';
$route['save-rating'] 	= 'Frontend/save_rating';

$route['contractor/(:any)'] 	= 'Frontend/contractorbidding/$1';
$route['contractorworking'] 	= 'Frontend/contractorworking';
$route['contractorcompleted'] 	= 'Frontend/contractorcompleted';
$route['contractorbiddingworkdetails'] 	= 'Frontend/contractorbiddingworkdetails';
$route['contractorworkdetails'] 	= 'Frontend/contractorworkdetails';
$route['biddetails'] 	= 'Frontend/biddetails';

$route['forgotpassword'] 	= 'Frontend/forgotpassword';
$route['recoverpassword/(:any)'] 	= 'Frontend/recoverpassword/$1';

$route['editjobpost/(:any)'] 	= 'Jobs/editJobPost/$1';
$route['deleteJobPost'] 		= 'Jobs/deleteJobPost';
$route['postjob'] 	= 'Jobs/postjob';
$route['getcity'] 	= 'Jobs/getcity';
$route['docupload'] 	= 'Jobs/docupload';
$route['mybids'] 		= 'Jobs/myBids';
$route['mybids/(:any)'] 	= 'Jobs/jobBids/$1';
$route['bidjob/(:any)'] 	= 'Jobs/bidJob/$1';
$route['biddedjob/(:any)'] 	= 'Jobs/biddedjob/$1';
$route['bidnow/(:any)'] 	= 'Jobs/createBid/$1';
$route['editbid/(:any)'] 	= 'Jobs/editBid/$1';
$route['deleteBid'] 		= 'Jobs/deleteBid';

$route['favouriteBid'] 		= 'Jobs/favouriteBid';
$route['awardwork/(:any)/(:any)'] 		= 'Jobs/awardWork/$1/$2';
$route['agreecontract/(:any)'] 	= 'Jobs/agreeContract/$1';
$route['reviewcontract/(:any)'] 	= 'Jobs/reviewContract/$1';
$route['agreeTerms'] 	= 'Jobs/agreeTerms';
$route['completeJob'] 	= 'Jobs/completeJob';
$route['showbidinfo'] 	= 'Jobs/showbidinfo';
$route['cancelcontract'] 	= 'Jobs/cancelcontract';


$route['confirmpayment/(:any)'] 	= 'Apipayment/confirmpayment/$1';

$route['messages'] 	= 'Messages/messages';
$route['messages/(:any)'] 	= 'Messages/messagedetails/$1';
$route['messages/(:any)/(:any)'] 	= 'Messages/messagedetails/$1/$2';
$route['notification'] 	= 'Messages/notification';
$route['notification/(:any)'] 	= 'Messages/notification/$1';
$route['sendmessage'] 	= 'Messages/sendmessage';
$route['checknewmessage'] 	= 'Messages/checknewmessage';
$route['getmessage'] 	= 'Messages/getmessage';
$route['accountbalance'] 	= 'Messages/accountbalance';
$route['accountbalance/(:any)'] 	= 'Messages/accountbalance/$1';
$route['paymentdetails'] 	= 'Messages/paymentdetails';


$route['download/(:any)'] 	= 'Jobs/download/$1';
$route['download/(:any)/(:any)'] 	= 'Jobs/download/$1/$2';


$route['search'] 	= 'Jobs/search';
$route['admin'] 	= 'admin/login';
//$route['login'] = "admin/login";
$route['logout-admin'] = "admin/login/logout";

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;