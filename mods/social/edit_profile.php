<?php
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_SOCIAL_INCLUDE.'friends.inc.php');
$_custom_css = $_base_path . 'mods/social/module.css'; // use a custom stylesheet

if (!$_SESSION['valid_user']) {
	require(AT_INCLUDE_PATH.'header.inc.php');
	$info = array('INVALID_USER', $_SESSION['course_id']);
	$msg->printInfos($info);
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

// Initiates member
$member = new Member($_SESSION['member_id']);

// Handles social profile 
if ($_POST['social_profile']){
	//update database from here
	header('Location: sprofile.php');
}


// Handles Saving
if (isset($_POST['submit'])){
	//where was this request sent from
	if (isset($_POST['edit'])){
			$id			 = intval($_POST['id']);
		if ($_POST['edit']=='position'){
			$company	 = $addslashes($_POST['company']);
			$title		 = $addslashes($_POST['title']);
			$from		 = intval($_POST['from']);
			$to			 = intval($_POST['to']);
			$description = $addslashes($_POST['description']);
			$member->updatePosition($id, $company, $title, $from, $to, $description);			
		} elseif ($_POST['edit']=='education'){
			$university	 = $addslashes($_POST['university']);
			$country	 = $addslashes($_POST['country']);
			$province	 = $addslashes($_POST['province']);
			$degree		 = $addslashes($_POST['degree']);
			$field		 = $addslashes($_POST['field']);
			$from		 = intval($_POST['from']);
			$to			 = intval($_POST['to']);
			$description = $addslashes($_POST['description']);
			$member->updateEducation($id, $university, $from, $to, $country, $province, $degree, $field, $description);
		} elseif ($_POST['edit']=='websites'){
			$url		= $addslashes($_POST['url']);
			$site_name	= $addslashes($_POST['site_name']);
			$member->updateWebsite($id, $url, $site_name);
		} elseif ($_POST['edit']=='interests' || $_POST['edit']=='associations' || $_POST['edit']=='awards' || $_POST['edit']=='expertise' 
					|| $_POST['edit']=='others'){
			$interests		= $addslashes($_POST['interests']);
			$associations	= $addslashes($_POST['associations']);
			$awards			= $addslashes($_POST['awards']);
			$expertise		= $addslashes($_POST['expertise']);
			$others			= $addslashes($_POST['others']);
			$member->updateAdditionalInformation($interests, $associations, $awards, $expertise, $others);
		}
	} 
	elseif (isset($_POST['add'])) {
		if ($_POST['add']=='position'){
			$company	 = $addslashes($_POST['company']);
			$title		 = $addslashes($_POST['title']);
			$from		 = intval($_POST['from']);
			$to			 = intval($_POST['to']);
			$description = $addslashes($_POST['description']);
			$member->addPosition($company, $title, $from, $to, $description);
		} elseif ($_POST['add']=='education'){
			$university	 = $addslashes($_POST['university']);
			$country	 = $addslashes($_POST['country']);
			$province	 = $addslashes($_POST['province']);
			$degree		 = $addslashes($_POST['degree']);
			$field		 = $addslashes($_POST['field']);
			$from		 = intval($_POST['from']);
			$to			 = intval($_POST['to']);
			$description = $addslashes($_POST['description']);
			$member->addEducation($university, $from, $to, $country, $province, $degree, $field, $description);
		} elseif ($_POST['add']=='websites'){
			$url		= $addslashes($_POST['url']);
			$site_name	= $addslashes($_POST['site_name']);
			$member->addWebsite($url, $site_name);
		}
	}
}


// Handles Adding
if (isset($_GET['add'])){
	//header starts here.
	include(AT_INCLUDE_PATH.'header.inc.php');
	if ($_GET['add']=='position'){
		$savant->display('edit_profile/add_position.tmpl.php');
	} elseif ($_GET['add']=='education'){
		$savant->display('edit_profile/add_education.tmpl.php');
	} elseif ($_GET['add']=='websites'){
		$savant->display('edit_profile/add_websites.tmpl.php');
	}
	//footer
	include(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

// Handles Editing
if (isset($_GET['edit']) && isset($_GET['id']) && (intval($_GET['id']) > 0)){
	$id = intval($_GET['id']);

	//header starts here.
	include(AT_INCLUDE_PATH.'header.inc.php');
	$savant->assign('id', $id);
	if ($_GET['edit']=='position'){
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'social_member_position WHERE id='.$id;
		$rs = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($rs);
		
		//Template
		$savant->assign('company', $row['company']);
		$savant->assign('title', $row['title']);
		$savant->assign('from', $row['from']);
		$savant->assign('to', $row['to']);
		$savant->assign('description', $row['description']);
		$savant->display('edit_profile/edit_position.tmpl.php');
	} elseif ($_GET['edit']=='education'){
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'social_member_education WHERE id='.$id;
		$rs = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($rs);

		//Template
		$savant->assign('university', $row['university']);
		$savant->assign('country', $row['country']);
		$savant->assign('province', $row['province']);
		$savant->assign('degree', $row['degree']);
		$savant->assign('field', $row['field']);
		$savant->assign('from', $row['from']);
		$savant->assign('to', $row['to']);
		$savant->assign('description', $row['description']);
		$savant->display('edit_profile/edit_education.tmpl.php');
	} elseif ($_GET['edit']=='websites'){
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'social_member_websites WHERE id='.$id;
		$rs = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($rs);

		//Template
		$savant->assign('url', $row['url']);
		$savant->assign('site_name', $row['site_name']);
		$savant->display('edit_profile/edit_websites.tmpl.php');

	} elseif ($_GET['edit']=='interests'){
		$sql = 'SELECT interests FROM '.TABLE_PREFIX.'social_member_additional_information WHERE member_id='.$_SESSION['member_id'];
		$rs = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($rs);

		//Template
		$savant->assign('interests', $row['interests']);
		$savant->assign('title', 'interests');
		$savant->display('edit_profile/edit_additional.tmpl.php');
	} elseif ($_GET['edit']=='associations'){
		$sql = 'SELECT associations FROM '.TABLE_PREFIX.'social_member_additional_information WHERE member_id='.$_SESSION['member_id'];
		$rs = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($rs);

		//Template
		$savant->assign('associations', $row['associations']);
		$savant->assign('title', 'associations');
		$savant->display('edit_profile/edit_additional.tmpl.php');
	} elseif ($_GET['edit']=='awards'){
		$sql = 'SELECT awards FROM '.TABLE_PREFIX.'social_member_additional_information WHERE member_id='.$_SESSION['member_id'];
		$rs = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($rs);

		//Template
		$savant->assign('awards', $row['awards']);
		$savant->assign('title', 'awards');
		$savant->display('edit_profile/edit_additional.tmpl.php');
	} 
	//footer
	include(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}


// Handles Deleting
if (isset($_GET['delete'])){
	$id	= intval($_GET['id']);
	if ($_GET['delete']=='position'){
		$member->deletePosition($id);
	} elseif ($_GET['delete']=='education'){
		$member->deleteEducation($id);
	} elseif ($_GET['delete']=='websites'){
		$member->deleteWebsite($id);
	}
}


// Member object
include(AT_INCLUDE_PATH.'header.inc.php');
$savant->assign('profile', $member->getDetails());
$savant->assign('position', $member->getPosition());
$savant->assign('education', $member->getEducation());
$savant->assign('websites', $member->getWebsites());
$savant->display('edit_profile.tmpl.php');
include(AT_INCLUDE_PATH.'footer.inc.php');
?>