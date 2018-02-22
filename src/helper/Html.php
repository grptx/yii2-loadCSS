<?php
/**
 * Created by PhpStorm.
 * User: gx
 * Date: 22/02/18
 * Time: 15.08
 */
namespace grptx\loadcss\helper;
use yii\helpers\Url;
use yii\web\JsExpression;

class Html extends \yii\helpers\Html {
	/**
	 * @inheritDoc
	 */
	public static function cssFile( $url, $options = [] ) {
		if (!isset($options['rel'])) {
			$options['rel'] = 'stylesheet';
		}
		$options['href'] = Url::to($url);

		if(isset($options['loadcss']) && $options['loadcss']) {

			unset($options['noscript']);
			unset($options['loadcss']);

			$options['rel']='preload';
			$options['as']='style';
			/** @var JsExpression $e */
			$e = new JsExpression('this.onload=null;this.rel=\"stylesheet\"');
			$options['onload']=$e->expression;

			$out = static::tag('link', '', $options);
			$out = "<link href=\"".$options['href']."\" rel=\"preload\" as=\"style\" onload=\"this.onload=null;this.rel='stylesheet'\">";


			$options['rel'] = 'stylesheet';
			unset($options['as']);
			unset($options['onload']);

			$out .= '<noscript>' . static::tag('link', '', $options) . '</noscript>';

			return $out;
		} else {
			if (isset($options['condition'])) {
				$condition = $options['condition'];
				unset($options['condition']);
				return self::wrapIntoCondition(static::tag('link', '', $options), $condition);
			} elseif (isset($options['noscript']) && $options['noscript'] === true) {
				unset($options['noscript']);
				return '<noscript>' . static::tag('link', '', $options) . '</noscript>';
			}

		}
		return static::tag('link', '', $options);
	}
	/**
	 * Wraps given content into conditional comments for IE, e.g., `lt IE 9`.
	 * @param string $content raw HTML content.
	 * @param string $condition condition string.
	 * @return string generated HTML.
	 */
	private static function wrapIntoCondition($content, $condition)
	{
		if (strpos($condition, '!IE') !== false) {
			return "<!--[if $condition]><!-->\n" . $content . "\n<!--<![endif]-->";
		}

		return "<!--[if $condition]>\n" . $content . "\n<![endif]-->";
	}
}