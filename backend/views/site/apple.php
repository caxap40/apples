<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Тестовое задание Яблочки';
?>
<div class="d-grid gap-4">

    <div class="text-center bg-transparent">
        <button id="make_apple" type="button" class="btn btn-primary btn-lg">Навешать яблок</button>
    </div>

    <div class="harvest d-flex flex-wrap-reverse gap-1">
        <?php
//             var_dump($apples);
            $html = null;
            foreach ($apples as $k=>$a) {
                $cut = Html::button('Уронить', ($a['state'] === appleState::onTree->value ? null : ['disabled'=>true]));
                $eat = Html::tag('div',
                    Html::button('Откусить', array_merge(['class'=>'col'], ($a['state'] === appleState::rotten->value ? ['disabled'=>true] : []))) .
                    Html::input('text', ['class'=>'col']),
                    ['class'=>'d-flex', 'title'=>'Откусить % от целого']
                );
                $del = Html::button('Съесть', ($a['state'] === appleState::rotten->value ? ['disabled'=>true] : []));
                $html .= Html::tag('div',$cut . $eat . $del,
                    ['class'=>'apple d-flex flex-column justify-content-between', 'data-id'=>$k, 'style'=>['background-color'=>$a['color']]]);
            }
        echo $html;
        ?>
    </div>
</div>
