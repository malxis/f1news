<?php $this->session->flash(); ?>
<div class="row pe-2">
    <div class="col ms-2">

        <a class="btn btn-primary" href="<?php echo WEBROOT ?>/news/read/<?php echo $idNews ?>" role="button"><i class="far fa-newspaper"></i> Voir l'article</a>

        <div class="text-center">
            <span hidden id="no-comment" class="text-muted">Cet article n'a aucun commentaire.</span>
            <button hidden class="btn btn-secondary me-1 mt-2 btn-collapse" type="button" id="btn-collapse-approved">
                <i class="fas fa-check-circle text-success"></i><span class="ms-2" id="count-approved"><?php echo $elementCountCommentsApproved ?></span> commentaires approuvés
            </button>

            <button hidden class="btn btn-secondary me-1 mt-2 btn-collapse" type="button" id="btn-collapse-waiting">
                <i class="fas fa-clock text-warning"></i><span class="ms-2" id="count-waiting"><?php echo $elementCountCommentsWaiting ?></span> commentaires en attente de vérification
            </button>
            <button hidden class="btn btn-secondary mt-2 btn-collapse" type="button" id="btn-collapse-refused">
                <i class="fas fa-times-circle text-danger"></i><span class="ms-2" id="count-refused"><?php echo $elementCountCommentsRefused ?></span> commentaires refusés
            </button>
        </div>



        <div class="row">
            <div class="comments-collapse" id="comments-collapse-approved" hidden>
                <h4 class="mt-1">Liste des commentaires approuvés :</h4>
                <div class="mb-1">
                    <div class="accordion accordion-flush" id="accordion-flush-comments-approved">
                        <?php
                        foreach ($commentsApproved as $comment) {
                        ?>

                            <div class="accordion-item" id="comment-item-<?php echo $comment->id ?>">
                                <h2 class="accordion-header" id="flush-heading<?php echo $comment->id ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $comment->id ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $comment->id ?>">
                                        <span class="comment-description">Commentaire posté par <?php echo $comment->firstName ?> <?php echo $comment->lastName ?> le <?php echo $this->tools->dateTimeEnglishToFrench($comment->datePosted) ?>.</span><span class="ms-1">Approuvé par <?php echo $comment->adminFirstName ?> <?php echo $comment->adminLastName ?>.</span>
                                    </button>
                                </h2>
                                <div id="flush-collapse<?php echo $comment->id ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $comment->id ?>" data-bs-parent="#accordion-flush-comments-approved">
                                    <div class="accordion-body">
                                        <?php echo html_entity_decode($comment->content) ?>
                                        <div class="text-center">
                                            <button hidden type="button" onclick="approveComment(<?php echo $comment->id ?>);" class="btn btn-success btn-approve"><i class="fas fa-check text-white"></i> Approuver</button>
                                            <button type="button" onclick="refuseComment(<?php echo $comment->id ?>);" class="btn btn-danger btn-refuse"><i class="fas fa-times text-white"></i> Refuser</button>
                                            <button type="button" onclick="deleteComment(<?php echo $comment->id ?>);" class="btn btn-secondary"><i class="fas fa-trash text-white"></i> Supprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>



            <div class="comments-collapse" id="comments-collapse-waiting" hidden>
                <h4 class="mt-1">Liste des commentaires en attente :</h4>
                <div class="mb-1">
                    <div class="accordion accordion-flush" id="accordion-flush-comments-waiting">
                        <?php
                        foreach ($commentsWaiting as $comment) {
                        ?>

                            <div class="accordion-item" id="comment-item-<?php echo $comment->id ?>">
                                <h2 class="accordion-header" id="flush-heading<?php echo $comment->id ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $comment->id ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $comment->id ?>">
                                        <span class="comment-description">Commentaire posté par <?php echo $comment->firstName ?> <?php echo $comment->lastName ?> le <?php echo $this->tools->dateTimeEnglishToFrench($comment->datePosted) ?>.</span>
                                    </button>
                                </h2>
                                <div id="flush-collapse<?php echo $comment->id ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $comment->id ?>" data-bs-parent="#accordion-flush-comments-waiting">
                                    <div class="accordion-body">
                                        <?php echo html_entity_decode($comment->content) ?>
                                        <div class="text-center">
                                            <button type="button" onclick="approveComment(<?php echo $comment->id ?>);" class="btn btn-success btn-approve"><i class="fas fa-check text-white"></i> Approuver</button>
                                            <button type="button" onclick="refuseComment(<?php echo $comment->id ?>);" class="btn btn-danger btn-refuse"><i class="fas fa-times text-white"></i> Refuser</button>
                                            <button type="button" onclick="deleteComment(<?php echo $comment->id ?>);" class="btn btn-secondary"><i class="fas fa-trash text-white"></i> Supprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>



            <div class="comments-collapse" id="comments-collapse-refused" hidden>
                <h4 class="mt-1">Liste des commentaires refusés :</h4>
                <div class="mb-1">
                    <div class="accordion accordion-flush" id="accordion-flush-comments-refused">
                        <?php
                        foreach ($commentsRefused as $comment) {
                        ?>

                            <div class="accordion-item" id="comment-item-<?php echo $comment->id ?>">
                                <h2 class="accordion-header" id="flush-heading<?php echo $comment->id ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $comment->id ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $comment->id ?>">
                                        <span class="comment-description">Commentaire posté par <?php echo $comment->firstName ?> <?php echo $comment->lastName ?> le <?php echo $this->tools->dateTimeEnglishToFrench($comment->datePosted) ?>.</span><span class="ms-1"> Refusé par <?php echo $comment->adminFirstName ?> <?php echo $comment->adminLastName ?>. Raison : <?php echo $comment->reasonRefused ?></span>
                                    </button>
                                </h2>
                                <div id="flush-collapse<?php echo $comment->id ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $comment->id ?>" data-bs-parent="#accordion-flush-comments-refused">
                                    <div class="accordion-body">
                                        <?php echo html_entity_decode($comment->content) ?>
                                        <div class="text-center">
                                            <button type="button" onclick="approveComment(<?php echo $comment->id ?>);" class="btn btn-success btn-approve"><i class="fas fa-check text-white"></i> Approuver</button>
                                            <button hidden type="button" onclick="refuseComment(<?php echo $comment->id ?>);" class="btn btn-danger btn-refuse"><i class="fas fa-times text-white"></i> Refuser</button>
                                            <button type="button" onclick="deleteComment(<?php echo $comment->id ?>);" class="btn btn-secondary"><i class="fas fa-trash text-white"></i> Supprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    const btnCollapses = document.querySelectorAll(".btn-collapse");
    const commentsCollapses = document.querySelectorAll(".comments-collapse");

    const btnCollapseApproved = document.getElementById("btn-collapse-approved");
    const btnCollapseWaiting = document.getElementById("btn-collapse-waiting");
    const btnCollapseRefused = document.getElementById("btn-collapse-refused");
    const noComment = document.getElementById("no-comment");

    const countApproved = document.getElementById("count-approved");
    const countWaiting = document.getElementById("count-waiting");
    const countRefused = document.getElementById("count-refused");

    const approvedCommentAccordion = document.getElementById("accordion-flush-comments-approved");
    const waitingCommentAccordion = document.getElementById("accordion-flush-comments-waiting");
    const refusedCommentAccordion = document.getElementById("accordion-flush-comments-refused");

    const adminName = "<?php echo ($_SESSION["user"]->firstName) ? $_SESSION["user"]->firstName : 0 ?> <?php echo ($_SESSION["user"]->lastName) ? $_SESSION["user"]->lastName : 0 ?>";

    for (var i = 0; i < btnCollapses.length; i++) {
        btnCollapses[i].addEventListener("click", function(e) {
            e.preventDefault();
            var stateBtn = e.srcElement.id.replace("btn-collapse-", "");
            for (collapse of commentsCollapses) {
                var stateCollapse = collapse.id.replace("comments-collapse-", "");
                collapse.hidden = true;
                if (stateCollapse === stateBtn) {
                    collapse.hidden = false;
                }
            }
        });
    }
    updateButtons();

    function approveComment(idComment) {

        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "<?php echo WEBROOT ?>/comments/approve_comment", true);
        xhttp.setRequestHeader("Content-Type", "application/json");
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                // Return 1 if the the comments has been approved else 0
                reponseStatus = parseInt(this.responseText);
                if (reponseStatus) {
                    var commentItem = document.getElementById("comment-item-" + idComment);

                    // Move item from one accordion to another
                    commentItem.remove();
                    approvedCommentAccordion.appendChild(commentItem);
                    commentItem.querySelector(".accordion-button").classList.add("collapsed");
                    commentItem.querySelector(".accordion-collapse").classList.remove("show");
                    commentItem.querySelector(".accordion-collapse").setAttribute("data-bs-parent", "#accordion-flush-comments-approved");

                    // Update accordion item description
                    var commentDesc = commentItem.querySelector(".comment-description");
                    var approvedBy = document.createElement("span");
                    approvedBy.classList.add("ms-1");
                    approvedBy.innerHTML = "Approuvé par " + adminName + ".";
                    var buttonDesc = commentItem.querySelector(".accordion-button");
                    buttonDesc.innerHTML = "";
                    buttonDesc.appendChild(commentDesc);
                    buttonDesc.appendChild(approvedBy);

                    // Hide / Show buttons
                    commentItem.querySelector(".btn-approve").hidden = true;
                    commentItem.querySelector(".btn-refuse").hidden = false;

                    updateButtons();

                }
            }
        };
        var data = {
            id: idComment
        };
        xhttp.send(JSON.stringify(data));
    }


    function refuseComment(idComment) {

        var refuseReason = prompt("Veuillez indiquer la raison du refus :");

        if (refuseReason != null) {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "<?php echo WEBROOT ?>/comments/refuse_comment", true);
            xhttp.setRequestHeader("Content-Type", "application/json");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    // Return 1 if the the comments has been refused else 0
                    reponseStatus = parseInt(this.responseText);
                    if (reponseStatus) {
                        commentItem = document.getElementById("comment-item-" + idComment);

                        // Move item from one accordion to another
                        commentItem.remove();
                        refusedCommentAccordion.appendChild(commentItem);
                        commentItem.querySelector(".accordion-button").classList.add("collapsed");
                        commentItem.querySelector(".accordion-collapse").classList.remove("show");
                        commentItem.querySelector(".accordion-collapse").setAttribute("data-bs-parent", "#accordion-flush-comments-refused");

                        // Update accordion item description
                        var commentDesc = commentItem.querySelector(".comment-description");
                        var refusedBy = document.createElement("span");
                        refusedBy.classList.add("ms-1");
                        refusedBy.innerHTML = "Refusé par " + adminName + ". Raison : " + refuseReason;
                        var buttonDesc = commentItem.querySelector(".accordion-button");
                        buttonDesc.innerHTML = "";
                        buttonDesc.appendChild(commentDesc);
                        buttonDesc.appendChild(refusedBy);

                        // Hide / Show buttons
                        commentItem.querySelector(".btn-approve").hidden = false;
                        commentItem.querySelector(".btn-refuse").hidden = true;

                        updateButtons();

                    }
                }
            };
            var data = {
                id: idComment,
                reason: encodeURI(refuseReason)
            };
            xhttp.send(JSON.stringify(data));
        }
    }




    function deleteComment(idComment) {
        if (confirm("Êtes-vous sur de vouloir supprimer ce commentaire ?")) {

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "<?php echo WEBROOT ?>/comments/delete_comment", true);
            xhttp.setRequestHeader("Content-Type", "application/json");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    // Return 1 if the the comments has been deleted else 0
                    reponseStatus = parseInt(this.responseText);
                    if (reponseStatus) {

                        var commentItem = document.getElementById("comment-item-" + idComment);
                        commentItem.remove(commentItem);

                        updateButtons();
                    }
                }
            };
            var data = {
                id: idComment
            };
            xhttp.send(JSON.stringify(data));

        }
    }


    function updateButtons() {
        // Update comments count
        countApproved.innerHTML = approvedCommentAccordion.childElementCount;
        countWaiting.innerHTML = waitingCommentAccordion.childElementCount;
        countRefused.innerHTML = refusedCommentAccordion.childElementCount;

        // Hide / Show button
        if (approvedCommentAccordion.childElementCount > 0)
            btnCollapseApproved.hidden = false;
        else {
            btnCollapseApproved.hidden = true;
            approvedCommentAccordion.parentNode.parentNode.hidden = true;
        }

        if (waitingCommentAccordion.childElementCount > 0)
            btnCollapseWaiting.hidden = false;
        else {
            btnCollapseWaiting.hidden = true;
            waitingCommentAccordion.parentNode.parentNode.hidden = true;
        }

        if (refusedCommentAccordion.childElementCount > 0)
            btnCollapseRefused.hidden = false;
        else {
            btnCollapseRefused.hidden = true;
            refusedCommentAccordion.parentNode.parentNode.hidden = true;
        }

        noComment.hidden = true;
        if (approvedCommentAccordion.childElementCount == 0 && waitingCommentAccordion.childElementCount == 0 && refusedCommentAccordion.childElementCount == 0)
            noComment.hidden = false;
    }
</script>