<?php

use app\common\Constants;
use app\common\Helper;
use app\models\activerecord\UserRoles;
use app\models\activerecord\Users;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
/**
 * @var string $startDate
 * @var string $endDate
 * @var Users[] $users
 * @var array $stats
 * @var Users $user
*/
$identity = Yii::$app->user->identity;
?>
<div class="row mb-4">
    <div class="col-md-4 col-12 d-flex align-items-center">
        <h3 class="mb-0 font-weight-normal">Dashboard</h3>
    </div>
</div>


<div class="row mb-2">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex clearfix">
                    <div class="text-left mt-3"><p class="card-text text-muted mb-1">Total Submissions</p>
                        <h2 class="mb-0 text-dark mainvalue"><?= Helper::printNumber(Helper::emptyOrValue($stats['total_submissions'])) ?></h2>
                    </div>
                    <div class="ml-auto">
                        <span class="bg-success-transparent icon-service text-success">
                            <i class="si si-people fs-2"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex clearfix">
                    <div class="text-left mt-3"><p class="card-text text-muted mb-1">Final Url</p>
                        <h2 style="font-size: 14px;white-space: normal;word-break: break-word;" class="mb-0 text-dark mainvalue"><?= $stats['final_url'] ?></h2>
                    </div>
                    <div class="ml-auto">
                        <span class="bg-primary-transparent icon-service text-primary">
                            <i class="si si-list fs-2"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
