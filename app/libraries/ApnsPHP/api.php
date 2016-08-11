<?php
/*
 * Created on Mar 25, 2009 DONE
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends MY_Controller {	
    function __construct() {
        parent::__construct();

        $this->template->set_template('login');
        $this->template->add_css('js/ajax/login.js');
        $this->template->add_css('css/css-login.css');

        $this->load->model('user/user_model', 'user');
        $this->load->model('child/child_info_model', 'childInfo');
        $this->load->model('company_info_model', 'companyInfo');
		$this->load->model('class/class_child_model', 'classChild');

        $this->load->model('yearterm_model', 'yearterm');
        $this->load->model('user/user_role_model', 'userRole');
        $this->load->model('customfield/kins_model', 'kins_model');
        $this->load->model('customfield/kin_child_model', 'kin_child_model');
        $this->load->model('login_token_model', 'login_token');
        $this->load->model('child/child_model', 'child_model');
        $this->load->model('recommendation_application_model', 'recommended_app');
        $this->load->model('subject_model', 'subject');
        $this->load->model('teacher_subject_model', 'teacher_subject');
        $this->load->helper('imageresizing');
        $this->load->model('lo_model','lo');
        $this->load->model('lo_child_model');
        $this->load->model('push_user_device_model', 'push_user_device');
        $this->load->model('push_user_device_type_model', 'push_user_device_type');
        $this->load->model('push_attempts_model', 'push_attempts');
    }

    public function change_language($language) {
        $cookie = array(
            'name' => 'slcLanguage',
            'value' => $language,
            'expire' => '86500'
        );
        set_cookie($cookie);
    }

    function api_login(){
        $email = isset($_REQUEST['email'])? trim($_REQUEST['email']):"";
        $password = isset($_REQUEST['password'])? trim($_REQUEST['password']):"";
        $result = array();
        //not enough param
        if (empty($email) || empty($password)){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
        
        $kin = $this->kins_model->getKinByEmailPass($email, $password);

        if ($kin){
            //insert a login token record
            $token = uniqid();
            $this->login_token->_insert($kin['kin_id'], $token);
            
            $result['code'] = 0;
            $result['msg'] = "Login successfully.";
            $result['token'] = $token;
            
        }else{
            $result['code'] = -5;
            $result['msg'] = "Invalid username or password.";
            $result['token'] = "";
        }
        return $result;
    }
    
    function api_get_account_info(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $kin_id = $this->login_token->isValidToken($token);
        
        $result = array();
        //not enough param
        if (empty($token)){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
        
        if ($kin_id){
            $result['code'] = 0;
            
            $kin = $this->kins_model->getKinNoRelation($kin_id);
            $childrenIDs = array();
            
            if ($kin){
                $result['accountID'] = $kin_id;
                $result['name'] = $kin['full_name'];
                $children = $this->kin_child_model->getMyChildren($kin_id);
                
                if (!empty($children)){
                    foreach($children as $child)
                        $childrenIDs[] = $child['child_id'];

                    $result['msg'] = "Get account information successfully.";
                }else{
                    $result['msg'] = "Currently you have no child.";
                }
            }else{
                $result['msg'] = "Could not get kin account information.";
            }
            
            $result['childIDs'] = $childrenIDs;
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    function api_add_child(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $childID = isset($_REQUEST['childID'])? trim($_REQUEST['childID']):"";
        $result = array();
        //not enough param
        if (empty($token) || empty($childID)){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
            
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            $child = $this->child_model->verifyChildInformation($childID);

            if ($child){
                if ($this->kin_child_model->isKinChildExist($kin_id,$child['childID'])){
                    $result['code'] = -104;
                    $result['msg'] = "Child existed.";
                    return $result;
                }else{
                    $data = array(
                        'kin_id' => $kin_id,
                        'child_id' => $child['childID']
                    );
                    $this->kin_child_model->_insert($data);

                    $result['msg'] = "Added child successfully.";
                }

            }
            else
                $result['msg'] = "Invalid child's information ";
            
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    function api_remove_child(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $childID = isset($_REQUEST['childID'])? trim($_REQUEST['childID']):"";
        $result = array();
        //not enough param
        if (empty($token) || empty($childID)){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
            
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            if (!$this->kin_child_model->isKinChildExist($kin_id,$childID)){
                $result['code'] = -10;
                $result['msg'] = "Invalid child id.";
                return $result;
            }else{
                $result['code'] = 0;
                $result['msg'] = "Removed child successfully.";
                $this->kin_child_model->deleteChild($kin_id, (int)$childID);
            }
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    public function api_get_year_term_list(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $result = array();
        //not enough param

        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            $result['msg'] = "Get year terms list successfully.";
            $termsDB = $this->yearterm->getYearTerm();
            $terms = array();
            if (!empty($termsDB))
                foreach($termsDB as $termDB){
                    $term = array();
                    $term['name'] = $termDB['year'] . "-"  .str_pad($termDB['term'],2,"0",STR_PAD_LEFT);;
                    $term['id'] = $termDB['id'];
                    $terms[] = $term;
                }
            $result['terms'] = $terms;
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
        
    }
    public function api_get_recommended_apps(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $result = array();
        //not enough param

        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            
            $apps = $this->recommended_app->getItems();
            if (empty ($apps))
                $result['msg'] = "No recommendation application is available at this moment.";
            else
                $result['msg'] = "Get year recommendation applications list successfully.";
            
            $result['recommended_apps'] = $apps;
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    function api_change_settings(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $is_receive_notify = isset($_REQUEST['receiveNoti'])? trim($_REQUEST['receiveNoti']):"";
        $is_receive_updates = isset($_REQUEST['receiveUpdates'])? trim($_REQUEST['receiveUpdates']):"";
        $result = array();
        //not enough param
        if (empty($token) || ($is_receive_notify == null || strlen($is_receive_notify)==0) || 
                ($is_receive_updates == null || strlen($is_receive_updates)==0)){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
            
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            $this->kins_model->change_settings($kin_id, $is_receive_notify, $is_receive_updates);
            $result['msg'] = "Changed account settings successfully.";
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    public function api_get_subject_info(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        
        $subject_id = isset($_REQUEST['subjectID'])? trim($_REQUEST['subjectID']):"";
        $year = isset($_REQUEST['yearID'])? trim($_REQUEST['yearID']):"";
        
        $result = array();
        //not enough param
        if (empty($token) || $subject_id =="" ||$year ==""){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
            
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            
            $subject = $this->subject->getSubjectName($subject_id);
            if (empty($subject))
                $result['msg'] = "Subject could not be found.";
            else{
                $result['title'] = $subject['name'];
                $teachersDB = $this->teacher_subject->get_teachers_by_subject_by_year($subject_id, $year);
                $teachers = array();
                if ($teachersDB)
                    foreach($teachersDB as $teacherDB){
                        $teachers[] = $teacherDB['name'];
                    }
                $result['teachers'] = $teachers;    
                    
                $coordinatorsDB = $this->user->get_subject_coordinators();
                $coordinators = array();
                if ($coordinatorsDB)
                    foreach($coordinatorsDB as $coordinatorDB){
                        $coordinators[] = $coordinatorDB['name'];
                    }
                $result['subjcoord'] = $coordinators;
            }
            
            $result['msg'] = "Get subject's information successfully.";
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
        
    }
    
    public function api_get_child_porfolio_images(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        
        $year_term_id = isset($_REQUEST['yearTermID'])? trim($_REQUEST['yearTermID']):"";
        $child_id = isset($_REQUEST['childID'])? trim($_REQUEST['childID']):"";
        
        $result = array();
        //not enough param
        if (empty($token) || $year_term_id =="" ||$child_id ==""){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
            
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            
            $year_term = $this->yearterm->new_findByID($year_term_id);
            if (!$year_term){
                $result['msg'] = "Year term could not be found.";
            }else{
                $image_root_thumbnail = IMAGE_ROOT_LOCAL_THUMBNAIL;
                $image = new SimpleImage();
                //echo $year_term['year'].$year_term['term']. $child_id;
                $year = $year_term['year'];
                $term = $year_term['term'];
                
                $images = array();
                $listImages = $this->lo->getPorfolioImages($year, $term, $child_id);
                foreach ($listImages as $item) {

                    $filePath = $item['urlBig'];     
                    $url_img = str_replace(array("/.././"),array("/"),FCPATH . "../" . $filePath);
                    // get file name
                    $part = explode("/", $url_img);
                    $filename = $part[count($part) - 1];
                    //get type image
                    $arr = explode(".", $filename);
                    //duong dan image thumbnail
                    $file_image_thumbnail = str_replace(array("\\"), array("/"), $image_root_thumbnail . "/" . $filename);
                    if (!file_exists($file_image_thumbnail) && file_exists($url_img)) {	
                        $image->load($url_img);
                        $image->scaleMax(250, 200);
                        $image->save("$file_image_thumbnail", $arr[1], 100);
                    }
                    if(file_exists($file_image_thumbnail))
                            $file_thumbnail = "images/parent/thumbnail/$filename";
                    else{
                            $file_thumbnail =trim($item['urlBig']);
                    }
                    $item['urlSmall'] = $file_thumbnail;
                    $images[] = $item;
                }
                $result['msg'] = "Get child's images successfully.";
                $result['images'] = $images;
            }
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
        
    }
    
    public function api_get_child_info(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $child_id = isset($_REQUEST['childID'])? trim($_REQUEST['childID']):"";
        
        $result = array();
        //not enough param
        if (empty($token) || $child_id == ""){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
            
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            
            $child_info = $this->childInfo->getItemByID($child_id);
            if (empty($child_info)){
                $result['code'] = -10;
                $result['msg'] = "Child could not be found.";
            }else{
                $result['msg'] = "Get child's information successfully.";
                $result['childID'] = $child_id;
                $result['childName'] = $child_info['name'];
                $result['childPhotoBig'] = $child_info['image'];
                $result['childPhotoSmall'] = $child_info['image'];
                $result['registrationDate'] = $child_info['date_enroll'];
                $result['childDesc'] = $child_info['name'];
                $result['dateOfBith'] = $child_info['dob'];
                $result['idNo'] = $child_info['id_num'];
                $result['gender'] = $child_info['gender'];
                
                $classANDCenter = $this->classChild->apiGetCenterANDClass($child_id);
                if ($classANDCenter){
                    $result['center'] = $classANDCenter['center'];
                    $result['level'] = $classANDCenter['level'];
                }else{
                    $result['center'] = $classANDCenter['center'];
                    $result['level'] = $classANDCenter['level'];
                }
                $result['recvNoti'] = $child_info['is_receive_notify'];
            }
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    function api_set_child_config(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $is_receive_notify = isset($_REQUEST['recvNoti'])? trim($_REQUEST['recvNoti']):"";
        $child_id = isset($_REQUEST['childID'])? trim($_REQUEST['childID']):"";
        $result = array();
        //not enough param
        if (empty($token) || $is_receive_notify == "" || $child_id == ""){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
            
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            $this->childInfo->update_receive_notify_setting($child_id, $is_receive_notify);
            $result['msg'] = "Changed child's settings successfully.";
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    public function api_add_push_user_device(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $parent_account_id = isset($_REQUEST['ParentAccountID'])? trim($_REQUEST['ParentAccountID']):"";
        $device_type = isset($_REQUEST['DeviceType'])? trim($_REQUEST['DeviceType']):"";
        $device_push_token = isset($_REQUEST['DevicePushToken'])? trim($_REQUEST['DevicePushToken']):"";
        $is_token_for_debug = isset($_REQUEST['IsTokenForDebug'])? trim($_REQUEST['IsTokenForDebug']):"";
        $result = array();
        
        
        //not enough param
        if (empty($token) || $parent_account_id == "" || $device_type == "" ||
                $device_push_token == "" || $is_token_for_debug == ""){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
        
        if (!$this->kins_model->is_kin_exist($parent_account_id)){
            $result['code'] = -201;
            $result['msg'] = "The parent account ID cannot be found.";
            return $result;
        }
        
        if (!$this->push_user_device_type->is_device_type_exist($device_type)){
            $result['code'] = -202;
            $result['msg'] = "The device type is unrecognized.";
            return $result;
        }
        
        if ($this->push_user_device->is_push_token_user_exist($device_push_token, $parent_account_id)){
            $result['code'] = -203;
            $result['msg'] = "The device push token already exists for the particular user.";
            return $result;
        }
        
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            $this->push_user_device->add_push_user_device($parent_account_id, 
                    $device_type, $device_push_token,$is_token_for_debug);
            $result['DeviceID'] = $this->push_user_device->get_max_id();
            
            $result['msg'] = "Added user push device successfully.";
        }else{
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    function api_remove_push_user_device(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $device_id = isset($_REQUEST['DeviceID'])? trim($_REQUEST['DeviceID']):"";
        $result = array();
        //not enough param
        if (empty($token) || empty($device_id)){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
            
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            $result['msg'] = "Removed push user device successfully.";
            $this->push_user_device->remove_push_user_device($device_id);
        }else{
            //$this->_write_header();
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    public function api_push_get_all_devices(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $parent_account_id = isset($_REQUEST['ParentAccountID'])? trim($_REQUEST['ParentAccountID']):"";
        $result = array();
        
        
        //not enough param
        if (empty($token) || $parent_account_id == ""){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
        
        if (!$this->kins_model->is_kin_exist($parent_account_id)){
            $result['code'] = -201;
            $result['msg'] = "The parent account ID cannot be found.";
            return $result;
        }
        
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            $result['PushDeviceInfo'] = $this->push_user_device
                                    ->get_all_push_devices($parent_account_id);
            
            $result['msg'] = "Get user's all devices list successfully.";
        }else{
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    public function api_push_send_message_to_user(){		
		require_once 'ApnsPHP/Abstract.php';
		require_once 'ApnsPHP/Exception.php';
		require_once 'ApnsPHP/Feedback.php';
		require_once 'ApnsPHP/Message.php';
		require_once 'ApnsPHP/Log/Embedded.php';
		require_once 'ApnsPHP/Log/Interface.php';
		require_once 'ApnsPHP/Message/Custom.php';
		require_once 'ApnsPHP/Message/Exception.php';
		require_once 'ApnsPHP/Push/Exception.php';
		require_once 'ApnsPHP/Push/Server.php';
		require_once 'ApnsPHP/Push/Server/Exception.php';
		
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $parent_account_id = isset($_REQUEST['ParentAccountID'])? trim($_REQUEST['ParentAccountID']):"";
        $push_message = isset($_REQUEST['PushMessage'])? trim($_REQUEST['PushMessage']):"";
        $push_message_params = isset($_REQUEST['PushMessageParams'])? trim($_REQUEST['PushMessageParams']):"";
        $result = array();
        
        
        //not enough param
        if ($parent_account_id == "" || $push_message == "" ||
                $push_message_params == ""){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
        
        if (!$this->kins_model->is_kin_exist($parent_account_id)){
            $result['code'] = -201;
            $result['msg'] = "The parent account ID cannot be found.";
            return $result;
        }
        
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            
            $devices = $this->push_user_device->get_all_push_devices($parent_account_id);
            if (!$devices || count($devices)==0){
                $result['code'] = -206;
                $result['msg'] = "The parent does not have any devices to push.";
                return $result;
            }
            
            $batchID = time();
            $addedTime = new DateTime();
            $attemptedTime = $addedTime->format("Y-m-d H:i:s");
            
            foreach($devices as $device){								
				if ($device['isTokenForDebug'] > 0)
				{
					$push = new ApnsPHP_Push(
					ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
					'./apns.pem'
					);
				}
				else
				{
					$push = new ApnsPHP_Push(
						ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
						'./apns-P.pem'
					);
				}
				
				$push->connect();
				$message = new ApnsPHP_Message($device['deviceID']);
				$message->setBadge(1);
				$message->setText($push_message);
				$message->setSound();
				$message->setCustomProperty('acme2', array('bang', 'whiz'));
				$message->setCustomProperty('acme3', array('bing', 'bong'));
				$message->setExpiry(30);
				$push->send();
				$push->disconnect();
				
				// Examine the error message container
				$aErrorQueue = $push->getErrors();
				if (!empty($aErrorQueue)) {
					var_dump($aErrorQueue);
				}
				
                $data = array(
                    'BatchPushID' => $batchID,
                    'PushDeviceID' => $device['deviceID'],
                    'TimeAttempted' => $attemptedTime,
                    'parentAccountID' => $parent_account_id,
                    'PushMessage' => $push_message,
                    'PushMessageParams' => $push_message_params);
                
                $this->push_attempts->_insert($data);
            }
            
            $result['PushID'] = $batchID;
            $result['msg'] = "Sent push message to user successfully.";
        }else{
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    public function api_push_check_status(){
        $token = isset($_REQUEST['token'])? trim($_REQUEST['token']):"";
        $push_id = isset($_REQUEST['PushID'])? trim($_REQUEST['PushID']):"";
        $result = array();
        
        
        //not enough param
        if (empty($token) || $push_id == ""){
            $result['code'] = -3;
            $result['msg'] = "Not enough parametter.";
            return $result;
        }
        
        $kin_id = $this->login_token->isValidToken($token);
        
        if ($kin_id){
            $result['code'] = 0;
            $pushStatuses= $this->push_attempts
                                    ->getItemsByBatchPushID($push_id);
            if (empty($pushStatuses)){
                $result['code'] = -205;
                $result['msg'] = "The Push ID cannot be found.";
                return $result;
            }else{
                $result['PushMessageStatus'] = $pushStatuses;
                $result['msg'] = "Checked push status successfully.";
                return $result;
            }
            
        }else{
            $result['code'] = -2;
            $result['msg'] = "Invalid token.";
        }
        return $result;
    }
    
    public function index($error = 0) {
        
        $action = isset($_REQUEST['action'])?$_REQUEST['action']:null;
        if ($action){
            switch($action){
                case 'IMS2Login':
                    $result = $this->api_login();
                    break;
                case 'IMS2GetAccountInfo':
                    $result = $this->api_get_account_info();
                    break;
                case 'IMS2AddChild':
                    $result = $this->api_add_child();
                    break;
                case 'IMS2RemoveChild':
                    $result = $this->api_remove_child();
                    break;
                case 'IMS2GetYearTermList':
                    $result = $this->api_get_year_term_list();
                    break;
                case 'IMS2GetRecommendedApps':
                    $result = $this->api_get_recommended_apps();
                    break;
                case 'IMS2ChangeSettings':
                    $result = $this->api_change_settings();
                    break;
                case 'IMS2GetSubjectInfo':
                    $result = $this->api_get_subject_info();
                    break;
                case 'ChildGetGallery':
                    $result = $this->api_get_child_porfolio_images();
                    break;
                case 'ChildGetInfo':
                    $result = $this->api_get_child_info();
                    break;
                case 'ChildSetConfig':
                    $result = $this->api_set_child_config();
                    break;
                case 'PushAddDevice':
                    $result = $this->api_add_push_user_device();
                    break;
                case 'PushRemoveDevice':
                    $result = $this->api_remove_push_user_device();
                    break;
                case 'PushGetAllDevices':
                    $result = $this->api_push_get_all_devices();
                    break;
                case 'PushSendMessageToUser':
                    $result = $this->api_push_send_message_to_user();
                    break;
                case 'PushCheckStatus':
                    $result = $this->api_push_check_status();
                    break;
                default:
                    $result['code'] = -1;
                    $result['msg'] = "API method not found.";
                    break;
            }
            if (isset($result))
                echo json_encode($result); 
        }
    }
        
    public function write_header(){
        header("POST /request HTTP/1.1");
        header("Accept: application/jsonrequest");
        header("Content-Encoding: identity");
        header("Content-Length: XXXX");
        header("Content-Type: application/jsonrequest");
        header("Host: json.penzance.org");        
    }

    function checkLogin() {
        $this->session->sess_destroy();
        $message = '';
        //$parent_homepage = site_url(HOMEPAGE_PARENT);
        //$staff_homepage = site_url(HOMEPAGE_STAFF);

        $this->language = $this->input->post('slcLanguage');

        $cookie = array(
            'name' => 'slcLanguage',
            'value' => $this->language,
            'expire' => '86500'
        );


        set_cookie($cookie);


        $who = '';
        $username = $this->input->post('txtUsername');
        $pass = $this->input->post('txtPassword');

        if (!empty($username) AND !empty($pass)) { // Emty => not allowed
            $user = $this->user->getInfo($username, $pass);

            if (!empty($user)) {
                //staff
                $who = 1;
            } else {
                $user = $this->childInfo->getInfo($username, $pass);
                
                if (!empty($user))
                    $who = 2;
                else {
                    $kin = $this->kins_model->getKinByEmailPass($username, $pass);

                    if ($kin){
                        $this->session->set_userdata('userID', $kin['kin_id']);
                        $this->session->set_userdata('password', $kin['password']);
                        
                        $this->session->set_userdata('kin_id', $kin['kin_id']);
                        $this->session->set_userdata('kin', $kin);
                        
                        $who = ACTOR_PARENT;
                        $this->session->set_userdata('who', $who);
                        
                        $parent_homepage = 'parent/home/home_new';
                        $this->session->set_userdata('home_page', $parent_homepage);
                        
                        redirect(base_url() . $parent_homepage);
                        //$user = $this->childInfo->getItemByID($kin['child_id']);
                        
                        //if (!empty($user)){
                        //}
                    }
                }
            }

            if ($who == 1) {
                $result = $this->yearterm->findByCurrent(1);
                if ($user) {
                    $role = $this->user_role->getItem(array(
                                'user_id' => $user['id'],
                                'order_by' => 'company_name'
                            ));
                    if ($role) {
                        $user_role = $role[0];
                        $this->session->set_userdata('role_id', $user_role['id']);
                        $this->session->set_userdata('roleID', $user_role['role_id']);
                        // $this->session->set_userdata('companyID', $user_role['company_id']);
                        $company = $this->companyInfo->getItemByID($user_role['company_id']);
                    } else {
                        $company = $this->companyInfo->getItem(array('id' => $user['company_id']));
                        $company = $company[0];
                    }

                    $this->session->set_userdata('companyName', $company['name']);
                    $this->session->set_userdata('companyID', $company['id']);

                    if ($company) {
                        $this->session->set_userdata('logo', LOGO_ROOT . $company['logo']);
                    } else {
                        $this->session->set_userdata('logo', SITE_URL . 'css/img/logo.gif');
                    }

                    $this->session->set_userdata('userID', $user['id']);
                    $this->session->set_userdata('username', $user['username']);
                    $this->session->set_userdata('name', $user['name']);
                    $this->session->set_userdata('email', $user['email']);
                    $this->session->set_userdata('userType', 0);
                    $this->session->set_userdata('level', $user['level']);
                    $this->session->set_userdata('who', $who);
                    $this->session->set_userdata('cyear', $result->year);
                    $this->session->set_userdata('cterm', $result->term);
                    $this->session->set_userdata('cYearTermId', $result->id);
                    if($this->session->userdata('current_url')){                        
                        $staff_homepage = $this->session->userdata('current_url');                        
                    }else{
                        $staff_homepage = $this->getHomePage();
                    }                    
                    // echo $this->user_role->get_first_module_has_role($user_role['role_id']);
                    // echo $staff_homepage;
                    // die();
                    //LTT added

                    $this->session->set_userdata('home_page', $staff_homepage);
                    if ($this->session->userdata('callback')) {
                        redirect($this->session->userdata('callback'));
                    } else {
                        redirect($staff_homepage);
                    }
                }
            } else {
                if ($who == ACTOR_PARENT){
                    
                }elseif ($user) {
                    $logo = '';
                    $result = $this->yearterm->findByCurrent(1);
                    $year = $result->year;
                    $term = $result->term;
                    $childID = $user['id']; //$this->session->userdata('userID');
                    $child = $this->childInfo->getInfoClassByUserID($childID, $year, $term);
                    $companyID = $child['company_id'];
					
                    if ($companyID) {
                        $company = $this->companyInfo->getItemByID($companyID);
                        if ($company)
                            $logo = trim($company['logo']);
                    }

                    if ($logo != ''){
                        if( strpos($logo, 'files') !== 0 )
                            $logo = LOGO_ROOT . $logo;
                        else
                            $logo = SITE_URL . $logo;
                    }else
                        $logo = SITE_URL . 'css/img/mainlogo.jpg';

                    $this->session->set_userdata('logo', $logo);



                    $this->session->set_userdata('userID', $user['id']);
                    $this->session->set_userdata('name', $user['name']);
                    $this->session->set_userdata('username', $user['id_num']);
                    $this->session->set_userdata('roleID', 0);
                    $this->session->set_userdata('companyID', $companyID);
                    $this->session->set_userdata('cyear', $result->year);
                    $this->session->set_userdata('cterm', $result->term);
                    $this->session->set_userdata('cYearTermId', $result->id);
					// load latest photo profile
					$latest_photo = $this->childInfo->getLatestPhoto($user['id'], $result->year);
                    if(isset($latest_photo['filePath']))
					   $user['image'] = $latest_photo['filePath'];
                    else
                       $user['image'] = "images/avatar_default.gif";
                       
					//end load latest photo profile
                    $imagePath = $user['image'];
                    if (strpos($imagePath, 'files') !== 0) {
                        $imagePath = 'files/children/' . $imagePath;
                    }
                    
                    $this->session->set_userdata('image', $user['image']);
                    $this->session->set_userdata('who', $who);

                    $this->session->set_userdata('userType', 1);
                    
                    $parent_homepage = 'parent/home';
                    $this->session->set_userdata('home_page', $parent_homepage);

                    redirect($parent_homepage);
                }
            }
        }


        redirect(site_url('login/index/1'));
    }

    function checkloginajax() {
        if (!$this->boolcheckLogin()) {
            echo -1;
            return;
        }
        echo 1;
    }

}

?>
