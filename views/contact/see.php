<?php $this->session->flash(); ?>
<div class="d-flex ms-2 mt-2">
    <?php
    if ($privilege == "admin") {
    ?>
        <form method="POST" class="me-2" action="<?php echo WEBROOT ?>/contact/see/<?php echo $privilege . "/" . $contactData->idContact ?>">
            <input type="hidden" name="id" value="<?php echo $contactData->idContact ?>">
            <select name="actualState" id="actualState" class="form-select" onchange="this.form.submit()">
                <option value="1" <?php if ($contactData->actualState == 1) echo 'selected'; ?>>Nouveau</option>
                <option value="2" <?php if ($contactData->actualState == 2) echo 'selected'; ?>>Ouvert</option>
                <option value="3" <?php if ($contactData->actualState == 3) echo 'selected'; ?>>En attente</option>
                <option value="4" <?php if ($contactData->actualState == 4) echo 'selected'; ?>>Résolu</option>
                <option value="5" <?php if ($contactData->actualState == 5) echo 'selected'; ?>>Fermé</option>
            </select>
        </form>
    <?php
    }
    ?>
</div>

<div class="row">
    <div class="col text-center me-1 mt-2">
        <?php if ($userSeeHisOwnRequest) { ?>
            <span class="badge <?php echo $contactData->stateBadgeColor ?>"><?php echo $contactData->stateName ?></span>
        <?php } ?>
        <h4><?php echo $contactData->subject ?></h4>
    </div>
</div>

</form>
<div class="row">

    <div class="col-12 mt-2">
        <div class="card <?php if ($userSeeHisOwnRequest) echo ' float-end me-2';
                            else echo ' float-start ms-2'; ?>" style="max-width: 70vw;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <img class="img-fluid rounded-circle mb-2 me-3" style="width: 50px;" src="<?php echo WEBROOT ?>/webroot/img/users/<?php echo $contactData->filename ?>" alt="Photo de profil de <?php echo $contactData->firstName ?> <?php echo $contactData->lastName ?>">
                    <div class="row">
                        <h5 class="card-title"><?php echo $contactData->firstName ?> <?php echo $contactData->lastName ?></h5>
                        <small class="text-muted mt-n2">Le <?php echo $this->tools->dateTimeEnglishToFrench($contactData->dateSent) ?></small>
                    </div>
                </div>
                <p class="card-text"><?php echo $contactData->message ?></p>
            </div>
        </div>
    </div>

    <?php
    foreach ($contactAnswerData as $contactAnswer) {
    ?>
        <div class="col-12 mt-2">
            <div class="card <?php if (($userSeeHisOwnRequest && $contactAnswer->idUserSent == $contactData->idUserSent) || (!$userSeeHisOwnRequest && $contactAnswer->idUserSent != $contactData->idUserSent)) echo ' float-end me-2';
                                else echo ' float-start ms-2'; ?>" style="max-width: 70vw;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img class="img-fluid rounded-circle mb-2 me-3" style="width: 50px;" src="<?php echo WEBROOT ?>/webroot/img/users/<?php echo $contactAnswer->filename ?>" alt="Photo de profil de <?php echo $contactAnswer->firstName ?> <?php echo $contactAnswer->lastName ?>">
                        <div class="row">
                            <h5 class="card-title"><?php echo $contactAnswer->firstName ?> <?php echo $contactAnswer->lastName ?></h5>
                            <small class="text-muted mt-n2">Le <?php echo $this->tools->dateTimeEnglishToFrench($contactAnswer->dateSent) ?></small>
                        </div>
                    </div>
                    <p class="card-text"><?php echo $contactAnswer->message ?></p>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="col-12 mt-2">
        <form class="float-end me-2 ms-2" method="POST" action="<?php echo WEBROOT ?>/contact/see/<?php echo $privilege . "/" . $contactData->idContact ?>">
            <textarea class="form-control" placeholder="Écrivez votre message ici..." id="message" name="message" rows="2" cols="50" maxlength="1024" required></textarea>
            <button type="submit" class="btn btn-primary mt-2 float-end">Envoyer</button>
        </form>
    </div>



</div>