<?php
defined('BASEPATH') or exit('No direct script access allowed');
defined('JWT_SECRET_KEY') or define('JWT_SECRET_KEY', '68f05dec6014f68e760c5c5fa3e31bcf391a2e10');
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


// Custom Constant Variables
define('FORMS', 'forms/');
define('ALLOW_MODIFICATION', 1);
define('DEMO_VERSION_MSG', 'Modification in demo version is not allowed');
define('TABLES', 'tables/');
define('VIEW', 'view/');
define('CATEGORY_IMG_PATH', 'uploads/category_image/');
define('SUBCATEGORY_IMG_PATH', 'uploads/subcategory_image/');
define('PRODUCT_IMG_PATH', 'uploads/product_image/');
define('SLIDER_IMG_PATH', 'uploads/slider_image/');
define('OFFER_IMG_PATH', 'uploads/offer_image/');
define('NOTIFICATION_IMG_PATH', 'uploads/notifications/');
define('USER_IMG_PATH', 'uploads/user_image/');
define('UPDATE_PATH', 'update/');
define('MEDIA_PATH', 'uploads/media/');
define('NO_IMAGE', 'assets/no-image.png');
define('EMAIL_ORDER_SUCCESS_IMG_PATH', 'assets/admin/images/order-success.png');
define('REVIEW_IMG_PATH', 'uploads/review_image/');
define('TICKET_IMG_PATH', 'uploads/tickets/');
define('DIRECT_BANK_TRANSFER_IMG_PATH', 'uploads/bank_transfer/');
define('SELLER_DOCUMENTS_PATH', 'uploads/seller/');
define('USER_AVATAR_PATH', 'uploads/user_avatar/');
define('RETAILER_LOGO_PATH', 'uploads/retailer_logo/');
define('RETAILER_BUSINESS_CARD_FRONT_PATH', 'uploads/retailer_business_card_front/');
define('RETAILER_BUSINESS_CARD_BACK_PATH', 'uploads/retailer_business_card_back/');

define('PAYMENT_DEMAND_IMG_PATH', 'uploads/payment_demand/');
define('INVOICE_IMG_PATH', 'uploads/invoices/');
define('EWAY_BILL_IMG_PATH', 'uploads/eway_bills/');
define('PAYMENT_CONFIRMATION_IMG_PATH','uploads/payment_confirmation/');
define('PAYMENT_MFG_ACK_IMG_PATH','uploads/mfg_payment_ack/');
define('PAYMENT_MFG_CONFIRMATION_IMG_PATH','uploads/mfg_payment_confirmation/');
define('COMPLAINT_IMG_PATH','uploads/complaints/');
define('COMPLAINT_MSG_IMG_PATH','uploads/complaints_msg/');

define('SELLER_LOGO_PATH', 'uploads/seller_logo/');
define('SELLER_BUSINESS_CARD_FRONT_PATH', 'uploads/seller_business_card_front/');
define('SELLER_BUSINESS_CARD_BACK_PATH', 'uploads/seller_business_card_back/');

define('SELLER_CANCELLED_CHEQUE_IMG_PATH', 'uploads/seller_cancelled_cheque/');
define('RETAILER_CANCELLED_CHEQUE_IMG_PATH', 'uploads/retailer_cancelled_cheque/');

define('SELLER_GST_IMG_PATH', 'uploads/seller_gst/');
define('RETAILER_GST_IMG_PATH', 'uploads/retailer_gst/');

define('SELLER_GST_PDF_PATH', 'uploads/seller_gst_pdf/');
define('RETAILER_GST_PDF_PATH', 'uploads/retailer_gst_pdf/');

define('SELLER_PAN_IMG_PATH', 'uploads/seller_pan/');
define('RETAILER_PAN_IMG_PATH', 'uploads/retailer_pan/');

define('SELLER_PAN_PDF_PATH', 'uploads/seller_pan_pdf/');
define('RETAILER_PAN_PDF_PATH', 'uploads/retailer_pan_pdf/');

define('SELLER_FERT_LIC_IMG_PATH', 'uploads/seller_firt_lic/');
define('RETAILER_FERT_LIC_IMG_PATH', 'uploads/retailer_firt_lic/');

define('SELLER_FERT_LIC_PDF_PATH', 'uploads/seller_firt_lic_pdf/');
define('RETAILER_FERT_LIC_PDF_PATH', 'uploads/retailer_firt_lic_pdf/');

define('SELLER_PEST_LIC_IMG_PATH', 'uploads/seller_pest_lic/');
define('RETAILER_PEST_LIC_IMG_PATH', 'uploads/retailer_pest_lic/');

define('SELLER_PEST_LIC_PDF_PATH', 'uploads/seller_pest_lic_pdf/');
define('RETAILER_PEST_LIC_PDF_PATH', 'uploads/retailer_pest_lic_pdf/');

define('SELLER_SEEDS_LIC_IMG_PATH', 'uploads/seller_seeds_lic/');
define('RETAILER_SEEDS_LIC_IMG_PATH', 'uploads/retailer_seeds_lic/');

define('SELLER_SEEDS_LIC_PDF_PATH', 'uploads/seller_seeds_lic_pdf/');
define('RETAILER_SEEDS_LIC_PDF_PATH', 'uploads/retailer_seeds_lic_pdf/');

define('SELLER_AGRI_SERV_LIC_IMG_PATH', 'uploads/seller_agri_serv_lic/');
define('RETAILER_AGRI_SERV_LIC_IMG_PATH', 'uploads/retailer_agri_serv_lic/');

define('SELLER_AGRI_SERV_LIC_PDF_PATH', 'uploads/seller_agri_serv_lic_pdf/');
define('RETAILER_AGRI_SERV_LIC_PDF_PATH', 'uploads/retailer_agri_serv_lic_pdf/');

define('SELLER_AGRI_EQUIP_LIC_IMG_PATH', 'uploads/seller_agri_equip_lic/');
define('RETAILER_AGRI_EQUIP_LIC_IMG_PATH', 'uploads/retailer_agri_equip_lic/');

define('SELLER_AGRI_EQUIP_LIC_PDF_PATH', 'uploads/seller_agri_equip_lic_pdf/');
define('RETAILER_AGRI_EQUIP_LIC_PDF_PATH', 'uploads/retailer_agri_equip_lic_pdf/');

define('SELLER_OFORM_IMG_PATH', 'uploads/seller_o_form/');
define('RETAILER_OFORM_IMG_PATH', 'uploads/retailer_o_form/');

define('SELLER_QUALITY_COMPLIANCE_FILE_PATH', 'uploads/seller_quality_compliance_file/');
define('SELLER_AWARDS_RECOGNITION_FILE_PATH', 'uploads/seller_awards_recognition_file/');

define('PRODUCT_IMAGE_PATH','uploads/product_images/');
define('PRODUCT_OTHER_IMAGE_PATH','uploads/product_other_images/');

define('STATEMENT_PATH','uploads/statement_files/');

//Thumbnail paths
define('THUMB_MD', 'thumb-md/');
define('THUMB_SM', 'thumb-sm/');
define('CROPPED_MD', 'cropped-md/');
define('CROPPED_SM', 'cropped-sm/');

define('PERMISSION_ERROR_MSG', ' You are not authorize to operate on the module ');

// ticket status 
define('PENDING', '1');
define('OPENED', '2');
define('RESOLVED', '3');
define('CLOSED', '4');
define('REOPEN', '5');

// direct bank transfer

define('BANK_TRANSFER', 'Direct Bank Transfer');

// pincode delivarable type

define('NONE', '0');
define('ALL', '1');
define('INCLUDED', '2');
define('EXCLUDED', '3');
