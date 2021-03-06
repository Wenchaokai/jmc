<?php 
/**
 * 快钱神州行-提交处理
 *
 * 快钱神州行-提交处理 (http://www.99billv2.com)
 * 
 * 调用模板：/modules/pay/templates/99card.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 99card.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', '99card');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
if(!jieqi_checklogin(true)) jieqi_printfail($jieqiLang['pay']['need_login']);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

if(isset($_REQUEST['egold']) && is_numeric($_REQUEST['egold']) && $_REQUEST['egold']>0){
	$_REQUEST['egold']=intval($_REQUEST['egold']);
	if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
		if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$_REQUEST['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$_REQUEST['egold']] * 100);
		else jieqi_printfail($jieqiLang['pay']['buy_type_error']);
	}else{
		$money=intval($_REQUEST['egold']);
	}
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$paylog= $paylog_handler->create();
	$paylog->setVar('siteid', JIEQI_SITE_ID);
	$paylog->setVar('buytime', JIEQI_NOW_TIME);
	$paylog->setVar('rettime', 0);
	$paylog->setVar('buyid', $_SESSION['jieqiUserId']);
	$paylog->setVar('buyname', $_SESSION['jieqiUserName']);
	$paylog->setVar('buyinfo', '');
	$paylog->setVar('moneytype', $jieqiPayset[JIEQI_PAY_TYPE]['moneytype']);
	$paylog->setVar('money', $money);
	$paylog->setVar('egoldtype', $jieqiPayset[JIEQI_PAY_TYPE]['paysilver']);
	$paylog->setVar('egold', $_REQUEST['egold']);
	$paylog->setVar('paytype', JIEQI_PAY_TYPE);
	$paylog->setVar('retserialno', '');
	$paylog->setVar('retaccount', '');
	$paylog->setVar('retinfo', '');
	$paylog->setVar('masterid', 0);
	$paylog->setVar('mastername', '');
	$paylog->setVar('masterinfo', '');
	$paylog->setVar('note', '');
	$paylog->setVar('payflag', 0);
	if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['add_paylog_error']);
	else{
		$amount=$money / 100;
		$merchant_id = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //商户编号
		$orderid = $paylog->getVar('payid');     //订单编号[商户网站]
		$currency = $jieqiPayset[JIEQI_PAY_TYPE]['currency'];    //货币类型 1为人民币 3为神州行卡
		$merchant_url = $jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];   //商家接受支付结果的URL
		$commodity_info = urlencode(JIEQI_EGOLD_NAME);
		$pname = urlencode($_SESSION['jieqiUserName']);
		$pemail = $jieqiPayset[JIEQI_PAY_TYPE]['pemail'];
		$merchant_param = $jieqiPayset[JIEQI_PAY_TYPE]['merchant_param'];
		$isSupportDES = $jieqiPayset[JIEQI_PAY_TYPE]['isSupportDES']='2';  //是否安全校验,1不校验 2为必校验,推荐
		$key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];  //私钥值。即商户登录99BILL快钱系统后设定的 商户密钥
		$pid_99billaccount=$jieqiPayset[JIEQI_PAY_TYPE]['pid_99billaccount'];		//代理/合作伙伴商户编号
		//注意正确的参数串拼凑顺序
		$text="merchant_id=".$merchant_id."&orderid=".$orderid."&amount=".$amount."&merchant_url=".$merchant_url."&merchant_key=".$key;
		//md5加密
		$mac = strtoupper(md5($text)); //对参数串进行私钥加密取得值

		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', sprintf('%0.2f', $money / 100));

		$jieqiTpl->assign('merchant_id', $merchant_id);
		$jieqiTpl->assign('orderid', $orderid);
		$jieqiTpl->assign('amount', $amount);
		$jieqiTpl->assign('currency', $currency);
		$jieqiTpl->assign('merchant_url', $merchant_url);
		$jieqiTpl->assign('commodity_info', $commodity_info);
		$jieqiTpl->assign('pname', $pname);
		$jieqiTpl->assign('pemail', $pemail);
		$jieqiTpl->assign('merchant_param', $merchant_param);
		$jieqiTpl->assign('isSupportDES', $isSupportDES);
		$jieqiTpl->assign('pid_99billaccount', $pid_99billaccount);
		$jieqiTpl->assign('mac', $mac);
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/99card.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>