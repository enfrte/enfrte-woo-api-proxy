<?php

namespace Enfrte\WooApiProxy\Libs;

use Latte;

class LatteEngine extends Latte\Engine {

	/**
	 * @var string
	 */
	protected $templateDir = '';

	/**
	 * @var string
	 */
	protected $templatePath = '';

	
    public function __construct() {
		parent::__construct();

		// Directory where .latte templates live
        $this->templateDir = dirname(__DIR__) . '/templates';

		// Set the temporary directory for compiled templates
		$this->setTempDirectory($this->templateDir . '/temp');

		// Enable auto-refresh for development mode. It recompiles templates on every request.
		$this->setautoRefresh( getenv('APP_ENV') !== 'production' ); // or maybe WP_DEBUG
    }

	
	/**
	 * @param string $file
	 * @return string
	 */
	protected function getTemplatePath(string $file): string {
        return $this->templateDir . '/' . ltrim($file, '/');
    }

	
	/**
	 * Generates a string from a template for WP_REST_Response
	 *
	 * @param string $file
	 * @param array $data
	 * @return string
	 */
	public static function latteRenderToString( string $file, array $data = [] ): string  {
		$le = new static();
		return $le->renderToString($le->getTemplatePath($file), $data);
	}

	// WP_REST_Response needs to be passed a string, this returns null, so it's not really usable with WP_REST_Response
	// public static function latteRender( string $file, array $data = [] ) {
	// 	$le = new static();
	// 	return $le->render($le->getTemplatePath($file), $data);
	// }


}
