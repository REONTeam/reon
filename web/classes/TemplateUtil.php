<?php
	require_once dirname(__DIR__)."/vendor/autoload.php";
	require_once("SessionUtil.php");
	
	class TemplateUtil {

		private static $instance;
		private $twig;
		private $translator;

		private final function  __construct() {
			$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__)."/templates");
			$cacheDir = sys_get_temp_dir() . "/reon_twig_cache";
			if (!is_dir($cacheDir)) {
				mkdir($cacheDir, 0755, true);
			}
			$this->twig = new \Twig\Environment($loader, [
				'cache' => $cacheDir,
			]);
			$this->twig->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension(self::getTranslator()));
		}

		public static function render($template, $vars = null) {
			if(!isset(self::$instance)) {
				self::$instance = new TemplateUtil();
			}
			if (!isset($vars)) $vars = array();
			$vars["session_active"] = SessionUtil::getInstance()->isSessionActive();
			$vars["curr_locale"] = SessionUtil::getInstance()->getLocale();
			return self::$instance->twig->render($template.".twig", $vars);
		}

		public static function translate(?string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string {
			return self::getTranslator()->trans($id, $parameters, $domain, $locale);
		}

		public static function getTranslator() {
			$locale = SessionUtil::getInstance()->getLocale();
			$translator = new \Symfony\Component\Translation\Translator($locale);
			$translator->setFallbackLocales(['en']);
			$translator->addLoader('yaml', new \Symfony\Component\Translation\Loader\YamlFileLoader());
			$supported_locales =  ['en', 'es', 'de', 'ja', 'it', 'fr'];
			foreach($supported_locales as $l) {
				$translator->addResource('yaml',  dirname(__DIR__).'/locales/'.$l.'.yml', $l);
			}
			return $translator;
		}

	}
?>
