<?php $this->session->flash(); ?>
<div class="row">
    <div class="col me-2 ms-2">

        <?php if ($totalElementCount > 0 || $filter != 0) { ?>
            <form action="<?php echo WEBROOT ?>/contact/<?php echo $privilege ?>/<?php echo $page ?>" method="POST">
                <div class="table-responsive">
                    <table class="table border">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <?php if ($privilege == "admin") { ?>
                                        <select name="filter" id="filter" onchange="this.form.submit()">
                                            <option value="0" <?php if ($filter == 0) echo 'selected'; ?>>Aucun filtre</option>
                                            <?php foreach ($statesList as $state) { ?>
                                                <option value="<?php echo $state->id ?>" <?php if ($filter == $state->id) echo 'selected'; ?>><?php echo $state->stateName ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        Statut
                                    <?php } ?>
                                </th>
                                <?php if ($totalElementCount > 0) { ?>
                                    <th scope="col">Sujet</th>
                                    <th scope="col">Date</th>
                                    <?php if ($privilege == "admin")
                                        echo '<th scope="col">Posté par</th>' ?>
                                    <th scope="col" class="text-center">Ouvrir</th>
                                <?php } ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($contactRequestsList as $contactRequest) {
                            ?>
                                <tr>
                                    <td><span class="badge <?php echo $contactRequest->stateBadgeColor ?>"><?php echo $contactRequest->stateName ?></span></td>
                                    <td><?php echo $contactRequest->subject ?></td>
                                    <td><?php echo $this->tools->dateEnglishToFrench($contactRequest->dateSent) ?></td>
                                    <?php if ($privilege == "admin")
                                        echo '<td>' . $contactRequest->firstName . ' ' . $contactRequest->lastName . '</td>' ?>

                                    <td class="text-center"><a href="<?php echo WEBROOT ?>/contact/see/<?php echo $privilege . "/" . $contactRequest->idContact ?>"><i class="fas fa-external-link-alt"></i></a></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <?php if ($privilege == "admin" && $totalElementCount == 0) { ?>
                <p class="text-center text-muted">Il n'y a aucune demande avec ce statut.</p>
            <?php }
            // The user or admin hasn't got any requests
        } else {
            if ($privilege == "admin") { ?>
                <p class="text-center text-muted">Aucune demande n'a été faite.</p>
            <?php } else { ?>
                <p class="text-center text-muted">Vous n'avez fait aucune demande.</p>
        <?php }
        } ?>
    </div>
</div>
<?php
echo $this->tools->pagination($page, $path, $totalElementCount, $elementPerPage);
?>