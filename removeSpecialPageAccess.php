<?php
 /**
 * @author Frogg <admin@frogg.fr>
 * @copyright wiki.frogg.fr © 2015,
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'removeSpecialPageAccess',
	'description' => 'remove access to Special Page & Purge from anonymous',
	'author' => 'admin@frogg.fr',
	'version' => '0.0.1',
	//AT WORK 'url' => 'https://www.mediawiki.org/wiki/Extension:Example',
	'descriptionmsg' => 'remove access to Special Page & Purge from anonymous'
);

function removeSpecialPageAccess(){
//Init Global
global $wgContLang,$wgUser,$wgRSPAallowedGrp;

//Init vars
$connPage	= $wgContLang->mExtendedSpecialPageAliases["Userlogin"][0];
$specPage	= $wgContLang->getNsText(-1);
$chkSO		= false;
$pInfo		= isset($_SERVER["PATH_INFO"])?$_SERVER["PATH_INFO"]:'';
$pUri		= isset($_SERVER["QUERY_STRING"])?$_SERVER["QUERY_STRING"]:'';

//check if user defined RSPA Users group
if(!isset($wgRSPAallowedGrp)){$wgRSPAallowedGrp=['sysop'];}

//if not in allowed groups
if(count(array_intersect($wgRSPAallowedGrp,$wgUser->getEffectiveGroups()))==0)
	{
	//getPath info
	 $pUri=urldecode($pUri);

	//Case Special Page
	if( (stripos($pInfo,"/".$specPage.":")!==false || stripos($pUri,$specPage.":")!==false)
			&& stripos($pInfo,$specPage.":".$connPage)===false
			&& stripos($pUri,$connPage)===false
			&& stripos($pUri,"search=")===false
			&& stripos($pUri,$specPage.":ConfirmEmail")===false
			&& stripos($pUri,$specPage.":Preferences")===false
			&& stripos($pUri,$specPage.":BannerLoader")===false
	) {$chkSO=true;}

	//Case Purge
	if(stripos($pUri,"action=purge")!==false){$chkSO=true;}

	/*
	TODO:
	confirm email,...
	*/
	}

//Do the check
if($chkSO){(stripos($pUri,"special:")!==false)?header('Location:./?title=Special:' . $connPage):header('Location:../?title=Special:'. $connPage);}
}

//add script to hooks
$wgHooks['SpecialPage_initList'][]='removeSpecialPageAccess';
?>
