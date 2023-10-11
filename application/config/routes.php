<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'icrmctrl/home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['login'] = '/login/login';
$route['logout'] = '/login/logout';
$route['home'] = '/icrmctrl/index';
$route['roles'] = '/login/roles';
$route['changePassword'] = '/login/changePassword';
//mahesh 14th july
$route['forgotPassword'] = '/login/forgotPassword';
$route['resetPassword'] = '/login/resetPassword';
$route['resetPasswordAction'] = '/login/resetPasswordAction';



// Admin Pages


$route['company'] = '/admin/company';
$route['company/(:any)'] = '/admin/company/$1';
$route['addCompany'] = '/admin/addCompany';
$route['editCompany/(:any)'] = '/admin/editCompany/$1';
$route['deleteCompany/(:any)'] = '/admin/deleteCompany/$1';
$route['activateCompany/(:any)'] = '/admin/activateCompany/$1';
$route['companyAdd'] = '/admin/companyAdd';
$route['downloadCompany'] = '/admin/downloadCompany';


// Admin User

$route['adminUser'] = '/admin/adminUser';
$route['adminUser/(:any)'] = '/admin/adminUser/$1';
$route['addAdminUser'] = '/admin/addAdminUser';
$route['editAdminUser/(:any)'] = '/admin/editAdminUser/$1';
$route['deleteAdminUser/(:any)'] = '/admin/deleteAdminUser/$1';
$route['activateAdminUser/(:any)'] = '/admin/activateAdminUser/$1';
$route['adminUserAdd'] = '/admin/adminUserAdd';
$route['downloadAdminUser'] = '/admin/downloadAdminUser';

// Admin Email
$route['adminEmail'] = '/admin/adminEmail';
$route['adminUser/(:any)'] = '/admin/adminEmail/$1';
$route['addAdminEmail'] = '/admin/addAdminEmail';
$route['editAdminEmail/(:any)'] = '/admin/editAdminEmail/$1';
$route['deleteAdminEmail/(:any)'] = '/admin/deleteAdminEmail/$1';
$route['adminEmailAdd'] = '/admin/adminEmailAdd';

/* Section - Product, Added By - Suvendu, Date - 14th June 2016, Time - 12:45 PM */
$route['productCategory'] = '/product/category';
$route['productCategory/(:any)'] = '/product/category/$1';
$route['addCategory'] = '/product/addCategory';
$route['editCategory/(:any)'] = '/product/editCategory/$1';
$route['deleteCategory/(:any)'] = '/product/deleteCategory/$1';
$route['activateCategory/(:any)'] = '/product/activateCategory/$1';
$route['productCategoryAdd'] = '/product/categoryAdd';
$route['downloadCategory'] = '/product/downloadCategory';
$route['checkCategoryAvailability'] = '/product/checkCategoryAvailability';

$route['productSubCategory'] = '/product/subCategory';
$route['productSubCategory/(:any)'] = '/product/subCategory/$1';
$route['addSubCategory'] = '/product/addSubCategory';
$route['editSubCategory/(:any)'] = '/product/editSubCategory/$1';
$route['deleteSubCategory/(:any)'] = '/product/deleteSubCategory/$1';
$route['activateSubCategory/(:any)'] = '/product/activateSubCategory/$1';
$route['productSubCategoryAdd'] = '/product/subCategoryAdd';
$route['downloadSubCategory'] = '/product/downloadSubCategory';


$route['materialGroup'] = '/product/group';
$route['materialGroup/(:any)'] = '/product/group/$1';
$route['addGroup'] = '/product/addGroup';
$route['editGroup/(:any)'] = '/product/editGroup/$1';
$route['deleteGroup/(:any)'] = '/product/deleteGroup/$1';
$route['activateGroup/(:any)'] = '/product/activateGroup/$1';
$route['materialGroupAdd'] = '/product/groupAdd';
$route['downloadGroup'] = '/product/downloadGroup';
$route['checkGroupAvailability'] = '/product/checkGroupAvailability';

$route['competitor'] = '/product/competitor';
$route['competitor/(:any)'] = '/product/competitor/$1';
$route['addCompetitor'] = '/product/addCompetitor';
$route['editCompetitor/(:any)'] = '/product/editCompetitor/$1';
$route['deleteCompetitor/(:any)'] = '/product/deleteCompetitor/$1';
$route['activateCompetitor/(:any)'] = '/product/activateCompetitor/$1';
$route['competitorAdd'] = '/product/competitorAdd';
$route['downloadCompetitor'] = '/product/downloadCompetitor';
$route['checkCompetitorAvailability'] = '/product/checkCompetitorAvailability';

#Product
$route['product'] = '/product/product';
$route['addProduct'] = '/product/addProduct';
$route['editProduct/(:any)'] = '/product/editProduct/$1';
$route['deleteProduct/(:any)'] = '/product/deleteProduct/$1';
$route['activateProduct/(:any)'] = '/product/activateProduct/$1';
$route['productAdd'] = '/product/productAdd';
$route['product/(:any)'] = '/product/product/$1';
$route['downloadProduct'] = '/product/downloadProduct';
$route['getProductGroup'] = '/product/getProductGroup';
$route['getProductGroup_for_products'] = '/product/getProductGroup_for_products';
$route['getProduct'] = '/product/getProduct';
$route['checkProductAvailability'] = '/product/checkProductAvailability';

#Demo Product
$route['demoProduct'] = '/product/demoProduct';
$route['addDemoProduct'] = '/product/addDemoProduct';
$route['editDemoProduct/(:any)'] = '/product/editDemoProduct/$1';
$route['deleteDemoProduct/(:any)'] = '/product/deleteDemoProduct/$1';
$route['activateDemoProduct/(:any)'] = '/product/activateDemoProduct/$1';
$route['demoProductAdd'] = '/product/demoProductAdd';
$route['demoProduct/(:any)'] = '/product/demoProduct/$1';
$route['downloadDemoProduct'] = '/product/downloadDemoProduct';
$route['getDemoProduct'] = '/product/getDemoProduct';
$route['checkDemoProductSerialNumberAvailability'] = '/product/checkDemoProductSerialNumberAvailability';

//customer
$route['customer'] = 'customer/customer';
$route['customer/(:any)'] = 'customer/customer/$1';
$route['addCustomer']='customer/addCustomer';
$route['customerAdd']='customer/customerAdd';
$route['editCustomer/(:any)'] = 'customer/editCustomer/$1';
$route['deleteCustomer/(:any)'] = 'customer/deleteCustomer/$1';
$route['activateCustomer/(:any)'] = 'customer/activateCustomer/$1';
$route['downloadCustomer'] = 'customer/downloadCustomer';
$route['getSubCategory'] = 'customer/get_sub_category';
$route['customerInstallationAdd'] = 'customer/customer_installation_add';

//contact
$route['contact'] = 'contact/contact';
$route['contact/(:any)'] = 'contact/contact/$1';
$route['addContact']='contact/addContact';
$route['contactAdd']='contact/contactAdd';
$route['editContact/(:any)'] = 'contact/editContact/$1';
$route['deleteContact/(:any)'] = 'contact/deleteContact/$1';
$route['activateContact/(:any)'] = 'contact/activateContact/$1';
$route['downloadContact'] = 'contact/downloadContact';

//Speciality
$route['speciality'] = '/speciality/speciality';
$route['addSpeciality']='/speciality/addSpeciality';
$route['specialityAdd']='/speciality/specialityAdd';
$route['speciality/(:any)'] = '/speciality/speciality/$1';
$route['editSpeciality/(:any)'] = 'speciality/editSpeciality/$1';
$route['deleteSpeciality/(:any)'] = 'speciality/deleteSpeciality/$1';
$route['activateSpeciality/(:any)'] = 'speciality/activateSpeciality/$1';
$route['downloadSpeciality'] = 'speciality/downloadSpeciality';

#Calendar
$route['visit'] = '/calendar/visit';
$route['planVisit'] = '/calendar/addVisit';
$route['editVisit/(:any)'] = '/calendar/editVisit/$1';
$route['deleteVisit/(:any)'] = '/calendar/deleteVisit/$1';
$route['activateVisit/(:any)'] = '/calendar/activateVisit/$1';
$route['visitAdd'] = '/calendar/visitAdd';
$route['visit/(:any)'] = '/calendar/visit/$1';
$route['downloadVisit'] = '/calendar/downloadVisit';
$route['viewCalendar'] = '/calendar/viewCalendar';
$route['viewDemoCalendar'] = '/calendar/viewDemoCalendar';
$route['getDemoCalendar'] = '/calendar/getDemoCalendar';
//mahesh 14th july 2016 6:10 PM
$route['update_visitFeedback'] = '/calendar/update_visitFeedback';

#Demo
$route['demo'] = '/calendar/demo';
$route['planDemo'] = '/calendar/addDemo';
$route['editDemo/(:any)'] = '/calendar/editDemo/$1';
$route['deleteDemo/(:any)'] = '/calendar/deleteDemo/$1';
$route['activateDemo/(:any)'] = '/calendar/activateDemo/$1';
$route['demoAdd'] = '/calendar/demoAdd';
$route['demo/(:any)'] = '/calendar/demo/$1';
$route['downloadDemo'] = '/calendar/downloadDemo';
$route['getOpportunity'] = '/calendar/getOpportunity';
$route['getDemo'] = '/calendar/getDemo';
//mahesh 14th july 2016 6:40 PM
$route['update_demoFeedback'] = '/calendar/update_demoFeedback';


//Campaign
$route['campaign'] = '/campaign/campaign';
$route['addCampaign']='/campaign/addCampaign';
$route['campaignAdd']='/campaign/campaignAdd';
$route['campaign/(:any)'] = '/campaign/campaign/$1';
$route['editCampaign/(:any)'] = 'campaign/editCampaign/$1';
$route['deleteCampaign/(:any)'] = 'campaign/deleteCampaign/$1';
$route['activateCampaign/(:any)'] = 'campaign/activateCampaign/$1';
$route['downloadCampaign'] = 'campaign/downloadCampaign';
$route['deactivateCampaign/(:any)'] = 'campaign/deactivateCampaign/$1';

//campaign document
/* for role login pages */
$route['viewCampaignDocuments'] = 'campaign/viewCampaignDocuments';
$route['viewCampaignDocuments/(:any)'] = 'campaign/viewCampaignDocuments/$1';
/* admin pages */ 
$route['campaignDocuments'] = 'campaign/campaignDocuments';
$route['addCampaignDocuments'] = 'campaign/addCampaignDocuments';
$route['campaignDocumentsAdd'] = 'campaign/campaignDocumentsAdd';
$route['campaignDocuments/(:any)'] = '/campaign/campaignDocuments/$1';
$route['editCampaignDocuments/(:any)'] = 'campaign/editCampaignDocuments/$1';
$route['deleteCampaignDocuments/(:any)'] = 'campaign/deleteCampaignDocuments/$1';
$route['activateCampaignDocuments/(:any)'] = 'campaign/activateCampaignDocuments/$1';



#Location
$route['locationAdd'] = '/location/locationAdd';

#Geo
$route['geo'] = '/location/geo';
$route['addGeo'] = '/location/addGeo';
$route['editGeo/(:any)'] = '/location/editGeo/$1';
$route['geo/(:any)'] = '/location/geo/$1';
$route['downloadGeo'] = '/location/downloadGeo';

#Country
$route['country'] = '/location/country';
$route['addCountry'] = '/location/addCountry';
$route['editCountry/(:any)'] = '/location/editCountry/$1';
$route['country/(:any)'] = '/location/country/$1';
$route['downloadCountry'] = '/location/downloadCountry';

#Region
$route['region'] = '/location/region';
$route['addRegion'] = '/location/addRegion';
$route['editRegion/(:any)'] = '/location/editRegion/$1';
$route['region/(:any)'] = '/location/region/$1';
$route['downloadRegion'] = '/location/downloadRegion';

#State
$route['state'] = '/location/state';
$route['addState'] = '/location/addState';
$route['editState/(:any)'] = '/location/editState/$1';
$route['state/(:any)'] = '/location/state/$1';
$route['downloadState'] = '/location/downloadState';

#District
$route['district'] = '/location/district';
$route['addDistrict'] = '/location/addDistrict';
$route['editDistrict/(:any)'] = '/location/editDistrict/$1';
$route['district/(:any)'] = '/location/district/$1';
$route['downloadDistrict'] = '/location/downloadDistrict';

#City
$route['city'] = '/location/city';
$route['addCity'] = '/location/addCity';
$route['editCity/(:any)'] = '/location/editCity/$1';
$route['city/(:any)'] = '/location/city/$1';
$route['downloadCity'] = '/location/downloadCity';
$route['checkLocationAvailability'] = '/location/checkLocationAvailability';

// qoute
$route['quote']='quote/quote';
$route['quotationPdf/(:any)']='quote/quotation_pdf/$1';
$route['quotation/(:any)']='quote/quotation/$1';
$route['quote/(:any)'] = 'quote/quote/$1';
$route['addQuote']='quote/addQuote';
$route['quoteAdd']='quote/quoteAdd';
$route['getQuoteStokist']='quote/get_stokist_list';
$route['quoteApprovalList/(:any)']='quote/quote_approval_list/$1';
$route['quoteApprovalList']='quote/quote_approval_list';
$route['approveQuote/(:any)']='quote/quote_approve/$1';
$route['rejectQuote/(:any)']='quote/quote_reject/$1';
$route['quoteDiscount'] = 'quote/quoteDiscount';
$route['quoteDiscountApp'] = 'quote/quoteDiscountApp';
$route['addQuoteRevision'] = 'quote/addQuoteRevision';

/*
** Mahesh code
*/
$route['users'] = '/User/Users';
$route['users/(:any)'] = '/User/Users/$1';
$route['addUser'] = '/User/addUser';
$route['insertUser'] = '/User/insertUser';
$route['deleteUser/(:any)'] = '/User/deleteUser/$1';
$route['activateUser/(:any)'] = '/User/activateUser/$1';
$route['downloadUser'] = '/User/downloadUser';
$route['viewUserDetails/(:any)'] = '/User/viewUserDetails/$1';
$route['editUser/(:any)'] = '/User/editUser/$1';
$route['editUserDetails/(:any)'] = '/User/editUserDetails/$1';
$route['updateUserDetails'] = '/User/updateUserDetails';
$route['editUserLocations/(:any)'] = '/User/editUserLocations/$1';
$route['updateUserLocations'] = '/User/updateUserLocations';
$route['editUserProducts/(:any)'] = '/User/editUserProducts/$1';
$route['updateUserProducts'] = '/User/updateUserProducts';
$route['changeUserRole/(:any)'] = '/User/changeUserRole/$1';
$route['updateUserRole'] = '/User/updateUserRole';
$route['productTargetUsers'] = '/User/productTargetUsers';
$route['productTargetUsers/(:any)'] = '/User/productTargetUsers/$1';
$route['assignUserProductTargets/(:any)'] = '/User/assignUserProductTargets/$1';
$route['updateProductTargets'] = '/User/updateProductTargets';
$route['bulkUploadUserProductTargets/(:any)'] = '/User/bulkUploadUserProductTargets/$1';
$route['downloadUserProductTargetsCsv/(:any)'] = '/User/downloadUserProductTargetsCsv/$1';
$route['csvUploadUserProductTargets'] = '/User/csvUploadUserProductTargets';
$route['user_productTargetVsActual'] = '/User/user_productTargetVsActual';



#Ajax files

$route['cityLocation'] = '/Ajax/cityLocation';
$route['getCustomer'] = '/Ajax/getCustomer';
$route['getAllCustomers'] = '/Ajax/getAllCustomers';
//$route['getBranch'] = '/Ajax/getBranch';
$route['getLocationAndParent'] = '/Ajax/getLocationAndParent';
$route['test/(:any)'] = '/Ajax/test/$1';
$route['getCampaign'] = '/Ajax/getCampaign';
$route['getContact'] = '/Ajax/getContact';
$route['getChilds'] = '/Ajax/getChilds';
$route['getSecondUser'] = '/Ajax/getSecondUser';
$route['getRBH'] = '/Ajax/getRBH';
$route['getAutocompleteData'] = '/Ajax/getAutocompleteData';
$route['getReportees'] = '/Ajax/getReportees';
$route['getReportingSEAndDistributor'] = '/Ajax/getReportingSEAndDistributor';
$route['getDecisionMakers'] = '/Ajax/getDecisionMakers';
$route['getDecisionMakerFromLead'] = '/Ajax/getDecisionMakerFromLead';
$route['getReporteesWithUser'] = '/Ajax/getReporteesWithUser';
$route['getColleagues'] = '/Ajax/getColleagues';
$route['getUserProductReporteesWithUser'] = '/Ajax/getUserProductReporteesWithUser';
//mahesh 7th july
$route['getInactiveUsersWithOpenLeads'] = '/Ajax/getInactiveUsersWithOpenLeads';
$route['getActiveUsersToAssignLeads'] = '/Ajax/getActiveUsersToAssignLeads';
$route['getBranch'] = '/Product/getBranch';
$route['getCityFromRegion'] = '/Ajax/getCityFromRegion';


// Leads
$route['newLead'] = '/Lead/newLead';
$route['newLeadAdd'] = '/Lead/newLeadAdd';
$route['openLeads'] = '/Lead/openLeads';
$route['openLeads/(:any)'] = '/Lead/openLeads/$1';
$route['closedLeads'] = '/Lead/closedLeads';
$route['closedLeads/(:any)'] = '/Lead/closedLeads/$1';
$route['assignLeads'] = '/Lead/assignLeads';
$route['assignLeadAdd'] = '/Lead/assignLeadAdd';
$route['trackLeads'] = '/Lead/trackLeads';
$route['trackLeads/(:any)'] = '/Lead/trackLeads/$1';
$route['approveLeads'] = '/Lead/approveLeads';
$route['approveLeads/(:any)'] = '/Lead/approveLeads/$1';
$route['editAppLead/(:any)'] = '/Lead/editAppLead/$1';
$route['approveLead/(:any)'] = '/Lead/approveLead/$1';
$route['rejectLead/(:any)'] = '/Lead/rejectLead/$1';
$route['editApproveLead'] = '/Lead/editApproveLead';
$route['closedLeadDetails/(:any)'] = '/Lead/closedLeadDetails/$1';
$route['openLeadDetails/(:any)'] = '/Lead/openLeadDetails/$1';
$route['updateLead'] = '/Lead/updateLead';
$route['dropLead'] = '/Lead/dropLead';
$route['closeLead'] = '/Lead/closeLead';
// mahesh 4th Mar 2017
$route['editRejectedLead/(:any)'] = '/Lead/editRejectedLead/$1';
$route['updateRejectedLead'] = '/Lead/updateRejectedLead';

$route['opportunity'] = '/Lead/opportunity';
$route['opportunity/(:any)'] = '/Lead/opportunity/$1';
$route['opportunityClosed'] = '/Lead/opportunityClosed';
//mahesh 15th july 2016 03:33 pm
$route['opportunityClosed/(:any)'] = '/Lead/opportunityClosed/$1';
$route['download_openLeads'] = '/Lead/download_openLeads';
$route['download_closedLeads'] = '/Lead/download_closedLeads';
//mahesh 16th july 2016 04:12 pm
$route['edit_orderConclusionDate'] = '/Lead/edit_orderConclusionDate';
$route['update_orderConclusionDate'] = '/Lead/update_orderConclusionDate';


//Opportunities
$route['openOpportunityDetails/(:any)'] = '/Opportunity/openOpportunityDetails/$1';
$route['closedOpportunityDetails/(:any)'] = '/Opportunity/closedOpportunityDetails/$1';
$route['insertOpportunity'] = '/Opportunity/insertOpportunity';
$route['updateOpportunity'] = '/Opportunity/updateOpportunity';
$route['download_opportunities'] = '/Lead/download_opportunities';
// added on 27-07-2021
$route['updateOpportunityDistributorRole'] = '/Opportunity/updateOpportunityDistributorRole';
// added on 27-07-2021 end


//mahesh 7th july
$route['assignInactiveUserLeads'] = '/Lead/assignInactiveUserLeads';
$route['assignInactiveUserLeads/(:any)'] = '/Lead/assignInactiveUserLeads/$1';
$route['submit_assignInactiveUserLeads'] = '/Lead/submit_assignInactiveUserLeads';
$route['assignInactiveUserLeadsDownload'] = '/Lead/assignInactiveUserLeadsDownload';





//Quote
$route['openQuoteDetails/(:any)'] = '/Quote/openQuoteDetails/$1';
$route['closedQuoteDetails/(:any)'] = '/Quote/closedQuoteDetails/$1';

//Contract Note
$route['opencNoteDetails/(:any)'] = '/Contract/opencNoteDetails/$1';
$route['closedcNoteDetails/(:any)'] = '/Contract/closedcNoteDetails/$1';
$route['cNoteAdd'] = '/Contract/cNoteAdd';
$route['contractPdf/(:any)'] = '/quote/contract_pdf/$1';
$route['contract/(:any)'] = '/quote/contract/$1';



#11-07-2016
$route['demoDetails'] = '/calendar/demoDetails';
$route['downloadDemoDetails'] = '/calendar/downloadDemoDetails';
$route['downloadDemoCalendarDetails/(:any)'] = '/calendar/downloadDemoCalendarDetails/$1';

#13-07-2016
$route['soEntryOpen'] = '/Contract/soEntryOpen';
$route['soEntryClose'] = '/Contract/soEntryClose';

//mahesh 14th july 2016 8:28pm
$route['download_soEntry'] = '/Contract/download_soEntry';
$route['bulkUpload_soEntry'] = '/Contract/bulkUpload_soEntry';
$route['insert_soEntry'] = '/Contract/insert_soEntry';

//mahesh 15th july 2016 12:57 pm
$route['soEntryOpen/(:any)'] = '/Contract/soEntryOpen/$1';
$route['soEntryClose/(:any)'] = '/Contract/soEntryClose/$1';

//Post GoLive additions
$route['re_route_user'] = '/Lead/re_route_user'; //Added by Naveen on 21st July 2016

#user logs // Added by Mahesh on 4th Aug 2016 12:30PM
$route['userLogs'] = '/UserLogs/userLogs';
$route['userLogs/(:any)'] = '/UserLogs/userLogs/$1';
$route['downloadUserLogs'] = '/UserLogs/downloadUserLogs';

#Dashboards // Added by Naveen on 07th Sep 2016 16:30 PM

$route['opportunityDashboard'] = '/Dashboard/opportunityDashboard';
$route['leadsDashboard'] = '/Dashboard/leadsDashboard';
$route['getOpportunityDashboardData'] = 'Dashboard/getOpportunityDashboardData';
$route['getLeadDashboardData'] = 'Dashboard/getLeadDashboardData';

# Demo Details // Added by Naveen on 13th Oct 2016 15:45 PM
$route['demoDetails/(:any)'] = '/calendar/demoDetails/$1';

// New Enhancements   START
$route['manageContractNotes'] = '/Contract/manageContractNotes';
$route['manageContractNotes/(:any)'] = '/Contract/manageContractNotes/$1';
$route['deleteContractNote/(:any)'] = '/Contract/deleteContractNote/$1';
// New Enhancements   END

// Phase2 START

# User Weekly Product Target
$route['financial_year']='Financial_year/financial_year';
$route['financial_year/(:any)']='Financial_year/financial_year/$1';
$route['add_financial_year'] = '/Financial_year/add_financial_year';
$route['insert_financial_year']='/Financial_year/insert_financial_year';
/*$route['get_weeks']='/Financial_year/get_weeks';
*/$route['retrieve_weeks']='/Financial_year/retrieve_weeks';
$route['retrieve_weeks/(:any)']='/Financial_year/retrieve_weeks/$1';
$route['weekly_user_product_targets']='/User_product_target/weekly_user_product_targets';
$route['weekly_user_product_targets/(:any)']='/User_product_target/weekly_user_product_targets/$1';
$route['bulk_upload_weekly_user_product_targets/(:any)']='/User_product_target/bulk_upload_weekly_user_product_targets/$1';
$route['download_weekly_user_product_target_csv']='/User_product_target/download_weekly_user_product_target_csv';
$route['csv_upload_weekly_user_product_targets']='/User_product_target/csv_upload_weekly_user_product_targets';

# Distributor Purchase Order
$route['po_list']='Purchase_order/po_list';
$route['po_list/(:any)']='Purchase_order/po_list/$1';
$route['add_po']='Purchase_order/add_po';
$route['insert_po']='Purchase_order/insert_po';
$route['view_po/(:any)']='Purchase_order/view_po/$1';
$route['view_po_untag/(:any)']='Purchase_order/view_po_untag/$1';
$route['view_tagged_po/(:any)']='Purchase_order/view_tagged_po/$1';
$route['download_po']='Purchase_order/download_po';
$route['tag_opportunity/(:any)']='Purchase_order/tag_opportunity/$1';
$route['tag_opportunity/(:any)/(:any)']='Purchase_order/tag_opportunity/$1/$1';
$route['untag_opportunity/(:any)']='Purchase_order/untag_opportunity/$1';
$route['untag_opportunity/(:any)/(:any)']='Purchase_order/untag_opportunity/$1/$1';
$route['add_po_opportunity']='Purchase_order/add_po_opportunity';
$route['po_opp_tag_list']='Purchase_order/po_opp_tag_list';
$route['po_opp_tag_list/(:any)']='Purchase_order/po_opp_tag_list/$1';
$route['po_opp_status']='Purchase_order/po_opp_status';
$route['untag_po_list']='Purchase_order/untag_po_list';
$route['untag_po_list/(:any)']='Purchase_order/untag_po_list/$1';

# Distributor stock details
$route['distributor_stock_details']='Purchase_order/distributor_stock_details';
$route['distributor_stock_details/(:any)']='Purchase_order/distributor_stock_details/$1';
$route['download_dist_stock']='Purchase_order/download_dist_stock';
$route['print_dist_stock']='Purchase_order/print_dist_stock';

# Cnote approval mechanism by rbh
$route['contract_note_approval_list']='/Cnote_rbh_approval/contract_note_approval_list';
$route['contract_note_approval_list/(:any)']='/Cnote_rbh_approval/contract_note_approval_list/$1';
$route['cNote_approval/(:any)']='/Cnote_rbh_approval/cNote_approval/$1';
$route['view_contract_note_pdf/(:any)']='/Cnote_rbh_approval/view_contract_note_pdf/$1';

# Stock in hand bulk upload
$route['product_stock_upload']='Product_stock/product_stock_upload';
$route['download_product_stock_csv']='Product_stock/download_product_stock_csv';
$route['insert_product_stock_upload']='Product_stock/insert_product_stock_upload';
$route['download_missing_product_stock_files/(:any)']='Product_stock/download_missing_product_stock_files/$1';
$route['product_stock_list']='Product_stock/product_stock_list';
$route['product_stock_list/(:any)']='Product_stock/product_stock_list/$1';
$route['download_product_stock_bulk_upload']='Product_stock/download_product_stock_bulk_upload';
$route['download_ps_bulk_upload_details/(:any)']='Product_stock/download_ps_bulk_upload_details/$1';
$route['missed_product_stock_records/(:any)']='Product_stock/missed_product_stock_records/$1';


# Oustanding amount bulk upload
$route['outstanding_amount_upload']='Outstanding_bulk_upload/outstanding_amount_upload';
$route['insert_outstanding_amount_upload']='Outstanding_bulk_upload/insert_outstanding_amount_upload';
$route['download_missing_so_files/(:any)']='Outstanding_bulk_upload/download_missing_so_files/$1';
$route['so_amount_list']='Outstanding_bulk_upload/so_amount_list';
$route['so_amount_list/(:any)']='Outstanding_bulk_upload/so_amount_list/$1';
$route['download_so_bulk_upload']='Outstanding_bulk_upload/download_so_bulk_upload';
$route['download_so_bulk_upload_details/(:any)']='Outstanding_bulk_upload/download_so_bulk_upload_details/$1';
$route['missed_outstanding_upload_records/(:any)']='Outstanding_bulk_upload/missed_outstanding_upload_records/$1';

# Margin Analysis Approval
$route['margin_analysis_list']='MarginAnalysis/margin_analysis_approval_list';
$route['margin_analysis_list/(:any)']='MarginAnalysis/margin_analysis_approval_list/$1';
$route['submitMarginAnalysisApproval']='MarginAnalysis/submitMarginAnalysisApproval';
$route['marginAnalysisConfig']='MarginAnalysis/marginAnalysisConfig';
$route['submitMarginAnalysisConfig']='MarginAnalysis/submitMarginAnalysisConfig';
$route['margin_bands']='MarginAnalysis/margin_bands';
#OTR Functionality
$route['otr_list']='Otr/otr_list';
$route['otr_list/(:any)']='Otr/otr_list/$1';

#Report
#Stock in Hand
$route['stock_in_hand'] = 'Report/stockInHand';
$route['getStockInHandChart2Data'] = 'Report/getStockInHandChart2Data';
$route['getToolStatusChart'] = 'Report/getToolStatusChart';
$route['getStockInHandChart3Data'] = 'Report/getStockInHandChart3Data';
#Outstanding Collections
$route['outstanding_collection_report'] = 'Report/outstandingCollection';
$route['getOutStandingCollectionChart2Data'] = 'Report/getOutStandingCollectionChart2Data';
$route['getOutStandingCollectionChart3Data'] = 'Report/getOutStandingCollectionChart3Data';
#Opportunity Lost
$route['opportunity_lost_report'] = 'Report/opportunityLost';
$route['getOpportunityLostChart2Data'] = 'Report/getOpportunityLostChart2Data';
$route['getOpportunityLostChart3Data'] = 'Report/getOpportunityLostChart3Data';
$route['opportunity_lost_report_filter'] = 'Report/opportunity_lost_report_filter';
#Fresh Business
$route['fresh_business_report'] = 'Report/freshBusiness';
$route['getFreshBusinessChart1Data'] = 'Report/getFreshBusinessChart1Data';
$route['getFreshBusinessChart2Data'] = 'Report/getFreshBusinessChart2Data';
$route['getFreshBusinessChart3Data'] = 'Report/getFreshBusinessChart3Data';
$route['download_fresh_business_report']='Report/download_fresh_business_report';

#open order report
$route['open_orders']='Report/open_orders';
$route['openOrderChart1Data'] = 'Report/openOrderChart1Data';
$route['openOrderChart2Data'] = 'Report/openOrderChart2Data';
$route['openOrderChart3Data'] = 'Report/openOrderChart3Data';

#Funnel or open opportunites report
$route['open_opportunities']='Report/open_opportunities';
$route['openOpportunitiesFilterData']='Report/openOpportunitiesFilterData';

//Mounika
# Distributor Product Opening Stock
$route['product_opening_stock_details']='/Product_opening_stock/product_opening_stock_details';
$route['insert_product_opening_stock']='/Product_opening_stock/insert_product_opening_stock';
# RBH Entering Distributor opening stock
$route['get_rbh_distributor_list']='Product_opening_stock/get_rbh_distributor_list';
$route['get_rbh_distributor_list/(:any)']='Product_opening_stock/get_rbh_distributor_list/$1';
$route['product_opening_stock_details/(:any)']='/Product_opening_stock/product_opening_stock_details/$1';

# opportunity Status // Added by suresh on 4th May 2017 10:41 AM
$route['opportunityStatus'] = '/Lead/opportunityStatus';
$route['opportunityStatus/(:any)'] = '/Lead/opportunityStatus/$1';
$route['download_allOpportunities'] = '/Lead/download_allOpportunities';

# Quote revision
$route['quoteRevision/(:any)'] = '/Quote/quoteRevision/$1';

# Product prices bulk update
$route['product_price_upload']='Product_price/product_price_upload';
$route['download_product_price_csv']='Product_price/download_product_price_csv';
$route['insert_product_price_upload']='Product_price/insert_product_price_upload';
$route['missed_product_price_records/(:any)']='Product_price/missed_product_price_records/$1';
$route['download_missing_product_price_files/(:any)']='Product_price/download_missing_product_price_files/$1';
$route['product_price_list']='Product_price/product_price_list';
$route['product_price_list/(:any)']='Product_price/product_price_list/$1';
$route['download_product_price_bulk_upload']='Product_price/download_product_price_bulk_upload';
$route['download_pp_bulk_upload_details/(:any)']='Product_price/download_pp_bulk_upload_details/$1';

# Quote Tracking
$route['track_quotes'] = '/MarginAnalysis/quote_tracking';
$route['track_quotes/(:any)'] = '/MarginAnalysis/quote_tracking/$1';
$route['getUserProductsBySegment'] = '/Purchase_order/getUserProductsBySegment';

# PO Approval list
$route['po_approval_list'] = '/MarginAnalysis/po_approval_list';
$route['po_approval_list/(:any)'] = '/MarginAnalysis/po_approval_list/$1';
$route['submitPoApproval'] = '/MarginAnalysis/submitPoApproval';
$route['po_tracking'] = '/MarginAnalysis/po_tracking';
$route['po_tracking/(:any)'] = '/MarginAnalysis/po_tracking/$1';
$route['po_revision/(:any)'] = '/Purchase_order/po_revision/$1';
$route['submitPoRevision'] = '/Purchase_order/submitPoRevision';

#Commission report for dealer
$route['commission_report']='Commission/commission_report';
$route['commission_report/(:any)']='Commission/commission_report/$1';
$route['otr_commission_report']='Commission/otr_commission_report';
$route['otr_commission_report/(:any)']='Commission/otr_commission_report/$1';
$route['add_dealer_payment']='Commission/add_dealer_payment';

# Reports
$route['run_rate_projection'] = 'Run_rate/run_rate_projection';
$route['icrm_report'] = 'Icrm/icrm_report';
$route['icrm_product'] = 'Icrm/icrm_product';
$route['sales_by_dealer']='Sales_by_dealer/sales_by_dealer';

#products bulk upload
$route['product_bulk_upload']='Product_bulk_upload/product_bulk_upload';
$route['download_product_csv']='Product_bulk_upload/download_product_csv';
$route['insert_product_list_upload']='Product_bulk_upload/insert_product_list_upload';
$route['missed_product_list_records/(:any)']='Product_bulk_upload/missed_product_list_records/$1';
$route['download_missing_product_list_files/(:any)']='Product_bulk_upload/download_missing_product_list_files/$1';
$route['product_list']='Product_bulk_upload/product_list';
$route['product_list/(:any)']='Product_bulk_upload/product_list/$1';
$route['download_product_list_bulk_upload']='Product_bulk_upload/download_product_list_bulk_upload';
$route['download_product_bulk_upload_details/(:any)']='Product_bulk_upload/download_product_bulk_upload_details/$1';

$route['downloadProduct_Upload'] = 'Product_bulk_upload/downloadProduct_Upload';
$route['generate_so_outstanding_xl']='Outstanding_bulk_upload/generate_so_outstanding_xl';
$route['insert_po_documents']='MarginAnalysis/insert_po_documents';

#settings
$route['settings']='Preference/get_preference_list';
$route['submit_settings']='Preference/submit_settings';

#Email Approval Action
$route['quoteApprovalAction/(:any)/(:any)']='EmailApprovalAction/quoteApprovalAction_fromEmail/$1/$2';
$route['approval_result']='EmailApprovalAction/approval_result';
$route['submitQuoteApprovalAction']='EmailApprovalAction/submitQuoteApprovalAction';
$route['poApprovalAction/(:any)/(:any)']='EmailApprovalAction/poApprovalAction_fromEmail/$1/$2';
$route['submitPoApprovalAction']='EmailApprovalAction/submitPoApproval';

#funnel report
$route['funnel_report']='Report/funnel_report';
$route['funnel_chart2'] = 'Report/funnel_chart2';
$route['funnel_chart3'] = 'Report/funnel_chart3';
$route['filter_funnel_chart']='Report/filter_funnel_chart';
$route['get_filter_duration']='Report/get_filter_duration';
$route['download_funnel_report']='Report/download_funnel_report';

#Visit Plan Report
$route['visit_plan_report']='Report/visit_plan_report';
$route['visit_plan_report/(:any)']='Report/visit_plan_report/$1';
$route['download_visit_plan_report'] = "Report/download_visit_plan_report";
$route['get_filter_funnel_download_data'] = "Report/download_funnel_report";

#Daily Visit Plan Report
$route['daily_visit_plan_report']='Report/daily_visit_plan_report';
$route['daily_visit_plan_report/(:any)']='Report/daily_visit_plan_report/$1';
$route['daily_visit_plan_report_download']='Report/daily_visit_plan_report_download';

#Visit Plan Report
$route['location_report']='Report/location_report';
$route['location_report/(:any)']='Report/location_report/$1';
$route['download_location_report'] = "Report/download_location_report";
#Demo Report
$route['demo_report']='Report/demo_report';
$route['demo_report/(:any)']='Report/demo_report/$1';
$route['download_demo_report'] = "Report/download_demo_report";

#static opportunity lost report
$route['static_opp_lost_report']='Static_report/static_opp_lost_report';
$route['get_filter_duration_ol']='Static_report/get_filter_duration_ol';
$route['static_opportunity_lost_report_filter']='Static_report/static_opportunity_lost_report_filter';
$route['static_getOpportunityLostChart2Data']='Static_report/static_getOpportunityLostChart2Data';
$route['static_getOpportunityLostChart3Data']='Static_report/static_getOpportunityLostChart3Data';

$route['static_target_vs_sales_report']='Static_report/static_target_vs_sales_report';
$route['static_targetVsSalesChart2Data']='Static_report/static_targetVsSalesChart2Data';
$route['static_tvs_2_report'] = 'Static_report/static_tvs_2_report';

$route['static_funnel_report']='Static_report/static_funnel_report';
$route['static_funnel_chart2'] = 'Static_report/static_funnel_chart2';
$route['static_funnel_chart3'] = 'Static_report/static_funnel_chart3';
$route['static_filter_funnel_chart']='Static_report/static_filter_funnel_chart';
$route['static_get_filter_duration']='Static_report/static_get_filter_duration';

#Target Vs Sales Report
$route['target_vs_sales_report']='Report/target_vs_sales_report';
$route['targetVsSalesChart2Data']='Report/targetVsSalesChart2Data';
$route['tvs_2_report'] = 'Report/tvs_2_report';
$route['get_filter_duration_table']='Report/get_filter_duration_table';
$route['download_target_vs_sales_report']='Report/download_target_vs_sales_report';

#Margin Analysis report
$route['margin_analysis_report']='Report2/margin_analysis_report';
$route['getProductsDropdownBySegment'] = 'Product/getProductsDropdownBySegment';
$route['download_margin_analysis_report']='Report2/download_margin_analysis_report';
# CNote Margin Analysis report
$route['cnote_margin_analysis']='Report2/cnote_margin_analysis';
$route['cnote_margin_analysis/(:any)']='Report2/cnote_margin_analysis/$1';
$route['download_cnote_margin_report']='Report2/download_cnote_margin_report';

# Fresh business static report
$route['fresh_business_bar']='Static_report/fresh_business_bar';
$route['fresh_business_filter_bar']='Static_report/fresh_business_filter_bar';
$route['fresh_business_bar2']='Static_report/fresh_business_bar2';
$route['download_stock_in_hand_xl']='Report/download_stock_in_hand_xl';

$route['get_filter_funnel_table']='Report/get_filter_funnel_table';
$route['dependent_users']='Report/dependent_users';
$route['getCustomersAutoCompleteList'] = 'Report2/getCustomersAutoCompleteList';
$route['getDealersAutoCompleteList'] = 'Report2/getDealersAutoCompleteList';

#updates 4th Jan 2018
$route['stock_in_hand_table']='Report/stock_in_hand_table';
$route['getProductsDropdownforstock']='Report/getProductsDropdownforstock';
$route['getsegmentDropdownforstock']='Report/getsegmentDropdownforstock';

$route['download_stock_in_hand_xl']='Report/download_stock_in_hand_xl';

$route['get_custom_filter_duration']='Report/get_custom_filter_duration';
#outstanding report
$route['outstanding_report']='Report/outstanding_report';
$route['getoutstandingChart1Data']='Report/getoutstandingChart1Data';
$route['getoutstandingChart2Data']='Report/getoutstandingChart2Data';
$route['getoutstandingChart3Data']='Report/getoutstandingChart3Data';
#Runrate Report
$route['run_rate']='Report/run_rate';
$route['filter_runrate_chart']='Report/filter_runrate_chart';
$route['rr_pro_table']='Report/rr_pro_table';
$route['download_rr_report']='Report/download_rr_report';

#Incentives Report
$route['incentives']='Report/incentives';
$route['filter_incentives_chart']='Report/filter_incentives_chart';
$route['get_quarter_based_on_year']='Report/get_quarter_based_on_year';
$route['get_incentives_chart2']='Report/get_incentives_chart2';
$route['download_incentives']='Report/download_incentives';
$route['dependent_products']='Report/dependent_products';

$route['incentive_settings']='Settings/incentive_settings';
$route['incentive_settings/(:any)']='Settings/incentive_settings/$1';
$route['view_incentive_settings/(:any)']='Settings/view_incentive_settings/$1';
$route['add_incentive_settings']='Settings/add_incentive_settings';
$route['incentive_insert_settings']='Settings/incentive_insert_settings';

#Lead Performance Report
$route['lead_performance_report']='Report/lead_performance_report';
$route['lead_performance_report/(:any)']='Report/lead_performance_report/$1';
$route['lead_performance_report_download']='Report/lead_performance_report_download';

#Order Lost Analysis Report
$route['order_lost_analysis_report']='Report/order_lost_analysis_report';
$route['order_lost_analysis_report/(:any)']='Report/order_lost_analysis_report/$1';
$route['order_lost_analysis_report_download']='Report/order_lost_analysis_report_download';

//New outstanding changes
$route['new_so_amount_upload']='New_outstanding_format/new_so_amount_upload';
$route['generate_new_so_outstanding_xl']='New_outstanding_format/generate_new_so_outstanding_xl';
$route['insert_new_so_amount_upload']='New_outstanding_format/insert_new_so_amount_upload';
$route['new_so_amount_list']='New_outstanding_format/new_so_amount_list';
$route['download_new_so_bulk_upload']='New_outstanding_format/download_new_so_bulk_upload';
$route['download_new_so_bulk_upload_details/(:any)']='New_outstanding_format/download_new_so_bulk_upload_details/$1';
$route['get_new_outstanding_report']='New_outstanding_format/get_new_outstanding_report';
$route['get_new_outstanding_report/(:any)']='New_outstanding_format/get_new_outstanding_report/$1';
$route['download_new_so_report']='New_outstanding_format/download_new_so_report';

/*Newly added routes*/
$route['currency']='Currency/currency';
$route['currency/(:any)']='Currency/currency/$1';
$route['add_currency']='Currency/add_currency';
$route['currency_add']='Currency/currency_add';
$route['editCurrency/(:any)']='Currency/editCurrency/$1';
$route['isCurCodeExist']='Currency/isCurCodeExist';
$route['downloadCurrency']='Currency/downloadCurrency';

//srilekha
$route['branch'] = 'Branch/branch';
$route['branch/(:any)'] = 'Branch/branch/$1';
$route['addBranch'] = '/Branch/addbranch';
$route['editBranch/(:any)'] = '/Branch/editbranch/$1';
$route['deleteBranch/(:any)'] = '/Branch/deletebranch/$1';
$route['activateBranch/(:any)'] = '/Branch/activatebranch/$1';
$route['branchAdd'] = '/Branch/branchAdd';
$route['downloadBranch'] = '/Branch/downloadbranch';


$route['customer_category'] = 'Customer_category/customer_category';
$route['customer_category/(:any)'] = 'Customer_category/customer_category/$1';
$route['addcustomer_category'] = '/Customer_category/addcustomer_category';
$route['editcustomer_category/(:any)'] = '/Customer_category/editcustomer_category/$1';
$route['deletecustomer_category/(:any)'] = '/Customer_category/deletecustomer_category/$1';
$route['activatecustomer_category/(:any)'] = '/Customer_category/activatecustomer_category/$1';
$route['customer_categoryAdd'] = '/Customer_category/customer_categoryAdd';
$route['downloadcustomer_category'] = '/Customer_category/downloadcustomer_category';


$route['sub_category'] = 'Customer_category/sub_category';
$route['sub_category/(:any)'] = 'Customer_category/sub_category/$1';
$route['addsub_category'] = '/Customer_category/addsub_category';
$route['editsub_category/(:any)'] = '/Customer_category/editsub_category/$1';
$route['deletesub_category/(:any)'] = '/Customer_category/deletesub_category/$1';
$route['activatesub_category/(:any)'] = '/Customer_category/activatesub_category/$1';
$route['sub_categoryAdd'] = '/Customer_category/sub_categoryAdd';
$route['downloadsub_category'] = '/Customer_category/downloadsub_category';

$route['check_quotes']='Contract/check_quotes';

// New channel Partner
$route['channel_partner'] = '/Channel_partner/channel_partner';
$route['channel_partner/(:any)'] = '/Channel_partner/channel_partner/$1';
$route['addchannel_partner']='/Channel_partner/addchannel_partner';
$route['channel_partnerAdd']='/Channel_partner/channel_partnerAdd';
$route['editchannel_partner/(:any)'] = 'Channel_partner/editchannel_partner/$1';
$route['deletechannel_partner/(:any)'] = 'Channel_partner/deletechannel_partner/$1';
$route['activatechannel_partner/(:any)'] = 'Channel_partner/activatechannel_partner/$1';
$route['downloadchannel_partner'] = 'Channel_partner/downloadchannel_partner';


$route['checksubcategoryAvailability'] = 'Product/checksubcategoryAvailability';
$route['currency_conversion'] = 'Currency_conversion/currency_conversion';
$route['insert_currency_conversion'] = 'Currency_conversion/insert_currency_conversion';

$route['login_api'] = '/apis/Login_api/login';
$route['logout_api'] = '/apis/Login_api/logout';
$route['get_user_list_api'] = '/apis/Login_api/get_se_data';
$route['app_version_api'] = '/apis/Login_api/force_update';

#Leads API
$route['newLead_api'] = '/apis/Lead_api/newLead';
$route['getCustomer_api'] = '/apis/Lead_api/getCustomer';
$route['getColleagues_api'] = '/apis/Lead_api/getColleagues';
$route['getCampaign_api'] = '/apis/Lead_api/getCampaign';
$route['getContact_api'] = '/apis/Lead_api/getContact';
$route['getSecondUser_api'] = '/apis/Lead_api/getSecondUser';
$route['newLeadAdd_api'] = '/apis/Lead_api/newLeadAdd';
$route['openLeads_api'] = '/apis/Lead_api/openLeads';
$route['updateLead_api'] = '/apis/Lead_api/updateLead';
$route['closedLeads_api'] = '/apis/Lead_api/closedLeads';
$route['leadStatusBar_api'] = '/apis/Lead_api/leadStatusBar';
$route['re_route_user_api'] = '/apis/Lead_api/re_route_user';
$route['dropLead_api'] = '/apis/Lead_api/dropLead';
$route['closeLead_api'] = '/apis/Lead_api/closeLead';


#customer API
$route['addCustomer_api']='/apis/Customer_api/addCustomer';
$route['getSubCategory_api'] = '/apis/Customer_api/get_sub_category';
$route['cityLocation_api'] = '/apis/Customer_api/cityLocation';
$route['customerAdd_api'] = '/apis/Customer_api/customerAdd';
$route['customer_api'] = '/apis/Customer_api/customer';
$route['updateCustomer_api'] = '/apis/Customer_api/updateCustomer';
$route['is_customer_code_exists_api'] = '/apis/Customer_api/is_customer_code_exists';
$route['is_customername_exists_api'] = '/apis/Customer_api/is_customername_exists';
$route['editCustomer_api'] = '/apis/Customer_api/editCustomer';

#Punch In/Out API
$route['punch_in_api'] = '/apis/Login_api/punch_in';
$route['punch_out_api'] = '/apis/Login_api/punch_out';

# Contact API
$route['addContact_api']='/apis/Contact_api/addContact';
$route['contactAdd_api']='/apis/Contact_api/contactAdd';
$route['contact_api'] = '/apis/Contact_api/contact';
$route['editContact_api'] = '/apis/Contact_api/editContact';
$route['updateContact_api'] = '/apis/Contact_api/updateContact';

#Opportunity API
$route['opportunity_api'] = '/apis/Opportunity_api/opportunity';
$route['getProductGroup_api'] = '/apis/Opportunity_api/getProductGroup';
$route['getProduct_api'] = '/apis/Opportunity_api/getProduct';
$route['getDecisionMakers_api'] = '/apis/Opportunity_api/getDecisionMakers';
$route['get_competitors_api'] = '/apis/Opportunity_api/get_competitors';
$route['insertOpportunity_api'] = '/apis/Opportunity_api/insertOpportunity';
$route['updateOpportunity_api'] = '/apis/Opportunity_api/updateOpportunity';
$route['get_opportunity_category_api'] = '/apis/Opportunity_api/get_opportunity_category';
$route['get_opportunity_probabilitybar_api'] = '/apis/Opportunity_api/get_opportunity_probabilitybar';
$route['opportunityClosed_api'] = '/apis/Opportunity_api/opportunityClosed';
$route['get_opportunity_statusbar_api'] = '/apis/Opportunity_api/get_opportunity_statusbar';
$route['open_opportunities_api'] = '/apis/Opportunity_api/open_opportunities';
$route['getReporteesWithUser_api'] = '/apis/Opportunity_api/getReporteesWithUser';
$route['get_life_time_api'] = '/apis/Opportunity_api/get_life_time';
$route['view_opportunity_api'] = '/apis/Opportunity_api/view_opportunity';
$route['getRBH_api'] = '/apis/Opportunity_api/getRBH';

#Quote Revision
$route['quoteRevision_api'] = '/apis/Quote_api/quoteRevision';
$route['addQuoteRevision_api'] = '/apis/Quote_api/addQuoteRevision';
$route['approveCustomers'] = '/Customer/approveCustomers';
$route['approveCustomers/(:any)'] = '/Customer/approveCustomers/$1';
$route['approveCustomer/(:any)'] = '/Customer/approveCustomer/$1';
$route['rejectCustomer/(:any)'] = '/Customer/rejectCustomer/$1';


#Quote API
$route['openQuoteDetails_api'] = '/apis/Quote_api/openQuoteDetails';
$route['quoteAdd_api']='/apis/Quote_api/quoteAdd';
$route['viewQuote_api'] = '/apis/Quote_api/viewQuote';
$route['freeSupplyItem_api'] = '/apis/Quote_api/freeSupplyItem';

#Track Quote
$route['track_quotes_api'] = '/apis/Quote_api/quote_tracking';

#Visit API
$route['planVisit_api'] = '/apis/Calendar_api/addVisit';
$route['visitAdd_api'] = '/apis/Calendar_api/visitAdd';
$route['visit_api'] = '/apis/Calendar_api/visit';
$route['deleteVisit_api'] = '/apis/Calendar_api/deleteVisit';
$route['activateVisit_api'] = '/apis/Calendar_api/activateVisit';
$route['editvisit_api'] = '/apis/Calendar_api/editvisit';
$route['updatevisit_api'] = '/apis/Calendar_api/updatevisit';
$route['viewCalendar_api'] = '/apis/Calendar_api/viewCalendar';
$route['getreportees_api'] = '/apis/Calendar_api/getreportees';
$route['update_visitFeedback_api'] = '/apis/Calendar_api/update_visitFeedback';


#Demo API
$route['planDemo_api'] = '/apis/Calendar_api/addDemo';
$route['getopportunity_api'] = '/apis/Calendar_api/getOpportunity';
$route['getdemo_api'] = '/apis/Calendar_api/getDemo';
$route['demoAdd_api'] = '/apis/Calendar_api/demoAdd';
$route['demo_api'] = '/apis/Calendar_api/demo';
$route['editdemo_api'] = '/apis/Calendar_api/editdemo';
$route['updatedemo_api'] = '/apis/Calendar_api/updatedemo';
$route['deleteDemo_api'] = '/apis/Calendar_api/deleteDemo';
$route['activateDemo_api'] = '/apis/Calendar_api/activateDemo';
$route['update_demoFeedback_api'] = '/apis/Calendar_api/update_demoFeedback';


$route['live_location'] = 'Live_location/live_location';
$route['insert_live_location_api'] = '/apis/Live_location_api/live_location_insert';
$route['get_near_by_customers_api'] = '/apis/Live_location_api/get_near_by_customers';
$route['live_location_list'] = 'Live_location/live_location_list';
$route['fetch_live_location'] = 'Live_location/fetch_live_location';
$route['track_live_location'] = 'Live_location/track_live_location';

#Cnote API
$route['openCNoteDetails_api'] = '/apis/Contract_api/openCNoteDetails';
$route['cNoteAdd_api'] = '/apis/Contract_api/cNoteAdd';

#Marketing Documents
$route['viewCampaignDocuments_api'] = '/apis/Contact_api/viewCampaignDocuments';

# Stock IN Hand
$route['stock_in_hand_api']='/apis/Customer_api/stock_in_hand';
$route['get_segment_api'] = '/apis/Customer_api/get_segment';
$route['get_product_api'] = '/apis/Customer_api/get_product';
#CNote api
$route['openCNoteDetails_api'] = '/apis/Contract_api/openCNoteDetails';
// $route['cNoteAdd_api'] = '/apis/Contract_api/cNoteAdd';

$route['cnoteApprovalAction/(:any)/(:any)']='EmailApprovalAction/cnoteApprovalAction_fromEmail/$1/$2';

$route['getCnoteGeneratedCustomerList'] = 'Customer/getCnoteGeneratedCustomerList';

$route['punch_in_report'] = 'UserLogs/punch_in_report';
$route['punch_in_report/(:any)'] = '/UserLogs/punch_in_report/$1';
$route['download_punch_in_report'] = '/UserLogs/download_punch_in_report';

$route['punchinlogs'] = 'UserLogs/punchinlogs';
$route['punchinlogs/(:any)'] = 'UserLogs/punchinlogs/$1';
$route['download_punch_in_logs'] = '/UserLogs/download_punch_in_logs';
/*END*/
// Phase2 END
$route['manageFreeSupplyItems'] = '/product/manageFreeSupplyItems';
$route['updatePercentage'] = '/product/updatePercentage';

$route['addConditionForApprovalMail'] = '/Lead/addConditionForApprovalMail';
$route['submitConditionForApprovigMail'] = '/Lead/submitConditionForApprovigMail';
$route['quoteRevStatusChange'] = '/Settings/quoteRevStatusChange';
$route['updateRevStatus'] = '/Settings/updateRevStatus';

