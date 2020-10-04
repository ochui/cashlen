<?php
use app\common\Constants;
use app\common\Helper;

$finalPageUrl = Helper::getSettingValue(Constants::SETTINGS_FINAL_URL);
$thanksPagePixelCode = Helper::getSettingValue(Constants::SETTINGS_THANKS_PAGE_PIXEL_CODE);
?>
<script>
    const FINAL_PAGE_URL = "<?=$finalPageUrl?>";
</script>
<?=$thanksPagePixelCode?>
<div class="main-wrapper-container">
    <div class="container">
        <div class="main-content" style="margin-bottom: 0;text-align: center;max-width: unset;width: 100%;">
            <h3 style="margin-top: 50px"><strong>Thank you for submitting the form our team will contact your asap.</strong></h3>
        </div>
    </div>
</div>
<?php
$js = <<<JS
    setTimeout(e=>{
        window.location.replace(FINAL_PAGE_URL);
    }, 3000);
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>
