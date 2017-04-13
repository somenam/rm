<div class="panel panel-default" style="width: 400px; min-height: 400px;">
    <?= CHtml::form('', 'post', array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data')); ?>


    <? if($info):?>
    <p style="color: red;"><?= $info; ?></p>
    <? endif;?>
    <p><b>Дата (день-месяц-год \ 13-04-2017)</b><br>
        <input type="text" name="date">
  </p>
  <input type=file name=uploadfile>
    <br>
    <p><?= CHtml::submitButton('Сохранить', array('id' => "submit", 'class' => 'btn btn-info btn-xs', 'style' => 'background-color: #61A3CB;')); ?></p>

    <?= CHtml::endForm(); ?>

</div>

