<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 20/01/15
 * Time: 1:19
 * To change this template use File | Settings | File Templates.
 */

namespace app\modules\mod_contable\models;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }
}