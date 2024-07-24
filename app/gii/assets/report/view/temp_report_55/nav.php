<div class='xblock'>
    <?php $ac = request()->action(); if($cate_field){ ?>
    <a href="<?php echo url('cate'); ?>" class="pear-btn  <?php if($ac=='cate'){ echo 'pear-btn-primary'; } ?> ">分类</a>
    <?php } ?>
    <?php if($time_field){ ?>
    <a href="<?php echo url('year'); ?>" class="pear-btn   <?php if($ac=='year'){ echo 'pear-btn-primary'; } ?> ">按年</a>
    <a href="<?php echo url('month'); ?>" class="pear-btn   <?php if($ac=='month'){ echo 'pear-btn-primary'; } ?> ">按月</a>
    <a href="<?php echo url('day'); ?>" class="pear-btn   <?php if($ac=='day' || $ac=='index'){ echo 'pear-btn-primary'; } ?> ">按日</a>
    <?php } ?>
</div>