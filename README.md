#百度翻译 for Laravel

以下是使用方法

首先定位到您的项目路径运行
```
composer require iscms/translate
```
然后需要添加
```
iscms\translate\BaiduTranslateServiceProvider::class,
```
到app.providers

之后运行
```
php artisan vendor:publish
```
将配置项目发不到您的config目录下
您可以找到
```
baidu-translate.php
```

您现在可以直接添加
```
BAIDU_TRANSLATE_ID=您的ID
BAIDU_TRANSLATE_KEY=您的Key
```
到.env配置文件中

您现在可以在项目中使用
```php
    $store = new Translate;
    $translate = $store->translate($title,'zh');
```
返回值为json格式
```json
{'result':'翻译结果','status':'success'} or {'result':'错误原因','status':'error'}
```
您可以根据实际情况进行处理