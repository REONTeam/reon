<?php
	require_once dirname(__DIR__)."/vendor/autoload.php";
	require_once("SessionUtil.php");
	
	class TemplateUtil {

		private static $instance;
		private $twig;
		private $translator;

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
			$translator = self::getTranslator();
			$twig = self::$instance->twig;
			$twig->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator));
			$vars["curr_locale"] = SessionUtil::getInstance()->getLocale();
			return $twig->render($template.".twig", $vars);
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
