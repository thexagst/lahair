<?php
/**
 * Custom Pagination Template
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Pagination">
    <ul class="pagination">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" class="page-link" aria-label="First">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="page-link" aria-label="Previous">
                    <span aria-hidden="true">‹</span>
                </a>
            </li>
        <?php else : ?>
            <li>
                <span class="page-link disabled" aria-label="First">
                    <span aria-hidden="true">«</span>
                </span>
            </li>
            <li>
                <span class="page-link disabled" aria-label="Previous">
                    <span aria-hidden="true">‹</span>
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="page-link <?= $link['active'] ? 'active' : '' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="page-link" aria-label="Next">
                    <span aria-hidden="true">›</span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" class="page-link" aria-label="Last">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        <?php else : ?>
            <li>
                <span class="page-link disabled" aria-label="Next">
                    <span aria-hidden="true">›</span>
                </span>
            </li>
            <li>
                <span class="page-link disabled" aria-label="Last">
                    <span aria-hidden="true">»</span>
                </span>
            </li>
        <?php endif ?>
    </ul>
</nav>
