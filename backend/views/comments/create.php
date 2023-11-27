<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Comments\Comments $model */

$this->title = 'Create Comments';
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Автоматическое подставление id статьи, если переоход из создания коментария в статье -->
    <?= $model->articleId = yii::$app->request->resolve()[1]['id'] ?? "" ?>

    <?= $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>
