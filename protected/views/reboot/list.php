<div>
    <?php //var_dump($reboots);?>
    <table id="head" class="table table-striped">
        <thead>
            <tr>
                <th width="150" class="form_label" align="center" style="text-align: center;">IP</th>
                <th style="text-align: center;">st1</th>
                <th style="text-align: center;">st2</th>
            </tr> 
        </thead>
        <? foreach($reboots as $reboot):?>
        <tr>
            <td width="150" class="form_label" align="center" style="text-align: center;"><?= $reboot->ip; ?></td>
            <td style="text-align: center;"><?= $reboot->st1; ?></td>
            <td style="text-align: center;"><?= $reboot->st2; ?></td>
        </tr>
        <? endforeach;?>

    </table>
</div>

