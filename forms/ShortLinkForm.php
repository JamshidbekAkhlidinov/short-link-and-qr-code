<?php
/*
 *   Jamshidbek Akhlidinov
 *   30 - 7 2025 17:13:38
 *   https://ustadev.uz
 *   https://github.com/JamshidbekAkhlidinov
 */

namespace app\forms;

use app\models\Data;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Yii;
use yii\base\Model;
use yii\db\Exception;

class ShortLinkForm extends Model
{
    public Data $model;

    public $name;
    public $url;

    public function __construct(Data $model, $config = [])
    {
        $this->model = $model;
        $this->name = $model->name;
        $this->url = $model->url;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            ['url', 'url'],
        ];
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if ($model = Data::findOne(['url' => $this->url])) {
            return [
                'success' => true,
                'message' => 'Data already exists.',
                'qr_file' => Yii::$app->request->hostInfo . $model->qr_file,
                'url' => Yii::$app->urlManager->createAbsoluteUrl(['site/redirect', 'code' => $model->code]),
            ];
        }

        $model = new Data();
        $model->name = $this->name;
        $model->url = $this->url;
        do {
            $code = str_replace('-', '_', Yii::$app->security->generateRandomString(6));
        } while (Data::find()->where(['code' => $code])->exists());
        $model->code = $code;
        $isSave = $model->save(false);

        if (!$isSave) {
            return ['success' => false, 'message' => 'Failed to save'];
        }

        $writer = new PngWriter();
        // Create QR code
        $qrCode = new QrCode(
            data: Yii::$app->urlManager->createAbsoluteUrl(['site/redirect', 'code' => $model->code]),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        /*
        // Create generic logo
        $logo = new Logo(
            path: Yii::getAlias('@webroot/images/logo.jpg'),
            resizeToWidth: 50,
            punchoutBackground: true
        );
        */
        // Create generic label
        $label = new Label(
            text: $model->name,
            textColor: new Color(255, 0, 0)
        );

        $result = $writer->write(
            qrCode: $qrCode,
            //logo: $logo,
            label: $label,
        );
        $filePath = $this->makeFolderAndGetPath($model->code);

        // Save it to a file
        $result->saveToFile($filePath);

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        //$dataUri = $result->getDataUri();

        $model->qr_file = Yii::getAlias('@web/qr_code/' . $model->code . '.png');

        return [
            'success' => $model->save(false),
            'message' => 'Successfully created',
            'qr_file' => Yii::$app->request->hostInfo . $model->qr_file,
            'url' => Yii::$app->urlManager->createAbsoluteUrl(['site/redirect', 'code' => $model->code]),
        ];
    }

    public function makeFolderAndGetPath($code)
    {
        $qrDir = Yii::getAlias('@webroot/qr_code');
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0775, true);
        }
        return $qrDir . '/' . $code . '.png';
    }
}