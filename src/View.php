<?php
/**
 * Created by PhpStorm.
 * User: gx
 * Date: 22/02/18
 * Time: 15.04
 */

namespace grptx\loadcss;


use grptx\loadcss\helper\Html;
use Yii;
use yii\helpers\ArrayHelper;

use yii\web\AssetBundle;

class View extends \rmrevin\yii\minify\View {
	public function registerCssFile( $url, $options = [], $key = null ) {
		$url = Yii::getAlias($url);
		$key = $key ?: $url;

		$depends = ArrayHelper::remove($options, 'depends', []);

		if (empty($depends)) {
			$this->cssFiles[$key] = Html::cssFile($url, $options);
		} else {
			$this->getAssetManager()->bundles[$key] = Yii::createObject([
				'class' => AssetBundle::className(),
				'baseUrl' => '',
				'css' => [strncmp($url, '//', 2) === 0 ? $url : ltrim($url, '/')],
				'cssOptions' => $options,
				'depends' => (array) $depends,
			]);
			$this->registerAssetBundle($key);
		}
	}

}