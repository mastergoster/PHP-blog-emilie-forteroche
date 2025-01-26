<?php 
    /** 
     * Affichage de la partie admin : les statistiques des articles.
     */
?>

<h2>Les Statistiques du site</h2>

<table class="adminArticle">
    <thead>
        <tr>
            <td class="titre <?= $tri[0] === 'titre' ? ($tri[1] === 'ASC' ? 'sort-up' : 'sort-down') : '' ?>">
                <a href="<?= Utils::generateUrl('statistiques', [], 'titre', $tri) ?>" title="Cliquez pour trier">
                    Nom de l'article
                </a>
            </td>
            <td class="<?= $tri[0] === 'vues' ? ($tri[1] === 'ASC' ? 'sort-up' : 'sort-down') : '' ?>">
                <a href="<?= Utils::generateUrl('statistiques', [], 'vues', $tri) ?>" title="Cliquez pour trier">
                    Nombre de vues
                </a>
            </td>
            <td class="<?= $tri[0] === 'comment' ? ($tri[1] === 'ASC' ? 'sort-up' : 'sort-down') : '' ?>">
                <a href="<?= Utils::generateUrl('statistiques', [], 'comment', $tri) ?>" title="Cliquez pour trier">
                    Nombre de commentaires
                </a>
            </td>
            <td class="<?= $tri[0] === 'creation' ? ($tri[1] === 'ASC' ? 'sort-up' : 'sort-down') : '' ?>">
                <a href="<?= Utils::generateUrl('statistiques', [], 'creation', $tri) ?>" title="Cliquez pour trier">
                    Date de publication
                </a>
            </td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article) : ?>
            <tr>
                <td class="titre"><?= $article->getTitle() ?></td>
                <td><?= $article->getNbView() ?></td>
                <td> <?= $article->getNbComments() ?>
                    <?php if ($article->getNbComments() > 0) : ?>
                        <a class="submit m-20" href="<?= Utils::generateUrl('adminComments', ["articleId" => $article->getId()]) ?>">Voir</a>
                    <?php endif; ?>
                </td>
                <td><?= Utils::convertDateToFrenchFormat($article->getDateCreation()) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>