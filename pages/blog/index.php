<?php
$page_title = 'Blog - Snapchat Tips & Guides';
$page_desc = 'Read the latest guides and tutorials about Snapchat features, tips, and how to use ViewSnapStories tools.';
$og_title = 'Blog - View Snap Stories';
$canonical = '/blog/';
?>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <h1 class="fw-bold mb-4">Blog</h1>
            <?php
            $postsFile = __DIR__ . '/../../data/posts.json';
            $posts = [];
            if (file_exists($postsFile)) {
                $posts = json_decode(file_get_contents($postsFile), true) ?? [];
            }
            if (empty($posts)) {
                echo '<p class="text-muted">No posts yet. Check back soon!</p>';
            } else {
                $page = max(1, intval($_GET['page'] ?? 1));
                $perPage = 6;
                $total = count($posts);
                $offset = ($page - 1) * $perPage;
                $paginated = array_slice($posts, $offset, $perPage);
                foreach ($paginated as $post): ?>
                    <article class="bg-light p-4 rounded-4 mb-4">
                        <h2 class="h4"><a href="/blog/<?= e($post['slug']) ?>" class="text-decoration-none"><?= e($post['title']) ?></a></h2>
                        <p class="text-muted small">By <?= e($post['author']) ?> - <?= date('F j, Y', $post['created_at']) ?></p>
                        <p><?= e($post['excerpt']) ?></p>
                        <a href="/blog/<?= e($post['slug']) ?>" class="btn btn-outline-warning btn-sm">Read More</a>
                    </article>
                <?php endforeach;
                if ($total > $perPage): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= ceil($total / $perPage); $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="/blog/?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif;
            } ?>
        </div>
    </div>
</div>
