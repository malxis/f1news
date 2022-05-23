<?php $this->session->flash(); ?>
<div class="container">

    <h2 class="text-center"><?php echo $pageData->title; ?></h2>

    <?php echo html_entity_decode($pageData->content); ?>

</div>