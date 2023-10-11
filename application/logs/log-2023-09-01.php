<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-09-01 18:12:22 --> Query error: Unknown column 'product_category_id' in 'field list' - Invalid query: INSERT INTO `demo` (`demo_id`, `product_category_id`, `requesting_employee_name`, `nature_of_demo`, `remarks1`, `lead_id`, `opportunity_id`, `product_id`, `demo_machine`, `demo_product_id`, `start_date`, `end_date`, `event_details`, `units_for_display`, `name_of_institute`, `contact_detail`, `name_of_contact_institute`, `key_decision_makers`, `name_of_units_demonstrated`, `file_path`, `file_name`, `letter_file_path`, `letter_file_name`, `created_by`, `created_time`) VALUES ('', '2', 'Sony Ponnanna', 'marketing', NULL, '', '', NULL, '', '', '2023-09-04 08:5', '2023-09-04 09:30', 'AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc', NULL, '', '', '', '', 'AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc', '[\"https:\\/\\/www.skanray-access.com\\/iCRM\\/uploads\\/demo_image\\/MY0723P01281693572142.pdf\"]', '[\"MY0723P01281693572142.pdf\"]', '[\"https:\\/\\/www.skanray-access.com\\/iCRM\\/uploads\\/demo_image\\/pr14000905391693572142.pdf\"]', '[\"pr14000905391693572142.pdf\"]', '939', '2023-09-01 18:12:22')
ERROR - 2023-09-01 18:12:22 --> Query error: Unknown column 'd.product_category_id' in 'on clause' - Invalid query: SELECT `d`.*, `u`.`email_id`, concat(u.first_name, " ", u.last_name) as user, `u`.`user_id`, `d`.`start_date`, `d`.`end_date`, `o`.`opportunity_id`, CONCAT(p.name, "(", `p`.`description`, ")") as ProductName, `c`.`name` as `customer_name`, `dpd`.`serial_number`, `dpd`.`location`, `pc`.`name` as `category_name`, `l`.`lead_id`, `l`.`lead_number`, `d`.`serial_number` as `snumber`
FROM `demo` `d`
INNER JOIN `opportunity` `o` ON `o`.`opportunity_id` = `d`.`opportunity_id`
INNER JOIN `lead` `l` ON `l`.`lead_id` = `o`.`lead_id`
INNER JOIN `customer` `c` ON `c`.`customer_id` = `l`.`customer_id`
INNER JOIN `user` `u` ON `u`.`user_id` = `l`.`user_id`
JOIN `opportunity_product` `op` ON `op`.`opportunity_id` = `o`.`opportunity_id`
INNER JOIN `product` `p` ON `p`.`product_id` = `op`.`product_id`
INNER JOIN `product_category` `pc` ON `pc`.`category_id` = `d`.`product_category_id`
INNER JOIN `demo_product_details` `dpd` ON `dpd`.`demo_product_id` = `d`.`demo_machine`
WHERE `d`.`demo_id` =0
GROUP BY `d`.`demo_id`
ERROR - 2023-09-01 18:12:22 --> Severity: Error --> Call to a member function result_array() on a non-object /var/www/iCRM/application/helpers/mahesh_fun_helper.php 3176
ERROR - 2023-09-01 18:12:34 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:34 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:34 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:34 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:34 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:34 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:50 --> Severity: Warning --> in_array() expects parameter 2 to be array, null given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:50 --> Severity: Warning --> in_array() expects parameter 2 to be array, null given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:50 --> Severity: Warning --> in_array() expects parameter 2 to be array, null given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:50 --> Severity: Warning --> in_array() expects parameter 2 to be array, null given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:50 --> Severity: Warning --> in_array() expects parameter 2 to be array, null given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 18:12:50 --> Severity: Warning --> in_array() expects parameter 2 to be array, null given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 13:07:34 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 176
ERROR - 2023-09-01 13:07:34 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 204
ERROR - 2023-09-01 13:07:34 --> 404 Page Not Found: Corn/get_live_location_details
ERROR - 2023-09-01 13:11:33 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 176
ERROR - 2023-09-01 13:11:33 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 204
ERROR - 2023-09-01 13:11:33 --> 404 Page Not Found: Get_mobile_version/index
ERROR - 2023-09-01 18:47:50 --> Query error: Column 'product_id' cannot be null - Invalid query: INSERT INTO `demo` (`demo_id`, `product_category_id`, `requesting_employee_name`, `nature_of_demo`, `remarks1`, `lead_id`, `opportunity_id`, `product_id`, `demo_machine`, `demo_product_id`, `start_date`, `end_date`, `event_details`, `units_for_display`, `name_of_institute`, `contact_detail`, `name_of_contact_institute`, `key_decision_makers`, `name_of_units_demonstrated`, `file_path`, `file_name`, `letter_file_path`, `letter_file_name`, `created_by`, `created_time`) VALUES ('', '2', 'Sony Ponnanna', 'marketing', NULL, '', '', NULL, '', '', '2023-09-03 10:0', '2023-09-03 11:45', 'TEST01SEP2023', NULL, '', '', '', '', 'TEST01SEP2023', '[\"https:\\/\\/www.skanray-access.com\\/iCRM\\/uploads\\/demo_image\\/AccpricelistControlled1693574270.pdf\"]', '[\"AccpricelistControlled1693574270.pdf\"]', '[\"https:\\/\\/www.skanray-access.com\\/iCRM\\/uploads\\/demo_image\\/pr14000905391693574270.pdf\"]', '[\"pr14000905391693574270.pdf\"]', '939', '2023-09-01 18:47:50')
ERROR - 2023-09-01 13:19:27 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 176
ERROR - 2023-09-01 13:19:27 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 204
ERROR - 2023-09-01 13:19:27 --> 404 Page Not Found: Get_mobile_version/index
ERROR - 2023-09-01 19:16:03 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=20.90106460,74.78235990&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 13:46:19 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 176
ERROR - 2023-09-01 13:46:19 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 204
ERROR - 2023-09-01 13:46:19 --> 404 Page Not Found: Get_mobile_version/index
ERROR - 2023-09-01 13:47:46 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 176
ERROR - 2023-09-01 13:47:46 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 204
ERROR - 2023-09-01 13:47:46 --> 404 Page Not Found: Get_mobile_version/index
ERROR - 2023-09-01 19:40:53 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=30.62132230,76.82506920&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 19:40:53 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=19.87263050,75.37067690&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 20:09:48 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=26.26543150,73.00678080&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 20:09:49 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=19.96222990,79.28985180&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 20:20:35 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=11.84868940,75.65917490&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 20:20:37 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=22.54110330,88.34705670&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 20:41:26 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=11.85004150,75.65844770&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 20:41:27 --> Severity: Warning --> file_get_contents(https://maps.googleapis.com/maps/api/geocode/json?latlng=19.87745020,75.35460900&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc): failed to open stream: HTTP request failed! HTTP/1.0 500 Internal Server Error
 /var/www/iCRM/application/controllers/Cron.php 529
ERROR - 2023-09-01 20:42:37 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 20:42:37 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 20:42:37 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 20:42:37 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 20:42:37 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 20:42:37 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 21:25:27 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:29:24 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:33:36 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:34:30 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:34:31 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:39:29 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:42:01 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:42:02 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:44:42 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 21:47:35 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/lead/modals/oppo_modal.php 166
ERROR - 2023-09-01 21:47:35 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/lead/modals/oppo_modal.php 202
ERROR - 2023-09-01 21:48:25 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 21:48:25 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 21:48:25 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 21:48:25 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 21:48:25 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 21:48:25 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 22:09:53 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/lead/modals/oppo_modal.php 166
ERROR - 2023-09-01 22:09:53 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/lead/modals/oppo_modal.php 202
ERROR - 2023-09-01 22:09:53 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/lead/modals/oppo_modal.php 166
ERROR - 2023-09-01 22:09:53 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/lead/modals/oppo_modal.php 202
ERROR - 2023-09-01 22:09:53 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/lead/modals/oppo_modal.php 166
ERROR - 2023-09-01 22:09:53 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/lead/modals/oppo_modal.php 202
ERROR - 2023-09-01 17:06:01 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 176
ERROR - 2023-09-01 17:06:01 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. /var/www/iCRM/system/core/Log.php 204
ERROR - 2023-09-01 17:06:01 --> 404 Page Not Found: Get_mobile_version/index
ERROR - 2023-09-01 23:38:05 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:05 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:05 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:05 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:05 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:05 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:12 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:12 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:12 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:12 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:12 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:38:12 --> Severity: Warning --> in_array() expects parameter 2 to be array, string given /var/www/iCRM/application/views/lead/opportunityView.php 37
ERROR - 2023-09-01 23:43:37 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 23:46:52 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 23:49:24 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 23:50:40 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 23:51:49 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 23:53:56 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 23:58:15 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
ERROR - 2023-09-01 23:59:41 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/iCRM/application/views/calendar/visitView.php 75
