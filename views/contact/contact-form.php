<?php $this->session->flash(); ?>
<div class="container">
    <div class="row">

        <form method="POST" action="<?php echo WEBROOT ?>/contact">

            <div class="row">
                <div class="col mb-3">
                    <label for="subject" class="form-label">Sujet</label>
                    <input type="text" class="form-control" placeholder="Écrivez le sujet de votre message..." id="subject" name="subject" maxlength="256" required>
                </div>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="message" class="form-label">Votre message</label>
                    <textarea class="form-control" placeholder="Écrivez votre message..." id="message" name="message" rows="5" maxlength="1024" required></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-secondary">Envoyer</button>
        </form>

    </div>
</div>