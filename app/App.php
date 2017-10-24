<?php
class App extends \yii\web\Application
{
    public $domain = '';
    public $baseUrl = '/';
    public $stateCode = 'MA';
    public $translationStatus = false;
    public $configuationData = [];
    public $shareItems = [];
    public $areaMaps = [];
    
    public function bootstrap()
    {
        if(isset(\Yii::$app->session['language'])) {
            $this->language = \Yii::$app->session['language'];
        }
        elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->language = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh-CN') !== false ? 'zh-CN' : 'en-US';
        }
        else {
            $this->language = 'en-US';
        }

        $this->language = 'en-US';
        
        if(isset($_GET['translation-manager']) && $_GET['translation-manager']=='!2345@AbC') {
            $this->session->set('translation-manager', true);
        }
        if(\Yii::$app->session->get('translation-manager')) {
            $this->translationStatus = true;
        }

        foreach($this->getModules() as $moduleId=>$moduleClass) 
        {
            $configFile = APP_ROOT.'/app/'.$moduleId.'/etc/config.php';
            if(file_exists($configFile)) {
                $config = include($configFile);
                new $moduleClass($moduleId, null, $config);
            }
        }
        return parent::bootstrap();
    }

    public function getSystemConfig($key, $defValue = null)
    {
        return \common\core\Configure::get($key, $defValue);
    }

    public function share($name, $data = null)
    {
        if (is_null($data)) {
            return isset($this->shareItems[$name]) ? $this->shareItems[$name] : null;
        }
        $this->shareItems[$name] = $data;
    }
}

class WS extends Yii
{
    public static function t($category, $message, $params = [], $language = null)
    {
        if(self::$app->id === 'wesnail-admin') return null;
        $isUiLang = true;

        if(substr($category, 0, 1) === '@') {
            $category = substr($category, 1);
            $isUiLang = false;
            \module\cms\helpers\Language::submit($category, $message, $message, 'zh-CN');
        }

        $result = parent::t($category, $message, $params, $language);

        if(! \Yii::$app->translationStatus) {
            return $result;
        }

        $message = htmlspecialchars($message);
        return $isUiLang ? "<t data-type=\"{$category}\" data-source=\"{$message}\">{$result}</t>" : $result;
    }

    public static function lang($type, $r=false)
    {
        $t = function($text, $params=[], $return=false) use($type, $r) {
            $result = WS::t($type, $text, $params);
            if($r || $return) {
                return $result;
            }
            echo $result; 
        };
        return $t;
    }

    public static function text($texts)
    {
        $texts = array_merge(['en-US'=>'', 'zu-CN'=>''], $texts);
        return $texts[\Yii::$app->language];
    }

    public static function langText($enText, $cnText)
    {
        return \WS::$app->language === 'zh-CN' && ! empty($cnText) ? $cnText : $enText;
    }

    public static function isChinese()
    {
        return \WS::$app->language === 'zh-CN';
    }

    public static function getStaticData($name)
    {
        return include(\WS::$app->basePath . "/data/{$name}.php");
    }

    public static function share($key, $value = null)
    {
        static $data = [];

        if ($value) {
            $data[$key] = $value;
        } else {
            return $data[$key] ?? null;
        }
    }
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require(APP_ROOT . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container();