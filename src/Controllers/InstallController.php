<?php

namespace App\Controllers;

use Faker\Factory;
use Faker\Generator;
use Yii;
use yii\easyii\models\InstallForm;
use yii\easyii\models\Photo;
use yii\easyii\models\SeoText;
use yii\easyii\models\Setting;
use yii\easyii\modules\carousel\models\Carousel;
use yii\easyii\modules\catalog;
use yii\easyii\modules\article;
use yii\easyii\modules\faq\models\Faq;
use yii\easyii\modules\file\models\File;
use yii\easyii\modules\gallery;
use yii\easyii\modules\guestbook\models\Guestbook;
use yii\easyii\modules\news\models\News;
use yii\easyii\modules\page\models\Page;
use yii\easyii\modules\text\models\Text;

class InstallController extends \yii\web\Controller
{
    public $layout = 'install';
    public $defaultAction = 'step1';
    public $db;
    /** @var Generator */
    public $faker;

    public $dbConnected = false;
    public $adminInstalled = false;
    public $shopInstalled = false;

    public function init()
    {
        parent::init();

        $this->db = Yii::$app->db;
        $locale = Yii::$app->formatter->locale;
        $this->faker = Factory::create(str_replace('-', '_', $locale));

        try {
            Yii::$app->db->open();
            $this->dbConnected = true;
            $this->adminInstalled = Yii::$app->getModule('admin')->installed;
            if($this->adminInstalled) {
                $this->shopInstalled = Page::find()->count() > 0 ? true : false;
            }
        }
        catch(\Exception $e){
            $this->dbConnected = false;
        }
    }

    public function actionStep1()
    {
        if($this->adminInstalled){
            return $this->redirect($this->shopInstalled ? ['/'] : ['/install/step3']);
        }
        return $this->render('step1');
    }

    public function actionStep2()
    {
        if($this->adminInstalled){
            return $this->redirect($this->shopInstalled ? ['/'] : ['/install/step3']);
        }
        $this->registerI18n();

        $installForm = new InstallForm();
        $installForm->robot_email = 'noreply@'.Yii::$app->request->serverName;

        Yii::$app->session->setFlash(InstallForm::RETURN_URL_KEY, '/install/step3');

        return $this->render('step2', ['model' => $installForm]);
    }

    public function actionStep3()
    {
        $result = [];
        $result[] = $this->insertTexts();
        $result[] = $this->insertSettings();
        $result[] = $this->insertPages();
        $result[] = $this->insertCatalog();
        $result[] = $this->insertNews();
        $result[] = $this->insertArticles();
        $result[] = $this->insertGallery();
        $result[] = $this->insertGuestbook();
        $result[] = $this->insertFaq();
        $result[] = $this->insertCarousel();
        $result[] = $this->insertFiles();

        return $this->render('step3', ['result' => $result]);
    }

    private function registerI18n()
    {
        Yii::$app->i18n->translations['easyii/install'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@easyii/messages',
            'fileMap' => [
                'easyii/install' => 'install.php',
            ]
        ];
    }

    public function insertTexts()
    {
        if(Text::find()->count()) {
            return '`<b>' . Text::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `'.Text::tableName().'`')->query();

        (new Text([
            'text' => 'Welcome on demo website',
            'slug' => 'index-welcome-title'
        ]))->save();

        return 'Text data inserted.';
    }

    public function insertSettings()
    {
        if(Setting::find()->where(['name' => 'admin_phone'])->count()) {
            return '`<b>' . Setting::tableName() . '</b>` table is not empty, skipping...';
        }

        (new Setting([
            'name' => 'admin_phone',
            'title' => Yii::t('app/install', 'Admin phone'),
            'value' => $this->faker->phoneNumber,
            'visibility' => Setting::VISIBLE_ALL
        ]))->save();

        (new Setting([
            'name' => 'counters',
            'title' => Yii::t('app/install', 'Counters'),
            'value' => '',
            'visibility' => Setting::VISIBLE_ROOT
        ]))->save();

        (new Setting([
            'name' => 'twitter_url',
            'title' => 'Twitter',
            'value' => 'https://twitter.com/',
            'visibility' => Setting::VISIBLE_ALL
        ]))->save();

        (new Setting([
            'name' => 'youtube_url',
            'title' => 'YouTube',
            'value' => 'https://www.youtube.com/',
            'visibility' => Setting::VISIBLE_ALL
        ]))->save();

        (new Setting([
            'name' => 'facebook_url',
            'title' => 'Facebook',
            'value' => 'https://www.facebook.com/',
            'visibility' => Setting::VISIBLE_ALL
        ]))->save();


        (new Setting([
            'name' => 'vk_url',
            'title' => 'VK',
            'value' => 'https://vk.com/',
            'visibility' => Setting::VISIBLE_ALL
        ]))->save();

        (new Setting([
            'name' => 'instagram_url',
            'title' => 'Instagram',
            'value' => 'https://www.instagram.com/',
            'visibility' => Setting::VISIBLE_ALL
        ]))->save();

        return 'Setting data inserted.';
    }

    public function insertPages()
    {
        if(Page::find()->count()) {
            return '`<b>' . Page::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `'.Page::tableName().'`')->query();

        $page1 = new Page([
            'title' => 'Index',
            'text' => '<h1>Welcome on demo website</h1><p><strong>All elements are live-editable, switch on Live Edit button to see this feature.</strong>&nbsp;</p><p>' . $this->faker->realText(). '</p>',
            'slug' => 'page-index'
        ]);
        $page1->save();
        $this->attachSeo($page1, '', 'Demo website', 'yii2, easyii, admin');

        $page2 = new Page([
            'title' => 'Shop',
            'text' => '',
            'slug' => 'page-shop'
        ]);
        $page2->save();
        $this->attachSeo($page2, 'Shop categories', 'Extended shop title');

        $page3 = new Page([
            'title' => 'Shop search',
            'text' => '',
            'slug' => 'page-shop-search'
        ]);
        $page3->save();
        $this->attachSeo($page3, 'Shop search results', 'Extended shop search title');

        $page4 = new Page([
            'title' => 'Shopping cart',
            'text' => '',
            'slug' => 'page-shopcart'
        ]);
        $page4->save();
        $this->attachSeo($page4, 'Shopping cart H1', 'Extended shopping cart title');

        $page5 = new Page([
            'title' => 'Order created',
            'text' => '<p>Your order successfully created. Our manager will contact you as soon as possible.</p>',
            'slug' => 'page-shopcart-success'
        ]);
        $page5->save();
        $this->attachSeo($page5, 'Success', 'Extended order success title');

        $page6 = new Page([
            'title' => 'News',
            'text' => '',
            'slug' => 'page-news'
        ]);
        $page6->save();
        $this->attachSeo($page6, 'News H1', 'Extended news title');

        $page7 = new Page([
            'title' => 'Articles',
            'text' => '',
            'slug' => 'page-articles'
        ]);
        $page7->save();
        $this->attachSeo($page7, 'Articles H1', 'Extended articles title');

        $page8 = new Page([
            'title' => 'Gallery',
            'text' => '',
            'slug' => 'page-gallery'
        ]);
        $page8->save();
        $this->attachSeo($page8, 'Photo gallery', 'Extended gallery title');

        $page9 = new Page([
            'title' => 'Guestbook',
            'text' => '',
            'slug' => 'page-guestbook'
        ]);
        $page9->save();
        $this->attachSeo($page9, 'Guestbook H1', 'Extended guestbook title');

        $page10 = new Page([
            'title' => 'FAQ',
            'text' => '',
            'slug' => 'page-faq'
        ]);
        $page10->save();
        $this->attachSeo($page10, 'Frequently Asked Question', 'Extended faq title');

        $page11 = new Page([
            'title' => 'Contact',
            'text' => '<p><strong>Address</strong>: ' . $this->faker->address . '</p><p><strong>Phone</strong>: ' . $this->faker->phoneNumber . '</p><p><strong>E-mail</strong>: ' . $this->faker->companyEmail . '</p>',
            'slug' => 'page-contact'
        ]);
        $page11->save();
        $this->attachSeo($page11, 'Contact us', 'Extended contact title');

        return 'Page data inserted.';
    }

    public function insertCatalog()
    {
        if(catalog\models\Category::find()->count()) {
            return '`<b>' . catalog\models\Category::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `'.catalog\models\Category::tableName().'`')->query();

        $fields = [
            [
                'name' => 'brand',
                'title' => 'Brand',
                'type' => 'select',
                'options' => ['Samsung', 'Apple', 'Nokia']
            ],
            [
                'name' => 'storage',
                'title' => 'Storage',
                'type' => 'string',
                'options' => ''
            ],
            [
                'name' => 'touchscreen',
                'title' => 'Touchscreen',
                'type' => 'boolean',
                'options' => ''
            ],
            [
                'name' => 'cpu',
                'title' => 'CPU cores',
                'type' => 'select',
                'options' => ['1', '2', '4', '8']
            ],
            [
                'name' => 'features',
                'title' => 'Features',
                'type' => 'checkbox',
                'options' => ['Wi-fi', '4G', 'GPS']
            ],
            [
                'name' => 'color',
                'title' => 'Color',
                'type' => 'checkbox',
                'options' => ['White', 'Black', 'Red', 'Blue']
            ],
        ];

        $root = new catalog\models\Category([
            'title' => 'Gadgets',
            'fields' => $fields,
        ]);
        $root->makeRoot();

        $cat1 = new catalog\models\Category([
            'title' => 'Smartphones',
            'fields' => $fields,
        ]);
        $cat1->appendTo($root);
        $this->attachSeo($cat1, 'Smartphones H1', 'Extended smartphones title');

        $cat2 = new catalog\models\Category([
            'title' => 'Tablets',
            'fields' => $fields,
        ]);
        $cat2->appendTo($root);
        $this->attachSeo($cat2, 'Tablets H1', 'Extended tablets title');

        if(catalog\models\Item::find()->count()) {
            return '`<b>' . catalog\models\Item::tableName() . '</b>` table is not empty, skipping...';
        }
        $time = time();

        $item1 = new catalog\models\Item([
            'category_id' => $cat1->primaryKey,
            'title' => 'Nokia 3310',
            'description' => '<h3>The legend</h3><p>The Nokia 3310 is a GSMmobile phone announced on September 1, 2000, and released in the fourth quarter of the year, replacing the popular Nokia 3210. The phone sold extremely well, being one of the most successful phones with 126 million units sold worldwide.&nbsp;The phone has since received a cult status and is still widely acclaimed today.</p><p>The 3310 was developed at the Copenhagen Nokia site in Denmark. It is a compact and sturdy phone featuring an 84 × 48 pixel pure monochrome display. It has a lighter 115 g battery variant which has fewer features; for example the 133 g battery version has the start-up image of two hands touching while the 115 g version does not. It is a slightly rounded rectangular unit that is typically held in the palm of a hand, with the buttons operated with the thumb. The blue button is the main button for selecting options, with "C" button as a "back" or "undo" button. Up and down buttons are used for navigation purposes. The on/off/profile button is a stiff black button located on the top of the phone.</p>',
            'available' => 5,
            'discount' => 0,
            'price' => 100,
            'data' => [
                'brand' => 'Nokia',
                'storage' => '1',
                'touchscreen' => '0',
                'cpu' => 1,
                'color' => ['White', 'Red', 'Blue']
            ],
            'image' => '/uploads/catalog/3310.jpg',
            'time' => $time
        ]);
        $item1->save();
        $this->attachPhotos($item1, ['/uploads/photos/3310-1.jpg', '/uploads/photos/3310-2.jpg']);
        $this->attachSeo($item1, 'Nokia 3310');

        $item2 = new catalog\models\Item([
            'category_id' => $cat1->primaryKey,
            'title' => 'Galaxy S6',
            'description' => '<h3>Next is beautifully crafted</h3><p>With their slim, seamless, full metal and glass construction, the sleek, ultra thin edged Galaxy S6 and unique, dual curved Galaxy S6 edge are crafted from the finest materials.</p><p>And while they may be the thinnest smartphones we`ve ever created, when it comes to cutting-edge technology and flagship Galaxy experience, these 5.1" QHD Super AMOLED smartphones are certainly no lightweights.</p>',
            'available' => 1,
            'discount' => 10,
            'price' => 1000,
            'data' => [
                'brand' => 'Samsung',
                'storage' => '32',
                'touchscreen' => '1',
                'cpu' => 8,
                'features' => ['Wi-fi', 'GPS']
            ],
            'image' => '/uploads/catalog/galaxy.jpg',
            'time' => $time - 86400
        ]);
        $item2->save();
        $this->attachPhotos($item2, ['/uploads/photos/galaxy-1.jpg', '/uploads/photos/galaxy-2.jpg', '/uploads/photos/galaxy-3.jpg', '/uploads/photos/galaxy-4.jpg']);
        $this->attachSeo($item2, 'Samsung Galaxy S6');

        $item3 = new catalog\models\Item([
            'category_id' => $cat1->primaryKey,
            'title' => 'Iphone 6',
            'description' => '<h3>Next is beautifully crafted</h3><p>With their slim, seamless, full metal and glass construction, the sleek, ultra thin edged Galaxy S6 and unique, dual curved Galaxy S6 edge are crafted from the finest materials.</p><p>And while they may be the thinnest smartphones we`ve ever created, when it comes to cutting-edge technology and flagship Galaxy experience, these 5.1" QHD Super AMOLED smartphones are certainly no lightweights.</p>',
            'available' => 0,
            'discount' => 10,
            'price' => 1100,
            'data' => [
                'brand' => 'Apple',
                'storage' => '64',
                'touchscreen' => '1',
                'cpu' => 4,
                'features' => ['Wi-fi', '4G', 'GPS']
            ],
            'image' => '/uploads/catalog/iphone.jpg',
            'time' => $time - 86400 * 2
        ]);
        $item3->save();
        $this->attachPhotos($item3, ['/uploads/photos/iphone-1.jpg', '/uploads/photos/iphone-2.jpg', '/uploads/photos/iphone-3.jpg', '/uploads/photos/iphone-4.jpg']);
        $this->attachSeo($item3, 'Apple Iphone 6');

        return 'Catalog data inserted.';
    }

    public function insertNews()
    {
        if (News::find()->count()) {
            return '`<b>' . News::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `' . News::tableName() . '`')->query();

        $time = time();

        $news1 = new News([
            'title' => $this->faker->realText(50),
            'image' => '/uploads/news/news-1.jpg',
            'short' => $this->faker->realText(),
            'text' => '<p>' . $this->faker->realText() . '</p><ul><li>' . $this->faker->word . '</li><li>' . $this->faker->word . '</li><li>' . $this->faker->word . '</li></ul><p>' . $this->faker->realText() . '</p>',
            'tagNames' => $this->faker->realText(10),
            'time' => $time
        ]);
        $news1->save();
        $this->attachPhotos($news1, ['/uploads/photos/news-1-1.jpg', '/uploads/photos/news-1-2.jpg', '/uploads/photos/news-1-3.jpg', '/uploads/photos/news-1-4.jpg']);
        $this->attachSeo($news1, 'First news H1');

        $news2 = new News([
            'title' => $this->faker->realText(50),
            'image' => '/uploads/news/news-2.jpg',
            'short' => $this->faker->realText(),
            'text' => '<p>' . $this->faker->realText() . '</p><ol> <li>' . $this->faker->realText(50) . '</li><li>' . $this->faker->realText(50) . '</li></ol>',
            'tagNames' => $this->faker->realText(10),
            'time' => $time - 86400
        ]);
        $news2->save();
        $this->attachSeo($news2, 'Second news H1');

        $news3 = new News([
            'title' => $this->faker->realText(50),
            'image' => '/uploads/news/news-3.jpg',
            'short' => $this->faker->realText(),
            'text' => '<p>' . $this->faker->realText() . '</p>',
            'time' => $time - 86400 * 2
        ]);
        $news3->save();
        $this->attachSeo($news3, 'Third news H1');

        return 'News data inserted.';
    }

    public function insertArticles()
    {
        if(article\models\Category::find()->count()) {
            return '`<b>' . article\models\Category::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `'.article\models\Category::tableName().'`')->query();

        $root1 = new article\models\Category([
            'title' => 'Articles category 1',
            'order_num' => 2
        ]);
        $root1->makeRoot();
        $this->attachSeo($root1, 'Articles category 1 H1', 'Extended category 1 title');

        $root2 = new article\models\Category([
            'title' => 'Articles category 2',
            'order_num' => 1
        ]);
        $root2->makeRoot();

        $subcat1 = new article\models\Category([
            'title' => 'Subcategory 1',
            'order_num' => 1
        ]);
        $subcat1->appendTo($root2);
        $this->attachSeo($subcat1, 'Subcategory 1 H1', 'Extended subcategory 1 title');

        $subcat2 = new article\models\Category([
            'title' => 'Subcategory 1',
            'order_num' => 1
        ]);
        $subcat2->appendTo($root2);
        $this->attachSeo($subcat2, 'Subcategory 2 H1', 'Extended subcategory 2 title');

        if (article\models\Item::find()->count()) {
            return '`<b>' . article\models\Item::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `' . article\models\Item::tableName() . '`')->query();

        $time = time();

        $article1 = new article\models\Item([
            'category_id' => $root1->primaryKey,
            'title' => $this->faker->realText(50),
            'image' => '/uploads/article/article-1.jpg',
            'short' => $this->faker->realText(),
            'text' => '<p><strong>' . $this->faker->realText(10) . '.</strong>' . $this->faker->realText() . '</p><ul><li>' . $this->faker->word . '</li><li>' . $this->faker->word . '</li><li>' . $this->faker->word . '</li></ul><p>' . $this->faker->realText() . '</p>',
            'tagNames' => $this->faker->realText(10),
            'time' => $time
        ]);
        $article1->save();
        $this->attachPhotos($article1, ['/uploads/photos/article-1-1.jpg', '/uploads/photos/article-1-2.jpg', '/uploads/photos/article-1-3.jpg', '/uploads/photos/news-1-4.jpg']);
        $this->attachSeo($article1, 'First article H1');

        $article2 = new article\models\Item([
            'category_id' => $root1->primaryKey,
            'title' => $this->faker->realText(50),
            'image' => '/uploads/article/article-2.jpg',
            'short' => $this->faker->realText(),
            'text' => '<p>' . $this->faker->realText() . '</p><ol> <li>' . $this->faker->realText(50) . '</li><li>' . $this->faker->realText(50) . '</li></ol>',
            'tagNames' => $this->faker->realText(10),
            'time' => $time - 86400
        ]);
        $article2->save();
        $this->attachSeo($article2, 'Second article H1');

        $article3 = new article\models\Item([
            'category_id' => $root1->primaryKey,
            'title' => $this->faker->realText(50),
            'image' => '/uploads/article/article-3.jpg',
            'short' => $this->faker->realText(),
            'text' => '<p>' . $this->faker->realText() . '</p>',
            'time' => $time - 86400 * 2
        ]);
        $article3->save();
        $this->attachSeo($article3, 'Third article H1');

        return 'Articles data inserted.';
    }

    public function insertGallery()
    {
        if (gallery\models\Category::find()->count()) {
            return '`<b>' . gallery\models\Category::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `' . gallery\models\Category::tableName() . '`')->query();

        $album1 = new gallery\models\Category([
            'title' => 'Album 1',
            'image' => '/uploads/gallery/album-1.jpg',
            'order_num' => 2
        ]);
        $album1->makeRoot();
        $this->attachSeo($album1, 'Album 1 H1', 'Extended Album 1 title');
        $this->attachPhotos($album1, [
            '/uploads/photos/album-1-9.jpg',
            '/uploads/photos/album-1-8.jpg',
            '/uploads/photos/album-1-7.jpg',
            '/uploads/photos/album-1-6.jpg',
            '/uploads/photos/album-1-5.jpg',
            '/uploads/photos/album-1-4.jpg',
            '/uploads/photos/album-1-3.jpg',
            '/uploads/photos/album-1-2.jpg',
            '/uploads/photos/album-1-1.jpg'
        ]);

        $album2 = new gallery\models\Category([
            'title' => 'Album 2',
            'image' => '/uploads/gallery/album-2.jpg',
            'order_num' => 1
        ]);
        $album2->makeRoot();
        $this->attachSeo($album2, 'Album 2 H1', 'Extended Album 2 title');

        return 'Gallery data inserted.';
    }

    public function insertGuestbook()
    {
        if(Guestbook::find()->count()) {
            return '`<b>' . Guestbook::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `'.Guestbook::tableName().'`')->query();

        $time = time();

        $post1 = new Guestbook([
            'name' => $this->faker->name(),
            'text' => $this->faker->realText()
        ]);
        $post1->time = $time;
        $post1->save();

        $post2 = new Guestbook([
            'name' => $this->faker->name(),
            'text' => $this->faker->realText()
        ]);
        $post2->time = $time - 86400;
        $post2->answer = $this->faker->realText();
        $post2->save();
        $post2->new = 0;
        $post2->update();

        $post3 = new Guestbook([
            'name' => $this->faker->name(),
            'text' => $this->faker->realText()
        ]);
        $post3->time = $time - 86400*2;
        $post3->save();

        return 'Guestbook data inserted.';
    }

    public function insertFaq()
    {
        if(Faq::find()->count()) {
            return '`<b>' . Faq::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `'.Faq::tableName().'`')->query();

        (new Faq([
            'question' => $this->faker->realText(50) . '?',
            'answer' => $this->faker->realText()
        ]))->save();

        (new Faq([
            'question' => $this->faker->realText(50) . '?',
            'answer' => $this->faker->realText()
        ]))->save();

        (new Faq([
            'question' => $this->faker->realText(50) . '?',
            'answer' => $this->faker->realText()
        ]))->save();

        return 'Faq data inserted.';
    }

    public function insertCarousel()
    {
        if(Carousel::find()->count()) {
            return '`<b>' . Carousel::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `'.Carousel::tableName().'`')->query();

        (new Carousel([
            'image' => '/uploads/carousel/1.jpg',
            'title' => $this->faker->realText(50),
            'text' => $this->faker->realText(),
        ]))->save();

        (new Carousel([
            'image' => '/uploads/carousel/2.jpg',
            'title' => $this->faker->realText(50),
            'text' => $this->faker->realText(),
        ]))->save();

        (new Carousel([
            'image' => '/uploads/carousel/3.jpg',
            'title' => $this->faker->realText(50),
            'text' => $this->faker->realText(),
        ]))->save();


        return 'Carousel data inserted.';
    }

    public function insertFiles()
    {
        if(File::find()->count()) {
            return '`<b>' . File::tableName() . '</b>` table is not empty, skipping...';
        }
        $this->db->createCommand('TRUNCATE TABLE `'.File::tableName().'`')->query();

        (new File([
            'title' => 'Price list',
            'file' => '/uploads/files/example.csv',
            'size' => 104
        ]))->save();

        return 'File data inserted.';
    }

    private function attachPhotos($model, $files){
        $class = get_class($model);
        foreach($files as $file) {
            (new Photo([
                'class' => $class,
                'item_id' => $model->primaryKey,
                'image' => $file
            ]))->save();
        }
    }

    private function attachSeo($model, $h1 = '', $title = '', $description = '', $keywords = ''){
        $class = get_class($model);
        (new SeoText([
            'class' => $class,
            'item_id' => $model->primaryKey,
            'h1' => $h1,
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords
        ]))->save();
    }
}
