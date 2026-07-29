<?php
$page_title = 'Snapchat Username Finder - Search Snapchat Users';
$page_desc = 'Search Snapchat usernames by name, nickname, or keyword. Find public Snapchat accounts anonymously.';
$og_title = 'Snapchat Username Finder';
$canonical = '/username-finder';
?>
<section class="text-center hero-section">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb justify-content-center"><li class="breadcrumb-item"><a href="/">Home</a></li><li class="breadcrumb-item active">Username Finder</li></ol></nav>
        <h1>Snapchat Username Finder</h1>
        <p class="my-4">Search by name, nickname, or keyword to discover public Snapchat accounts. Tap a result to open their profile.</p>
        <form onsubmit="event.preventDefault();var v=document.getElementById('uf-query').value.trim().replace(/^@/,'');if(/^[a-z0-9._-]{2,30}$/i.test(v))window.location.href='/profile/'+v">
            <div class="d-flex flex-column flex-md-row gap-3 mb-2 justify-content-center">
                <div class="position-relative" style="max-width:400px;width:100%;">
                    <input type="text" id="uf-query" class="shadow-sm form-control" placeholder="Enter username or keyword">
                </div>
                <button class="shadow-sm btn btn-warning flex-shrink-0 px-4" type="submit">Find</button>
            </div>
        </form>
        <div class="tool-badges d-flex flex-wrap justify-content-center mt-4"><a href="/"><span class="glass-badge">Story Viewer</span></a><a href="/view-profile"><span class="glass-badge">Profile Viewer</span></a><a href="/snapchat-story-downloader"><span class="glass-badge">Story Downloader</span></a><a href="/snapchat-spotlight-downloader"><span class="glass-badge">Spotlight Downloader</span></a><a href="/snapchat-video-downloader"><span class="glass-badge">Video Downloader</span></a></div>
        <p><span class="ps-3 pe-3">&#10004; 100% Anonymous</span><span class="ps-3 pe-3">&#10004; No Login Required</span><span class="ps-3 pe-3">&#10004; Free to Use</span><span class="ps-3 pe-3">&#10004; Secure &amp; Private</span></p>
    </div>
</section>

<section class="py-5"><div class="container"><div class="bg-light py-5 rounded-4"><h2 class="mb-5 text-center fw-bold">How to Find a Snapchat Username</h2><div class="row"><div class="col-lg-4 text-center p-4"><p class="features-icon">🔎</p><h3 class="mt-3 mb-3">1. Enter a Name or Keyword</h3><p>Type a name, nickname, or keyword. Exact username not required.</p></div><div class="col-lg-4 text-center p-4"><p class="features-icon">⚙️</p><h3 class="mt-3 mb-3">2. Browse Results</h3><p>Matching public profiles show up with profile pics and usernames.</p></div><div class="col-lg-4 text-center p-4"><p class="features-icon">👀</p><h3 class="mt-3 mb-3">3. Open Profile Anonymously</h3><p>Tap a result to view their public profile. No login needed, no trace left.</p></div></div></div></div></section>
<section class="py-5"><div class="container"><h2 class="mb-4 text-center fw-bold">FAQ</h2><div class="col-lg-8 offset-lg-2"><div class="my-3 faq"><p class="mb-1 fw-bold">Can I find any Snapchat account?</p><p class="m-0">Only public accounts that Snapchat makes searchable.</p></div><div class="my-3 faq"><p class="mb-1 fw-bold">Do I need a Snapchat account?</p><p class="m-0">No. No account or login needed.</p></div><div class="my-3 faq"><p class="mb-1 fw-bold">Is this free?</p><p class="m-0">Yes. No subscription, no paywalled features.</p></div></div></div></section>
<?php require __DIR__ . '/../inc/tool-links.php'; ?>
