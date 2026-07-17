<?php
	require_once dirname(__DIR__)."/vendor/autoload.php";
	require_once("SessionUtil.php");
	
	class TemplateUtil {

		private static $instance;
		private $twig;
		private static $translator = null;

		private final function  __construct() {
			$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__)."/templates");
			$this->twig = new \Twig\Environment($loader, [
				'cache' => false,
				'auto_reload' => true,
			]);
			$this->twig->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension(self::getTranslator()));

			// Cache-busting: append the file's modification time as ?v= so changed
			// assets are re-fetched automatically, instead of hand-bumped version
			// strings. Assets live under htdocs/ and are referenced by their URL path
			// (e.g. asset('/css/main.css')). Missing file => return the path unversioned
			// so the asset still loads.
			$this->twig->addFunction(new \Twig\TwigFunction('asset', function ($path) {
				$file = dirname(__DIR__) . '/htdocs' . $path;
				$mtime = @filemtime($file);
				return $mtime ? $path . '?v=' . $mtime : $path;
			}));
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
			if (self::$translator !== null) {
				return self::$translator;
			}

			$locale = SessionUtil::getInstance()->getLocale();
			$translator = new \Symfony\Component\Translation\Translator($locale);
			$translator->setFallbackLocales(['en']);

			if (!class_exists('\Symfony\Component\Translation\Loader\YamlFileLoader')) {
				error_log("Translation YAML loader class unavailable; locale resources cannot be loaded.");
				self::$translator = $translator;
				return self::$translator;
			}

			$translator->addLoader('yaml', new \Symfony\Component\Translation\Loader\YamlFileLoader());

			$supported_locales = ['en', 'es', 'de', 'ja', 'it', 'fr'];
			foreach ($supported_locales as $l) {
				$path = dirname(__DIR__) . '/locales/' . $l . '.yml';
				if (!is_file($path)) {
					continue;
				}

				try {
					if (class_exists('\Symfony\Component\Yaml\Yaml')) {
						$raw = @file_get_contents($path);
						if (!is_string($raw) || $raw === '') {
							throw new \RuntimeException("Locale file is empty or unreadable");
						}

						$sanitized = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
						$sanitized = preg_replace('/^\s*---\s*(\r?\n)/m', '', $sanitized);
						$sanitized = preg_replace('/^\s*\.\.\.\s*(\r?\n)/m', '', $sanitized);
						\Symfony\Component\Yaml\Yaml::parse($sanitized);
					}

					$translator->addResource('yaml', $path, $l);
				} catch (\Throwable $e) {
					error_log("Skipping invalid locale YAML [{$l}] at {$path}: " . $e->getMessage());
				}
			}

			self::$translator = $translator;
			return self::$translator;
		}

	}
?>
