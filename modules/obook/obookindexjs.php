<?php 
/**
 * 输出javascript格式的电子书目录
 *
 * 输出javascript格式的电子书目录
 * 
 * 调用模板：/modules/obook/templates/obookindexjs.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookindexjs.php 231 2008-11-27 08:46:26Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
if(empty($_REQUEST['aid'])) exit;
$linkfile=JIEQI_ROOT_PATH.'/files/obook/articlelink'.jieqi_getsubdir($_REQUEST['aid']).'/'.$_REQUEST['aid'].'.php';
if(file_exists($linkfile)){
	global $jieqiObookdata;
	include_once($linkfile);
	$ochapterrows=array();
	if($jieqiObookdata['obook']['display'] ==0){
		$i=0;
		foreach($jieqiObookdata['ochapter'] as $chapter){
			if($chapter['display']==0){
				$ochapterrows[$i]['ochapterid']=jieqi_htmlstr($chapter['ochapterid']);
				$ochapterrows[$i]['chaptername']=jieqi_htmlstr($chapter['chaptername']);
				$ochapterrows[$i]['saleprice']=jieqi_htmlstr($chapter['saleprice']);
				$ochapterrows[$i]['size']=jieqi_htmlstr($chapter['size']);
				$ochapterrows[$i]['postdate']=date('Y-m-d H:i:s', $chapter['postdate']);
				$i++;

			}
		}
		//包含包含模板类
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('obookid', jieqi_htmlstr($jieqiObookdata['obook']['obookid']));
		$jieqiTpl->assign('obookname', jieqi_htmlstr($jieqiObookdata['obook']['obookname']));
		$jieqiTpl->assign('articleid', intval($_REQUEST['aid']));
		$jieqiTpl->assign_by_ref('ochapterrows', $ochapterrows);
		$jieqiTpl->setCaching(0);
		if(empty($_REQUEST['convertjs'])){
			$jieqiTpl->display($jieqiModules['obook']['path'].'/templates/obookindexjs.html');
		}else{
			$data=$jieqiTpl->fetch($jieqiModules['obook']['path'].'/templates/obookindexjs.html');
			echo "document.write('".jieqi_setslashes($data)."');";
		}
	}
}
?>