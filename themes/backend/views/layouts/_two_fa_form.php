<?php
use yii\helpers\Html;
?>
<div class="form-group">
    <label for="">Two FA Code</label>
    <input type="text" class="form-control" name="two_fa_code" placeholder="Two FA code">
    <input type="hidden" name="two_fa_fail_redirect" value="<?=Yii::$app->getRequest()->getUrl()?>">
</div>
