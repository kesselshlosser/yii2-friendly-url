<?php

namespace maks757\friendly;

use yii\base\Object;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

/**
 * @property Object $model
*/
class UrlRules extends Object implements UrlRuleInterface
{
    public $action = null;
    public $level = 1;
    public $model = null;

    public function __construct(array $config = [])
    {
        if($this->model instanceof IUrlRules)
            throw new \Exception('Model '.$this->model->className() .' not using interface'. UrlRuleInterface::class );
        parent::__construct($config);
    }


    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = explode('/', $request->getPathInfo());
        if (!empty($this->model) && strpos($request->getPathInfo(), $this->action) && !empty($pathInfo[$this->level])) {
//            $article = Yii2DataArticle::find()->joinWith('seo s')
//                ->where(['s.seo_url' => $pathInfo[$this->level]])->one();
//            return ['/site/news-post', ['id' => $article->id]];
        }
        return false;
    }

    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|bool the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)
    {
        if (!empty($this->model) && $route === $this->action && !empty($params['article_id'])) {
//            $article = Yii2DataArticle::findOne($params['article_id']);
//            if(!empty($article->seoUrl)){
//                return $route . '/' . $article->seoUrl;
//            }
        }
        return false;
    }
}
