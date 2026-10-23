<?php if ($pager->hasPrevious() || $pager->hasNext()) : ?>

<nav class="modern-pagination">

    <ul class="pagination justify-content-center align-items-center">

        <!-- PREVIOUS -->
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link nav-btn"
                   href="<?= $pager->getPreviousPage('users') ?>">
                    ← Prev
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link nav-btn">← Prev</span>
            </li>
        <?php endif ?>

        <!-- PAGE NUMBERS -->
        <?php foreach ($pager->links('users') as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link number"
                   href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <!-- NEXT -->
        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link nav-btn"
                   href="<?= $pager->getNextPage('users') ?>">
                    Next →
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link nav-btn">Next →</span>
            </li>
        <?php endif ?>

    </ul>

</nav>

<?php endif ?>