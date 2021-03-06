<?php
/**
 * 模块配置管理
 *
 * 配置模块名称、路径，访问的url等
 * 
 * 调用模板：/templates/admin/managemodules.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: managemodules.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
jieqi_checklogin();
if($jieqiUsersStatus != JIEQI_GROUP_ADMIN) jieqi_printfail(LANG_NEED_ADMIN);

//jieqi_getconfigs(JIEQI_MODULE_NAME, 'modules','jieqiModules'); //系统已经载入
jieqi_loadlang('modules', JIEQI_MODULE_NAME);
if(!empty($_REQUEST['dosubmit'])){
	foreach($_REQUEST['jieqiModules'] as $k=>$v) $jieqiModules[$k]=$v;
	$jieqiModules=$_REQUEST['jieqiModules'];
	//jieqi_setconfigs('modules','jieqiModules',$jieqiModules, JIEQI_MODULE_NAME);
	jieqi_save_modconfig($jieqiModules);
	jieqi_jumppage(JIEQI_URL.'/admin/managemodules.php',LANG_DO_SUCCESS,$jieqiLang['system']['modules_config_saved']);
}

include_once(JIEQI_ROOT_PATH.'/admin/header.php');

if(!isset($jieqiModules)) $jieqiModules=array();
$fileroot=JIEQI_ROOT_PATH.'/modules';
$handle = opendir($fileroot);
$changeflag=false;
//检测
while (false !== ($file = readdir($handle)))
{
	if($file[0] != '.' && is_dir($fileroot.'/'.$file))
	{
		if(!isset($jieqiModules[$file]))
		{
			$changeflag=true;
			$jieqiModules[$file]= array('caption'=>$file, 'dir'=>'', 'path'=>'', 'url'=>'', 'theme'=>'', 'publish'=>'1');
		}
	}
}
closedir($handle);
if($changeflag){
	//jieqi_setconfigs('modules','jieqiModules',$jieqiModules, JIEQI_MODULE_NAME);
	jieqi_save_modconfig($jieqiModules);
}

$fileroot=JIEQI_ROOT_PATH.'/themes';
$handle = opendir($fileroot);
$themes=array();
while (false !== ($file = readdir($handle)))
{
	if($file[0] !='.' && is_dir($fileroot.'/'.$file))
	{
		$themes[]=$file;
	}
}
closedir($handle);
$jieqiTpl->assign('themes',$themes);
$jieqiTpl->assign('jieqiModules',$jieqiModules);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/managemodules.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

//单独设置写配置文件规范
function jieqi_save_modconfig($jieqiModules){
	$file=JIEQI_ROOT_PATH.'/configs/modules.php';
	$data='<?php'."\r\n";
	foreach($jieqiModules as $k=>$v){
		$tmpvar = $k=='system' ? '' : '/modules/'.$k;
		if($v['dir']==$tmpvar) $v['dir']='';
		if($v['path']==JIEQI_ROOT_PATH.$tmpvar) $v['path']='';
		if($v['url']==JIEQI_LOCAL_URL.$tmpvar) $v['url']='';
		if($v['theme']==JIEQI_THEME_SET) $v['theme']='';
		
		$data.='$jieqiModules[\''.jieqi_setslashes($k, '"').'\'] = array(\'caption\'=>\''.jieqi_setslashes($v['caption'], '"').'\', \'dir\'=>\''.jieqi_setslashes($v['dir'], '"').'\', \'path\'=>\''.jieqi_setslashes($v['path'], '"').'\', \'url\'=>\''.jieqi_setslashes($v['url'], '"').'\', \'theme\'=>\''.jieqi_setslashes($v['theme'], '"').'\', \'publish\'=>\''.jieqi_setslashes($v['publish'], '"').'\');'."\r\n";
	}
	$data.='?>';
	jieqi_writefile($file, $data);
}
?>