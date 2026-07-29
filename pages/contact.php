<?php
$page_title = 'Contact Us - Get Support & Help';
$page_desc = 'Contact the ViewSnapStories team for support, feedback, or inquiries about our Snapchat viewer and downloader tools.';
$og_title = 'Contact ViewSnapStories';
$canonical = '/contact';
?>
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="/">Home</a></li><li class="breadcrumb-item active">Contact Us</li></ol></nav>
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h1 class="fw-bold mb-4">Contact Us</h1>
                <div class="bg-light p-4 rounded-4 mb-4">
                    <h5>Contact Information</h5>
                    <p class="mb-1">Telegram: <a href="https://t.me/s/viewsnapstories" target="_blank" rel="nofollow">@viewsnapstories</a></p>
                    <p class="mb-0">Email: <a href="mailto:contact@viewsnapstories.com">contact@viewsnapstories.com</a></p>
                </div>
                <div class="bg-light p-4 rounded-4">
                    <h5>Contact Form</h5>
                    <form id="contactForm" @submit.prevent="submitContact">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required minlength="2" maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required maxlength="200">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required maxlength="2000"></textarea>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <button type="submit" class="btn btn-warning px-4">Send message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Sending...';
    try {
        const resp = await fetch('/x/contact', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: this.name.value,
                email: this.email.value,
                subject: this.subject.value,
                message: this.message.value,
                csrf_token: this.csrf_token.value
            })
        });
        const data = await resp.json();
        if (data.success) {
            showToast('Message sent successfully!', 'success');
            this.reset();
        } else {
            showToast(data.error || 'Failed to send message', 'error');
        }
    } catch(e) {
        showToast('Network error. Please try again.', 'error');
    }
    btn.disabled = false;
    btn.textContent = 'Send message';
});
</script>
