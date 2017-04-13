

<div style="width: 300px; min-height: 400px;">
    <?= CHtml::form('', 'post', array('class' => 'form-horizontal')); ?>
    <!-- То самое место где будут выводиться ошибки
        если они будут при валидации !-->

    <p>
        <? if($info):?>
    <p style="color: red;"><?= $info; ?></p>
    <? endif;?>
</p>
<div class="form-group">
    <?= CHtml::activeLabel($form, 'username', array('class' => 'control-label col-sm-2',)) ?>
    <div class="col-sm-10">
        <?= CHtml::activeTextField($form, 'username', array('class' => 'form-control',)) ?>
    </div>
</div>
<div class="form-group">
    <?= CHtml::activeLabel($form, 'email', array('class' => 'control-label col-sm-2',)) ?>
    <div class="col-sm-10">
        <?= CHtml::activeTextField($form, 'email', array('class' => 'form-control',)) ?>
    </div>
</div>
<div class="form-group">
    <?= CHtml::activeLabel($form, 'jabber', array('class' => 'control-label col-sm-2',)) ?>
    <div class="col-sm-10">
        <?= CHtml::activeTextField($form, 'jabber', array('class' => 'form-control',)) ?>
    </div>
</div>
<div class="form-group">
    <?= CHtml::activeLabel($form, 'password', array('class' => 'control-label col-sm-2',)) ?>
    <div class="col-sm-10">
        <?= CHtml::activePasswordField($form, 'password', array('class' => 'form-control',)) ?>
    </div>
</div>

<div class="form-group">
    <label for="key" class="control-label col-sm-2">Ключ</label>
    <div class="col-sm-10">
        <input type="text" name="key" class="form-control" id="key">
    </div>
</div>



<?= CHtml::submitButton('Регистрация', array('id' => "submit", 'class' => 'btn btn-info btn-xs submit')); ?>


<?= CHtml::endForm(); ?>

</div>