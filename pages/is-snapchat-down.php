<?php
$page_title = 'Is Snapchat Down? Check Current Status & Troubleshooting';
$page_desc = 'Check if Snapchat is down right now. Get current status, troubleshooting tips, and links to official outage reports.';
$og_title = 'Is Snapchat Down?';
$canonical = '/is-snapchat-down';
?>
<section class="text-center hero-section">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb justify-content-center"><li class="breadcrumb-item"><a href="/">Home</a></li><li class="breadcrumb-item active">Is Snapchat down?</li></ol></nav>
        <h1>Is Snapchat down right now?</h1>
        <div id="status-result" class="my-4">
            <div class="bg-light p-4 rounded-4 d-inline-block">
                <h2 id="status-text" class="mb-0">Checking...</h2>
                <div id="status-detail" class="mt-2 text-muted">Running a quick check on Snapchat's server...</div>
            </div>
        </div>
        <button class="btn btn-warning px-4" onclick="checkStatus()" id="status-btn">Check again</button>
    </div>
</section>
<section class="py-5"><div class="container"><div class="bg-light py-5 rounded-4 px-4"><h2 class="mb-4 text-center fw-bold">Troubleshooting Tips</h2><ul><li>Confirm you are online - open another site or app on the same network</li><li>Force-close Snapchat completely, then reopen it</li><li>Update Snapchat from the App Store or Google Play</li><li>If snaps or messages freeze, sign out and back in, or restart your phone</li></ul><h3 class="mt-4">Check outage reports</h3><ul><li><a href="https://downdetector.com/status/snapchat/" target="_blank" rel="nofollow">Downdetector - Snapchat user reports</a></li><li><a href="https://isdown.app/integrations/snapchat" target="_blank" rel="nofollow">IsDown - Snapchat status</a></li></ul></div></div></section>
<script>
async function checkStatus() {
    const btn = document.getElementById('status-btn');
    const text = document.getElementById('status-text');
    const detail = document.getElementById('status-detail');
    btn.disabled = true;
    btn.textContent = 'Checking...';
    text.textContent = 'Checking...';
    detail.textContent = 'Running a quick check on Snapchat\'s server...';
    try {
        const resp = await fetch('/x/status/snapchat');
        const data = await resp.json();
        if (data.success) {
            if (data.status === 'online') {
                text.textContent = '🟢 Snapchat is online';
                detail.textContent = `Responded with HTTP ${data.http_code} in ${data.response_time_ms}ms. If your app still acts up, it may be your phone or internet.`;
            } else {
                text.textContent = '🟡 Snapchat may be experiencing issues';
                detail.textContent = `Check Downdetector for user reports.`;
            }
        } else {
            text.textContent = '🔴 Could not check';
            detail.textContent = 'Our check failed. This could be a network issue on our end.';
        }
    } catch(e) {
        text.textContent = '🔴 Check failed';
        detail.textContent = 'Network error. Please try again.';
    }
    btn.disabled = false;
    btn.textContent = 'Check again';
}
checkStatus();
</script>
<?php require __DIR__ . '/../inc/tool-links.php'; ?>
