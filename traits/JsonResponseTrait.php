<?php

namespace app\traits;

use Yii;
use yii\web\Response;

/**
 * Class JsonResponseTrait
 * @author jiang
 * @package app\traits
 */
trait JsonResponseTrait
{
    /**
     * @param array $content
     * @return mixed
     */
    public function responseJson(array $content)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $content;
    }

    /**
     * @param array $content
     * @return mixed
     */
    public function responseSuccessJson(array $content)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'status' => true,
            'data' => $content,
            'info' => 'success',
        ];
    }

    /**
     * @param string $content
     * @return mixed
     */
    public function responseErrorJson($content)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'status' => false,
            'data' => [],
            'info' => $content,
        ];
    }

}