<?php
	require_once dirname(__DIR__)."/vendor/autoload.php";
	require_once("SessionUtil.php");
	
	class TemplateUtil {

		private static $instance;
		private $twig;

		private final function  __construct() {
			$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__)."/templates");
			$this->twig = new \Twig\Environment($loader, [
				'cache' => dirname(__DIR__)."/tmp",
			]);
		}

		public static function render($template, $vars = null) {
			if(!isset(self::$instance)) {
				self::$instance = new TemplateUtil();
			}
			if (!isset($vars)) $vars = array();
			$vars["session_active"] = SessionUtil::getInstance()->isSessionActive();
			return self::$instance->twig->render($template.".twig", $vars);
		}
	}
?>
