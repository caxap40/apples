<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Тестовое задание Яблочки';
?>
<div class="d-grid gap-4">

    <div class="text-center bg-transparent">
        <button id="make_apple" type="button" class="btn btn-primary btn-lg">Больше яблок</button>
    </div>

        <div class="harvest d-flex flex-wrap-reverse gap-1">
        <?php
//             var_dump($apples);
            $html = null;
            foreach ($apples as $k=>$a) {
                $cut = Html::button('Уронить', array_merge(['class' => 'fall'], ($a['state'] === appleState::onTree->value ? [] : ['disabled'=>true])));
                $eat = Html::tag('div',
                    Html::input('text', 'percent', null, ['class'=>'col', 'autocomplete'=>'off']) .
                    Html::button('Откусить', array_merge(['class'=>'eat col'], ($a['state'] === appleState::rotten->value ? ['disabled'=>true] : []))),
                    ['class'=>'d-flex', 'title'=>'Откусить % от целого']
                );
                $del = Html::button('Съесть', array_merge(['class' => 'delete'], ($a['state'] === appleState::rotten->value ? ['disabled'=>true] : [])));
                $apple = Html::tag('div', null,
                                ['class'=>'back', 'style'=>['background-color'=>$a['color'], 'width'=>(string)($a['size']*150).'px']]) .
                    Html::tag('div',$cut . $eat . $del, ['class'=>'apple d-flex flex-column justify-content-between', 'data-id'=>$k]);
                $html .= Html::tag('div', $apple, ['class'=>'box']);
            }

            echo $html;
            $script = <<< JS
document.getElementById('make_apple').addEventListener('click', () => { window.location.href = '/index.php?action=generate' });
for (let apple of document.querySelectorAll('.apple')) {
      apple.addEventListener('click', (_e) => 
      { if (_e.target.classList.contains('fall')) window.location.href = '/index.php?action=fall&id='+_e.currentTarget.dataset.id;
        if (_e.target.classList.contains('eat')) window.location.href = 
            '/index.php?action=eat&id='+_e.currentTarget.dataset.id+'&percent='+_e.target.previousElementSibling.value;
        if (_e.target.classList.contains('delete')) window.location.href = '/index.php?action=delete&id='+_e.currentTarget.dataset.id;
      })
    }
JS;
            echo "\n<script>$script</script>";
      ?>
        </div>
</div>
