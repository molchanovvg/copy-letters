<?php

namespace app\models\forms;
use yii\base\Model;

/**
 * Class CopyForm
 */
class CopyForm extends Model
{
    public $flightDate;
    public $clientList;
    public $flyght;

    public $report;

    /**
     * @return array
     */
    public function rules()
    {
        return []; // TODO: Change the autogenerated stub
    }

    /**
     * @return bool
     */
    public function copyFiles(): bool
    {
        if ($this->validate()) {
            return true;
        }
        return false;
    }
}