<?php

namespace maks757\friendly;

use Exception;
use maks757\friendly\components\IUrlRules;
use yii\base\Object;

use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;


/**
 * @property Object $model
 * @property string $url_key
 * @property string $action_key
 * @property string $controller_and_action
 * @property string $action
 */
class UrlRules extends Object implements UrlRuleInterface
{
    public $controller_and_action;
    public $action;
    public $model;
    public $routes = [];

    private $level;

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     *
     * @throws Exception
     */
    public function parseRequest($manager, $request)
    {
        if (empty($this->action) || empty($this->controller_and_action)) {
            throw new \Exception('Class ' . UrlRules::className() . ' parameter exception model, action or controller_and_action.');
        }

        $this->level = count(explode('/', \Yii::$app->getRequest()->url)) - count($this->routes) - 1;
        $pathInfo = explode('/', $request->getPathInfo());

        $link_url = array_reverse($pathInfo);
        $link_url_array = [];

        for($i = 0; $i < count($this->routes); $i++){
            if(isset($link_url[$i])) {
                $link_url_array[] = $link_url[$i];
            }
        }

        if ($request->getPathInfo() === $this->action .'/'. implode('/', array_reverse($link_url_array)) && !empty($pathInfo[$this->level])) {
            $params = [];
            $index = 0;
            foreach ($this->routes as $rout) {
                $model = \Yii::createObject($rout['model']);
                if (!$model instanceof IUrlRules) {
                    throw new \Exception('Model ' . $rout['model'] . ' not using interface ' . UrlRuleInterface::class . '.');
                }
                $params[$rout['action_key']] = $model->fiendKey($pathInfo[$this->level + $index]);
                $index++;
            }
            return [$this->controller_and_action, $params];
        }
        return false;
    }

    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|bool the created URL, or false if this rule cannot be used for creating this URL.
     *
     * @throws Exception
     */
    public function createUrl($manager, $route, $params)
    {
        if (empty($this->action) || empty($this->controller_and_action)) {
            throw new \Exception('Class ' . UrlRules::className() . ' parameter exception model, action or controller_and_action.');
        }

        if ($route === $this->action) {
            $seoUrl = '';
            foreach ($this->routes as $rout) {
                /* @var $model ActiveRecord */
                $model = \Yii::createObject($rout['model']);
                if (!$model instanceof IUrlRules) {
                    throw new \Exception('Model ' . $rout['model'] . ' not using interface ' . UrlRuleInterface::class);
                }

                if (!empty($rout['url_key'])) {
                    $seoUrl .= (empty($seoUrl) ? '' : '/') . $model->seoUrl($params[$rout['url_key']]);
                } else {
                    throw new Exception('Invalid param: routes!!! Empty param: url_key!!!');
                }
            }
            if (!empty($seoUrl)) {
                return Url::to($route . '/' . $seoUrl);
            }
        }
        return false;
    }
}
