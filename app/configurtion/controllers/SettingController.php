<?php
namespace module\configurtion\controllers;

use WS;

class SettingController extends \module\core\component\Controller
{
    public function actionIndex($area_id = null, $id = null)
    {
        $settingTree = [
            [
                'title' => 'Rent',
                'items' => [
                    'lease.home.deluxe.hot.ids' => [
                        'title' => 'Home Deluxe hot ids'
                    ],
                    'lease.home.deluxe.hot.more' => [
                        'title' => 'Home Deluxe hot more link'
                    ],
                    'lease.home.newest.hot.ids' => [
                        'title' => 'Home Newest hot ids'
                    ],
                    'lease.home.newest.hot.more' => [
                        'title' => 'Home Newest hot more link'
                    ]
                ]
            ],
            [
                'title' => 'Purchase',
                'items' => [
                    'purchase.home.deluxe.hot.ids' => [
                        'title' => 'Home deluxe hot ids'
                    ],
                    'purchase.home.deluxe.hot.more' => [
                        'title' => 'Home Deluxe hot more link'
                    ],
                    'purchase.home.newest.hot.ids' => [
                        'title' => 'Home Newest hot ids'
                    ],
                    'purchase.home.newest.hot.more' => [
                        'title' => 'Home Newest hot more link'
                    ],
                    'purchase.mortgage-calculator.interest-rate.default' => [
                        'title' => 'Mortgage interest rate'
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
                        'link' => true
                    ]
                ]
            ]
        ];

        if (is_null($id)) {
            $mergedData = \module\configurtion\model\Configure::getAdminData($area_id === 'global' ? null : $area_id, $settingTree);

            $this->view->setActiveMenuId('setting-general-'.$area_id);

            return $this->render('index.phtml', [
                'areaId' => $area_id,
                'configData'=>$mergedData
            ]);
        } else {
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
            $entity->value = json_decode($entity->value);

            $render = '\module\configurtion\widgets\Render'.ucfirst($options['type']);

            $this->view->setActiveMenuId('setting-'.str_replace('.', '-', $entity->path));

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
            $processClassName::process($row->path, $data);

            $row->value = json_encode($data);
            return $row->save();
        }

        if(WS::$app->request->isPost) {
            $configData = WS::$app->request->post('config');
            \module\configurtion\model\Configure::saveData($area_id, $configData);
        }

        return $this->redirect(['/configurtion/setting/index', 'area_id' => $area_id]);
    }
}