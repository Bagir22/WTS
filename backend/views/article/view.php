<?php

use common\models\Comments\Comments;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var \common\models\Article\Article $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['../article/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['../article/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create comment', ['../comments/create', 'id' => $model->id], ['class' => 'btn btn-outline-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'body:ntext',
            'userId',
        ],
    ]) ?>


    <h4>Comments:</h4>

    <?php
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getComments(),
            'pagination' => [
                'pageSize' => yii::$app->params['comment.limit'],
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'userId',
                'articleId',
                'body:ntext',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Comments $model, $key, $index, $column) {
                        return Url::toRoute([sprintf("../comments/%s", $action), 'id' => $model->id]);
                    }
                ],
            ]
        ]);
    ?>

</div>
