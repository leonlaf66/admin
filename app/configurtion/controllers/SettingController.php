<?php
namespace module\configurtion\controllers;

use WS;

class SettingController extends \module\core\component\Controller
{
    public function actionIndex($area_id = null, $id = null)
    {
        $settingTree = [
            [
                'title' => 'Purchase',
                'items' => [
                    'purchase.mortgage-calculator.interest-rate.default' => [
                        'title' => 'Mortgage interest rate'
                    ]
                ]
            ],
            [
                'title' => 'News',
                'items' => [
                    'news.banner.top' => [
                        'title' => 'Website news banner',
                        'link' => true,
                        'display' => function () {
                            return 'Settings...';
                        }
                    ],
                    'app.news.banner.top' => [
                        'title' => 'App news banner',
                        'link' => true,
                        'display' => function () {
                            return 'Settings...';
                        }
                    ]
                ]
            ],
            [
                'title' => 'Google Map Account',
                'items' => [
                    'google.map.key' => [
                        'title' => 'Map Key'
                    ]
                ]
            ],
            [
                'title' => 'Other settings',
                'items' => [
                    'home.luxury.houses' => [
                        'title' => 'Home Luxury houses',
                        'link' => true,
                        'display' => function ($rows) {
                            if (empty($rows)) return 'Not Configured';

                            $outs = [];
                            foreach ($rows as $row) {
                                $outs[] = $row['id'];
                            }
                            return implode(',', $outs);
                        }
                    ]
                ]
            ]
        ];

        if (is_null($id)) { // 普通列表
            $mergedData = \module\configurtion\model\Configure::getAdminData($area_id === 'global' ? null : $area_id, $settingTree);

            $this->view->setActiveMenuId('setting-general-'.$area_id);

            return $this->render('index.phtml', [
                'areaId' => $area_id,
                'configData'=>$mergedData
            ]);
        } else { // 特定的
            $localConfiguation = WS::$app->configuationData;
            if (!isset($localConfiguation[$id])) exit;

            $options = $localConfiguation[$id];

            $entity = \models\SiteSetting::find()
                ->where([
                    'path' => $id,
                    'site_id' => $area_id === 'global' ? null : $area_id
                ])
                ->one();

            if (! $entity) {
                $entity = new \models\SiteSetting();
                $entity->path = $id;
                $entity->value = isset($options['default']) ? json_encode($options['default']) : json_encode(null);
                $entity->site_id = $area_id === 'global' ? null : $area_id;
            }
            $entity->value = json_decode($entity->value, true);

            $render = '\module\configurtion\widgets\Render'.ucfirst($options['type']);
            $this->view->setActiveMenuId('setting-general-'.$area_id);

            return $this->render('index_one.phtml', [
                'areaId' => $area_id,
                'render' => $render,
                'entity'=>$entity
            ]);
        }
    }

    public function actionSave($area_id = null, $path = null)
    {
        if ($area_id === 'global') $area_id = null;

        if ($path) {
            $localConfiguation = WS::$app->configuationData;
            if (!isset($localConfiguation[$path])) {
                exit;
            }
            $options = $localConfiguation[$path];

            $data = WS::$app->request->post('data');
            $row = \module\configurtion\model\Configure::find()->where(['path' => $path, 'site_id'=>$area_id])->one();
            if (! $row) {
                $row = new \module\configurtion\model\Configure();
                $row->path = $path;
                $row->site_id = $area_id;
            }

            $processClassName = "\\module\\configurtion\\callbacks\\".$options['type'];
            if (class_exists($processClassName)) {
                $processClassName::process($row->path, $data);
            }

            $row->value = json_encode($data);
            return $row->save();
        }

        if(WS::$app->request->isPost) {
            $configData = WS::$app->request->post('config');
            \module\configurtion\model\Configure::saveData($area_id, $configData);
        }

        return $this->redirect(['/configurtion/setting/index', 'area_id' => ($area_id ? $area_id : 'global')]);
    }
}