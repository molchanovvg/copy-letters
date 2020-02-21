<?php

namespace app\models\forms;
use app\models\Client;
use app\models\Flights;
use app\services\CopyService;
use yii\base\Model;

/**
 * Class CopyForm
 */
class CopyForm extends Model
{
    public $flightDate;
    public $clientId;
    public $flightId;

    public $report;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['clientId', 'flightId'], 'required'],
            [['flightDate'], 'string'],
            [['flightDate'], 'date', 'format' => 'php:Y-m-d'],
            [
                ['clientId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Client::class,
                'targetAttribute' => ['clientId' => 'id'],
            ],
            [
                ['flightId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Flights::class,
                'targetAttribute' => ['flightId' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'flightDate' => 'Дата',
            'flightId' => 'Рейс',
            'clientId' => 'Клиент',

        ];
    }

    /**
     * @return bool
     */
    public function runService(): bool
    {
        if ($this->validate()) {

            $client = Client::findOne($this->clientId);

            $flight = Flights::findOne($this->flightId);

            $service = new CopyService($client, $flight, $this->flightDate);

            if ($service->setup() && $service->copyFile()) {
                $this->report = $service->getReport();
                return true;
            }
            $this->addErrors($service->getErrors());
        }
        return false;
    }
}
