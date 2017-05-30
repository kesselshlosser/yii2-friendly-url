<?php

namespace maks757\friendly;
use Exception;
use maks757\friendly\components\IUrlRules;
use yii\base\Object;

use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;


/**
 * @property Object $model
*/
class UrlRules extends Object implements UrlRuleInterface
{
    public $url_key;
    public $action_key;
    public $controller_and_action;
    public $action;
    public $level = 1;
    public $model;

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
        if(empty($this->model) || empty($this->action) || empty($this->url_key) || empty($this->action_key) || empty($this->controller_and_action))
            throw new \Exception('Class ' . UrlRules::className() .' parameter exception model, action, url_key, action_key or controller_and_action.');

        $model = \Yii::createObject($this->model);
        if(!$model instanceof IUrlRules)
            throw new \Exception('Model '.$this->model .' not using interface '. UrlRuleInterface::class .'.');

        $pathInfo = explode('/', $request->getPathInfo());
        if (strpos($request->getPathInfo(), $this->action) !== false && !empty($pathInfo[$this->level])) {
            return [$this->controller_and_action, [$this->action_key => $model->fiendKey($pathInfo[$this->level])]];
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
        $model = \Yii::createObject($this->model);
        if(!$model instanceof IUrlRules)
            throw new \Exception('Model '.$this->model .' not using interface '. UrlRuleInterface::class );

        if ($route === $this->action && !empty($params[$this->url_key])) {
            $seoUrl = $model->getSeoUrl($params[$this->url_key]);
            if(!empty($seoUrl)){
                return $route . '/' . $seoUrl;
            }
        }
        return false;
    }
}
