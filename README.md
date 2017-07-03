Friendly url
============
Friendly url for your library or module

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist maks757/yii2-friendly-url "*"
```

or add

```
"maks757/yii2-friendly-url": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Model
-----
```php
// implements IUrlRules !!!
class NewsModel extends \yii\db\ActiveRecord implements maks757\friendly\components\IUrlRules
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'big_image', 'name'], 'required'],
            [['date'], 'integer'],
            [['image'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 255],
            // seo data
            [['seoUrl', 'seoTitle', 'seoDescription', 'seoKeywords'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seoUrl' => 'СЕО Url',
            'seoTitle' => 'СЕО Title',
            'seoDescription' => 'СЕО Description',
            'seoKeywords' => 'СЕО Keys',
        ];
    }
    
    /**
     * @param mixed $key
     * @return boolean|integer model id
     */
    public function fiendKey($key)
    {
        $object = NewsModel::findOne(key);
        return empty($object) ? false : $object->id;
    }

    /**
     * @param integer $id
     * @return string
     */
    public function seoUrl($id)
    {
        return NewsModel::findOne($id)->seoUrl;
    }
}
```

Configuration
-----
```php
'components' => [
    //...
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
            'news' => 'news/show',
            [
                'class' => \maks757\friendly\UrlRules::className(),
                'model' => \common\modules\news\entities\NewsModel::class,
                'action' => 'news/show', // View 'news/show' or news
                'url_key' => 'id', // View set news id
                'action_key' => 'news_id', // Action get news id
                'controller_and_action' => '/news/show' // Action news show
            ]
        ],
    ],
    //...
],
```

View
-----
```php
<a href="Url::toRoute(['/news/show', 'id' => $news->id])>Go to news</a>
```

Action
-----
```php
public function actionShow($news_id)
{
    //...
}
```
