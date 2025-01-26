<?php 
    /** 
     * Affichage de la partie admin : les commentaires.
     */
?>

<h2>Moderation des commentaires</h2>

<table class="adminArticle">
    <thead>
        <tr>
            <td>
                commentaire
            </td>
            <td class="<?= $tri[0] === 'pseudo' ? ($tri[1] === 'ASC' ? 'sort-up' : 'sort-down') : '' ?>">
                <a href="<?= Utils::generateUrl('adminComments', ["articleId" => $idArticle], 'pseudo', $tri) ?>" title="Cliquez pour trier">
                    pseudo
                </a>
            </td>
            <td class="<?= $tri[0] === 'creation' ? ($tri[1] === 'ASC' ? 'sort-up' : 'sort-down') : '' ?>">
                <a href="<?= Utils::generateUrl('adminComments', ["articleId" => $idArticle], 'creation', $tri) ?>" title="Cliquez pour trier">
                    Date de publication
                </a>
            </td>
            <td>
                Actions
            </td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comments as $comment) : ?>
            <tr>
                <td class="titre"><?= $comment->getContent() ?></td>
                <td><?= $comment->getPseudo() ?></td>
                <td><?= Utils::convertDateToFrenchFormat($comment->getDateCreation()) ?></td>
                <td>
                    <a class="submit" href="<?= Utils::generateUrl('deleteComment', ['id' => $comment->getId(), "csrf_token" => $csrfToken, "articleId" => $idArticle]) ?>" title="Supprimer le commentaire">
                        supprimer
                    </a>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>