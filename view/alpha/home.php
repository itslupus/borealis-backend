<p>welcome back <?=$name?></p><br>
<div class = 'dropdown'>
    <div class = 'dropdown-header padded'>week classes</div>
    <div class = 'dropdown-content'>
        <?php if(count($courses) == 0) { ?>
            <p>no courses this term</p>
        <?php } ?>
        <?php foreach($courses as $k => $v){ ?>
            <p><?=$k?></p>
            <?php foreach($v as $z){ ?>
                <p>> <?=$z?></p>
            <?php } ?>
            <br>
        <?php } ?>
    </div>
</div>
<br>
<div class = 'dropdown'>
    <div class = 'dropdown-header padded'>fees</div>
    <div class = 'dropdown-content'>
        <p>summary: <?=$summary_total?></p>
        <table border = 1>
            <?php foreach($summary_details as $detail){ ?>
                <tr>
                    <td><?=$detail->get_name()?></td>
                    <td><?=$detail->get_amnt()?></td>
                    <td>-<?=$detail->get_amnt_paid()?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>