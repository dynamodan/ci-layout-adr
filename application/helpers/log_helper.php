<?php

if(!function_exists('say') && !function_exists('dmp')&& !function_exists('warn')) {
	global $CI_LOGGER, $LOG_STRING;
	$CI_LOGGER = Logger::getLogger("main");
	$LOG_STRING = "";
	
	$LOG_CONFIGURATOR = new LoggerConfiguratorDefault();
	$CI_LOGGER->configure($LOG_CONFIGURATOR->parse(APPPATH.'config/config.log4php.xml'));
	
	 // so it works with cron, or running from not install dir
	Logger::getRootLogger()
		->getAppender("daily")
		->setFile(APPPATH."logs/ci-%s.txt");

	// static wrappers around the log4php functions
	function say($msg) {
		global $CI_LOGGER, $LOG_STRING;
		$bt = debug_backtrace();
		$msg = preg_replace('/\n+$/', '', $msg);
		
		LoggerMDC::put("LINE", $bt[0]['line']);
		LoggerMDC::put("FILE", $bt[0]['file']);
	
		$CI_LOGGER->info($msg);
		
		LoggerMDC::remove("LINE");
		LoggerMDC::remove("FILE");
		
		return $msg."\n";
	}

	function warn($msg) {
		global $CI_LOGGER, $LOG_STRING;
		$bt = debug_backtrace();
		$msg = preg_replace('/\n+$/', '', $msg);
		
		LoggerMDC::put("LINE", $bt[0]['line']);
		LoggerMDC::put("FILE", $bt[0]['file']);
	
		$CI_LOGGER->error($msg);	
	
		LoggerMDC::remove("LINE");
		LoggerMDC::remove("FILE");
		
		return $msg."\n";
	}

	function dmp($msg) {
		global $CI_LOGGER, $LOG_STRING;
		$bt = debug_backtrace();
		$msg = var_export($msg, true);
		
		LoggerMDC::put("LINE", $bt[0]['line']);
		LoggerMDC::put("FILE", $bt[0]['file']);
	
		$CI_LOGGER->info($msg);
		
		LoggerMDC::remove("LINE");
		LoggerMDC::remove("FILE");
		
		return $msg."\n";
	}
}

