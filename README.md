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
class ProductModel extends \yii\db\ActiveRecord implements maks757\friendly\components\IUrlRules
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //...
            // seo data
            [['seoUrl', 'seoTitle', 'seoDescription', 'seoKeywords'], 'string']
        ];
    }
    
    // Exemple: $key = 'my-first-product' -> return id
    /**
     * @param mixed $key
     * @return boolean|integer model id
     */
    public function fiendKey($key)
    {
        $model = NewsModel::findOne(['seoTitle' => $key]);
        return empty($model) ? false : $model->id;
    }

    // Exemple: $id = 10 -> return seoUrl
    /**
     * @param integer $id
     * @return string
     */
    public function seoUrl($id)
    {
        return ProductModel::findOne($id)->seoUrl;
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
            'news' => 'product/show',
            [
                    'class' => \maks757\friendly\UrlRules::className(),
                    'action' => 'product/show', // View 'product/show' or news
                    'controller_and_action' => 'product/show', // Action news show
                    //param: (action_key) - Action param get product id
                    //param: (url_key) - // View set product id
                    'routes' => [
                        ['model' => \common\models\ProductGroup::class, 'url_key' => 'group_id', 'action_key' => 'group',],
                        ['model' => \common\models\Product::class, 'url_key' => 'product_id', 'action_key' => 'product',],
                    ]
                ],
        ],
    ],
    //...
],
```

View
-----
```php
<a href="Url::toRoute(['/product/show', 'group_id' => $group, 'product_id' => $product->id])">Go to product</a>
example url: https://tise/product/show/water/colla
water = group seo url
colla = product seo url
```

Action from Product Controller
-----
```php
public function actionShow($group, $product)
{
    //...
}
```
