<?php
class TestController extends Yaf_Controller_Abstract {

	public function init()
	{

		$basedir = $_SERVER[ 'DOCUMENT_ROOT'];
		Yaf_Loader::import($basedir . '/application/functions/request.function.php');

	}

	public function indexAction()
	{//默认Action

		$date = date('Y.m.d');
		$this->getView()->assign("title", "周报");
		$this->getView()->assign("weekly_head", "周报-{$date}");
   	}

	public function sendAction()
	{
		Yaf_Dispatcher::getInstance()->disableView();
		header('Content-Type: application/json; charset=utf-8');

		$_POST = trim_array($_POST);

		if ( empty($_POST['emailto']) ) {
			die(json_encode(array('errno' => '10004', 'errmsg'=>'邮箱不能为空')));
		}

		$head = $_POST['username'] . '周报-' . date('Y.m.d');
		$filename = $_POST['username'] . '周报' . date('Ymd');

		$weekly = new WeeklyModel($_POST['username']);
                $weekly->setHead($head);

		$weekly->setTitle('线上故障报备');
		$weekly->setContent($_POST['trouble']);

		$weekly->setTitle('本周工作情况');
		for ( $i = 1; $i <= $_POST['proj_total']; $i ++ ) {
			$proj_key = 'project' . $i;
			$whodid_key = 'whodid' . $i;
			$status_key = 'status' . $i;
			$detail_key = 'detail' . $i;
			if ( !empty($_POST[$proj_key]) && !empty($_POST[$whodid_key]) && !empty($_POST[$status_key]) && !empty($_POST[$detail_key]) ) {
				$weekly->setSTitle($_POST[$proj_key]);
				$weekly->setContent('负责人：' . $_POST[$whodid_key]);
				$weekly->setContent('状态：' . $_POST[$status_key]);
				$weekly->setContent($_POST[$detail_key]);
			}
		}

		$weekly->setTitle('下周工作计划');
		for ( $i = 1; $i <= $_POST['plan_total']; $i ++ ) {
			$plan_key = 'plan' . $i;
			if ( !empty($_POST[$plan_key]) ) {
				$weekly->setTTitle($_POST[$plan_key]);
			}
		}

		$weekly->setTitle('工作感慨');
		$weekly->setContent($_POST['essay']);

		$weekly->setTail();
                $path = $weekly->create_doc_xml_file($filename);

		mail::send($_POST['emailto'], $path);

		die(json_encode(array('rst' => 1)));
	}

	public function downloadAction()
	{

		Yaf_Dispatcher::getInstance()->disableView();

		$_GET = trim_array($_GET);

		if ( empty($_GET['username']) ) {
			exit;
		}
		$filename = $_GET['username'] . '周报' . date('Ymd');
		$path = '/alidata1/weekly/docx/' . md5($_GET['username']) . '/' . $filename . '.docx';

		//下载
		if ( file_exists($path) ) {
			header('Content-Description: File Transfer');

			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.mail::get_basename($path));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			readfile($path);
		}
		exit();
	}

	public function testAction()
	{
		Yaf_Dispatcher::getInstance()->disableView();
		var_dump('adadfadf');exit;
		/*
		header('Content-Type: application/json; charset=utf-8');
		mail::send('luxiaotong713@sina.com', '/alidata1/weekly/docx/441f8affc404d5dbbb81305dee3624a4/逯晓瞳周报20140226.docx');
		$rst = array();
		die(json_encode($rst));
		*/

		$path = '/alidata1/weekly/docx/441f8affc404d5dbbb81305dee3624a4/逯晓瞳周报20140307.docx';
		header('Content-Description: File Transfer');

                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename='.mail::get_basename($path));
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($path));
                        readfile($path);
	}
}
