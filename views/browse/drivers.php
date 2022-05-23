<?php $this->session->flash(); ?>
<div class="container text-center" id="container">
    <div class="spinner-border text-primary" id="container-spinner"></div>
    <h1 class="text-center" id="page-title"></h1>
    <div class="text-center mb-2">
        <img id="page-image" class="img-fluid" style="max-width: 200px;">
    </div>
    <p id="page-description"></p>
</div>

<script src="<?php echo WEBROOT ?>/webroot/js/tools/tools.js"></script>
<script>
    var pageWanted = "drivers";
</script>
<script src="<?php echo WEBROOT ?>/webroot/js/f1/f1-information-page.js"></script>