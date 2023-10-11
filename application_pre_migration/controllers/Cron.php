<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Cron extends CI_controller {

	public function __construct() 
	{
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
		
	}

	public function cron_daily($print_mail=0){

		//send_email('navee.naveen@gmail.com','Testing-Daily Cron','cron test - daily cron'.date('Y m d H:i:s'));


		define('PRINT_MAIL',$print_mail);

		reminder_orderConclusionDatePast();
		escalation1_orderConclusionDatePast();
		escalation2_orderConclusionDatePast();

		reminder_postDemoNotUpdated();
		escalation1_postDemoNotUpdated();
		escalation2_postDemoNotUpdated();

		reminder_postVisitNotUpdated();
		escalation1_postVisitNotUpdated();
		escalation2_postVisitNotUpdated();
	}

	public function cron_5minute($print_mail=0){

		define('PRINT_MAIL',$print_mail);

		//send_email('navee.naveen@gmail.com','Testing-5 minute Cron','cron test - 5 minute cron'.date('Y m d H:i:s'));

		if(date('H') == 7 && date('i') >= 0 && date('i') < 5) 
		{
			//send_email('navee.naveen@gmail.com','Testing-Before daily cron','cron test - before daily cron'.date('Y m d H:i:s'));
			$this->cron_daily();
		}
		notification_leadApproval();
		notification_leadAssigned();
	}

	public function cron_test()
	{
		send_email('navee.naveen@gmail.com','Test','cron test'.date('Y m d H:i:s'));
	}

	public function cron_test1()
	{
		send_email('navee.naveen@gmail.com','Testing-direct cron','cron test - direct'.date('Y m d H:i:s'));
	}
	
}