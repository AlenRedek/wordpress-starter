<?php

global $cpt, $year;
$years = ar_get_archives($cpt);

?>

<?php if($years): ?>
<div class="row xs-pb-30">
    <div class="col-xs-12">
        <select class="form-control select-links">
        <?php foreach($years as $y): ?>
            <option data-href="<?php echo update_url_params(array('years'=>$y), $cpt); ?>" <?php if($y == $year) echo 'selected'; ?>><?php echo $y; ?></option>
        <?php endforeach; ?>
        </select>
    </div>
</div>
<?php endif; ?>