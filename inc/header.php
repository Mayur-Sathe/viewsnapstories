<!DOCTYPE html>
<html lang="en" dir="ltr" class="<?= get_theme_class() ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= get_page_title($page_title ?? null) ?></title>
    <meta name="description" content="<?= get_page_description($page_desc ?? null) ?>">
    <meta name="keywords" content="<?= e($page_keywords ?? 'snapchat story viewer, snapchat anonymous viewer, snap viewer, snapchat anonymous, snapchat story anonymous, download snapchat stories') ?>">
    <meta name="google-site-verification" content="vqGDgqIr7cyMteuwXCJN8qUfvFWzBcHipp2nDIe9hoU" />
    <link rel="canonical" href="<?= get_canonical($canonical ?? '/') ?>">
    <link rel="alternate" href="https://viewsnapstories.com" hreflang="en">
    <link rel="alternate" href="<?= SITE_URL ?>" hreflang="x-default">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($og_title ?? 'Snapchat Story Viewer') ?>">
    <meta property="og:description" content="<?= e($og_desc ?? 'Use our Snapchat story viewer to watch public Snapchat stories anonymously without login or app. Fast, secure Snapchat viewer online.') ?>">
    <meta property="og:url" content="<?= get_canonical($canonical ?? '/') ?>">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    <meta property="og:image" content="<?= SITE_URL ?>/assets/img/viewsnapstories.svg">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($og_title ?? 'Snapchat Story Viewer') ?>">
    <meta name="twitter:description" content="<?= e($og_desc ?? 'Use our Snapchat story viewer to watch public Snapchat stories anonymously without login or app. Fast, secure Snapchat viewer online.') ?>">
    <meta name="twitter:image" content="<?= SITE_URL ?>/assets/img/viewsnapstories.svg">

    <meta name="robots" content="<?= !empty($page_noindex) ? 'noindex,follow' : 'index,follow' ?>">

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://flagcdn.com">

    <!-- TODO: Uncomment and add your Google Search Console verification code when domain is set up -->
    <!-- <meta name="google-site-verification" content="YOUR_VERIFICATION_CODE"> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/custom.css?v=<?= SITE_VERSION ?>">

    <link rel="icon" type="image/png" href="/assets/img/snaplogo2.png">
    <link rel="apple-touch-icon" href="/assets/img/snaplogo2.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff">

    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= GA_MEASUREMENT_ID ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= GA_MEASUREMENT_ID ?>');
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "<?= SITE_URL ?>/#organization",
                "name": "<?= SITE_NAME ?>",
                "url": "<?= SITE_URL ?>",
                "logo": { "@type": "ImageObject", "url": "<?= SITE_URL ?>/assets/img/viewsnapstories.svg" },
                "sameAs": []
            },
            {
                "@type": "WebSite",
                "@id": "<?= SITE_URL ?>#website",
                "url": "<?= SITE_URL ?>",
                "name": "<?= SITE_NAME ?>",
                "description": "<?= get_page_description() ?>",
                "inLanguage": "en",
                "publisher": { "@id": "<?= SITE_URL ?>/#organization" },
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": { "@type": "EntryPoint", "urlTemplate": "<?= SITE_URL ?>/profile/{query}" },
                    "query-input": "required name=query"
                }
            },
            {
                "@type": "WebApplication",
                "@id": "<?= SITE_URL ?>#webapp",
                "name": "Snapchat Story Viewer",
                "url": "<?= SITE_URL ?>",
                "applicationCategory": "UtilitiesApplication",
                "operatingSystem": "Any",
                "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" },
                "publisher": { "@id": "<?= SITE_URL ?>/#organization" }
            }
        ]
    }
    </script>
    <?php if (!empty($breadcrumbs)): ?>
    <?= breadcrumb_schema($breadcrumbs) ?>
    <?php endif; ?>
</head>
<body>
    <?php require __DIR__ . '/banner.php'; ?>
    <header>
        <nav class="border-bottom navbar navbar-expand-lg">
            <div class="container">
                <a class="d-flex align-items-center gap-2 navbar-brand" href="/">
                    <span class="logo-icon"><img src="/assets/img/snaplogo2.png" alt="logo" class="img-fluid mb-1" height="35" width="35" loading="lazy"></span>
                    <div class="brand-text">
                        <div class="fw-bold"><?= SITE_NAME ?></div>
                        <small class="sub-brand"><?= SITE_TAGLINE ?></small>
                    </div>
                </a>
                <button class="navbar-toggler" type="button" aria-label="Menu" data-bs-toggle="collapse" data-bs-target="#main_nav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="ms-auto navbar-nav">
                        <li class="my-2 nav-item <?= current_page('/') ?>"><a class="nav-link" href="/">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="my-2 nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Tools</a>
                            <ul class="dropdown-menu rounded-bottom fade-up">
                                <li><a class="dropdown-item" href="/">Snapchat Story Viewer</a></li>
                                <li><a class="dropdown-item" href="/view-profile">Snapchat Profile Viewer</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/snapchat-story-downloader">Snapchat Story Downloader</a></li>
                                <li><a class="dropdown-item" href="/snapchat-spotlight-downloader">Snapchat Spotlight Downloader</a></li>
                                <li><a class="dropdown-item" href="/snapchat-video-downloader">Snapchat Video Downloader</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/username-finder">Snapchat Username Finder</a></li>
                                <li><a class="dropdown-item" href="/snapchat-followers-count">Snapchat Followers Count</a></li>
                                <li><a class="dropdown-item" href="/is-snapchat-down">Is Snapchat down?</a></li>
                            </ul>
                        </li>
                        <li class="my-2 nav-item <?= current_page('/about') ?>"><a class="nav-link" href="/about">About</a></li>
                        <li class="my-2 nav-item <?= current_page('/how-it-works') ?>"><a class="nav-link" href="/how-it-works">How it Works</a></li>
                        <li class="my-2 nav-item <?= current_page('/faq') ?>"><a class="nav-link" href="/faq">FAQ</a></li>
                        <li class="my-2 nav-item <?= current_page('/contact') ?>"><a class="nav-link" href="/contact">Contact</a></li>
                        <li class="nav-item dropdown">
                            <a class="my-2 nav-link d-flex align-items-center dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <img src="https://flagcdn.com/w20/us.png" srcset="https://flagcdn.com/w40/us.png 2x" loading="lazy" class="me-1" height="15" width="20" alt=""> English
                            </a>
                            <ul class="dropdown-menu rounded-bottom fade-up menu-last">
                                <li><a class="dropdown-item active" href="/"><img src="https://flagcdn.com/w20/us.png" srcset="https://flagcdn.com/w40/us.png 2x" loading="lazy" class="me-1" height="15" width="20" alt=""> English (EN)</a></li>
                                <li><a class="dropdown-item" href="/de"><img src="https://flagcdn.com/w20/de.png" srcset="https://flagcdn.com/w40/de.png 2x" loading="lazy" class="me-1" height="15" width="20" alt=""> German (DE)</a></li>
                                <li><a class="dropdown-item" href="/es"><img src="https://flagcdn.com/w20/es.png" srcset="https://flagcdn.com/w40/es.png 2x" loading="lazy" class="me-1" height="15" width="20" alt=""> Spanish (ES)</a></li>
                                <li><a class="dropdown-item" href="/fr"><img src="https://flagcdn.com/w20/fr.png" srcset="https://flagcdn.com/w40/fr.png 2x" loading="lazy" class="me-1" height="15" width="20" alt=""> French (FR)</a></li>
                                <li><a class="dropdown-item" href="/nl"><img src="https://flagcdn.com/w20/nl.png" srcset="https://flagcdn.com/w40/nl.png 2x" loading="lazy" class="me-1" height="15" width="20" alt=""> Dutch (NL)</a></li>
                                <li><a class="dropdown-item" href="/ar"><img src="https://flagcdn.com/w20/sa.png" srcset="https://flagcdn.com/w40/sa.png 2x" loading="lazy" class="me-1" height="15" width="20" alt=""> Arabic (AR)</a></li>
                                <li><a class="dropdown-item" href="/hi"><img src="https://flagcdn.com/w20/in.png" srcset="https://flagcdn.com/w40/in.png 2x" loading="lazy" class="me-1" height="15" width="20" alt=""> हिंदी (HI)</a></li>
                            </ul>
                        </li>
                    </ul>
                    <span class="d-flex mt-3 mt-lg-0 ps-3">
                        <button id="themeToggle" class="theme-toggle" aria-label="Toggle dark mode">
                            <span class="icon-sun"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg></span>
                            <span class="icon-moon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></span>
                        </button>
                    </span>
                </div>
            </div>
        </nav>
    </header>
    <main>
