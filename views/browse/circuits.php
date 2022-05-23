<?php $this->session->flash(); ?>
<div class="container text-center" id="container">
    <div class="spinner-border text-primary" id="container-spinner"></div>
    <h1 class="text-center" id="page-title"></h1>
    <p id="page-description"></p>
    <div class="text-center">
        <img id="page-image" class="img-fluid w-50" style="min-width: 200px;">
    </div>
</div>

<script src="<?php echo WEBROOT ?>/webroot/js/tools/tools.js"></script>
<script>
    var pageWanted = "circuits";
</script>
<script src="<?php echo WEBROOT ?>/webroot/js/f1/f1-information-page.js"></script>