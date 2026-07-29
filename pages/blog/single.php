<script type="application/ld+json">{
    "@context":"https://schema.org",
    "@type":"Article",
    "headline":<?= json_encode($post['title']) ?>,
    "description":<?= json_encode($post['excerpt'] ?? '') ?>,
    "author":{"@type":"Person","name":<?= json_encode($post['author']) ?>},
    "datePublished":"<?= date('c', $post['created_at']) ?>",
    "dateModified":"<?= date('c', $post['updated_at'] ?? $post['created_at']) ?>",
    "mainEntityOfPage":{"@type":"WebPage","@id":"<?= SITE_URL ?>/blog/<?= e($post['slug']) ?>"},
    "publisher":{"@type":"Organization","name":"<?= SITE_NAME ?>"}
}</script>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="/">Home</a></li><li class="breadcrumb-item"><a href="/blog/">Blog</a></li><li class="breadcrumb-item active"><?= e($post['title']) ?></li></ol></nav>
            <h1 class="fw-bold mb-3"><?= e($post['title']) ?></h1>
            <p class="text-muted">By <?= e($post['author']) ?> - <?= date('F j, Y', $post['created_at']) ?></p>
            <div class="mt-4 blog-content">
                <?= $post['content'] ?>
            </div>
            <div class="mt-4">
                <a href="/blog/" class="btn btn-warning">&larr; Back to Blog</a>
            </div>
        </div>
    </div>
</div>
