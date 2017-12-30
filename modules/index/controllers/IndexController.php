<?php

namespace app\modules\index\controllers;

use app\traits\JsonResponseTrait;
use yii\base\Module;
use yii\web\Controller;

/**
 * Class IndexController
 * @author jiang
 * @package app\modules\login\controllers
 */
class IndexController extends Controller
{
    use JsonResponseTrait;

    /**
     * IndexController constructor.
     * @param $id
     * @param Module $module
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    /**
     *
     */
    public function actionIndex()
    {
        return 'welcome';
    }

}