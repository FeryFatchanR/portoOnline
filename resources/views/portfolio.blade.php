@php
    $person = $profile->singleLineName();
    $brand = $profile->brandName();
    $mark = $profile->logo_text ?: collect(explode(' ', $brand))->map(fn ($w) => mb_substr($w, 0, 1))->take(3)->implode('');
    $headline = $profile->tagline ?: ($profile->subtitle ?: $brand);
    $clients = $experiences->pluck('company')
        ->map(fn ($c) => trim(explode(' - ', $c)[0]))
        ->merge($organizations->pluck('organization'))
        ->filter()->unique()->values();
    $nav = array_filter([
        'layanan' => $services->isNotEmpty() ? 'Layanan' : null,
        'proyek' => $projects->isNotEmpty() ? 'Proyek' : null,
        'tentang' => 'Tentang',
        'tim' => $team->isNotEmpty() ? 'Tim' : null,
        'kontak' => 'Kontak',
    ]);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $brand }}{{ $profile->tagline ? ' · '.$profile->tagline : '' }}</title>
    <meta name="description" content="{{ $profile->description ?: $headline }}">
    <meta property="og:title" content="{{ $brand }}">
    <meta property="og:description" content="{{ $headline }}">
    <meta name="color-scheme" content="light dark">
    <script>
        try {
            var t = localStorage.getItem('theme');
            if (t === 'light' || t === 'dark') document.documentElement.dataset.theme = t;
        } catch (e) {}
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600&family=Schibsted+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Layout: company profile — header sticky, hero dua kolom (pesan + panel ringkasan), lalu layanan, proyek, tentang, tim, CTA, footer. */
        :root {
            --bg: #F5F7F6;
            --surface: #FFFFFF;
            --surface-2: #EBF0EE;
            --ink: #0F1618;
            --muted: #56636A;
            --line: #DAE1DF;
            --accent: #0A7563;
            --accent-strong: #075E4F;
            --accent-ink: #FFFFFF;
            --accent-soft: #DCEFEA;
            --band: #0B2B26;
            --band-ink: #E8F4F1;
            --band-muted: #A5C4BC;
            --ok: #1C8A4B;

            --font-display: "Schibsted Grotesk", "Helvetica Neue", Arial, sans-serif;
            --font-body: "IBM Plex Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
            --font-mono: "IBM Plex Mono", ui-monospace, Menlo, Consolas, monospace;

            --step--1: 0.8125rem;
            --step-0: 1rem;
            --step-1: 1.1875rem;
            --step-2: 1.5rem;
            --step-3: clamp(1.875rem, 1.4rem + 1.6vw, 2.625rem);
            --step-4: clamp(2.375rem, 1.6rem + 3.2vw, 4rem);

            --radius: 14px;
            --wrap: 1180px;
            color-scheme: light;
        }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg: #0C1012;
                --surface: #141A1D;
                --surface-2: #192124;
                --ink: #E6ECEA;
                --muted: #97A4A3;
                --line: #253034;
                --accent: #3FC6AA;
                --accent-strong: #63D6BD;
                --accent-ink: #04201A;
                --accent-soft: #11302A;
                --band: #10302A;
                --band-ink: #E8F4F1;
                --band-muted: #A5C4BC;
                --ok: #4ADE80;
                color-scheme: dark;
            }
        }
        :root[data-theme="dark"] {
            --bg: #0C1012;
            --surface: #141A1D;
            --surface-2: #192124;
            --ink: #E6ECEA;
            --muted: #97A4A3;
            --line: #253034;
            --accent: #3FC6AA;
            --accent-strong: #63D6BD;
            --accent-ink: #04201A;
            --accent-soft: #11302A;
            --band: #10302A;
            --band-ink: #E8F4F1;
            --band-muted: #A5C4BC;
            --ok: #4ADE80;
            color-scheme: dark;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; scroll-padding-top: 80px; }
        @media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } *, *::before, *::after { transition: none !important; } }
        body { margin: 0; background: var(--bg); color: var(--ink); font-family: var(--font-body); font-size: var(--step-0); line-height: 1.6; -webkit-font-smoothing: antialiased; }
        body::before { content: ""; position: fixed; inset: 0 0 auto; height: env(safe-area-inset-top, 0px); z-index: 60; background: var(--bg); }
        img { max-width: 100%; display: block; }
        a { color: inherit; }
        :focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; border-radius: 6px; }
        h1, h2, h3 { font-family: var(--font-display); line-height: 1.12; margin: 0; text-wrap: balance; }
        p { margin: 0; }
        ul { margin: 0; padding: 0; list-style: none; }

        .wrap { max-width: var(--wrap); margin: 0 auto; padding-left: max(20px, env(safe-area-inset-left, 0px)); padding-right: max(20px, env(safe-area-inset-right, 0px)); }
        .section { padding-block: clamp(64px, 9vw, 112px); }
        .section + .section { border-top: 1px solid var(--line); }
        .section-head { display: flex; flex-direction: column; gap: 12px; max-width: 640px; margin-bottom: 48px; }
        .kicker { font-size: var(--step--1); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--accent); }
        .section-head h2 { font-size: var(--step-3); font-weight: 700; letter-spacing: -0.015em; }
        .section-head p { color: var(--muted); font-size: var(--step-1); }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 20px; border-radius: 10px; font-weight: 600; font-size: 0.9375rem; text-decoration: none; border: 1px solid var(--line); background: var(--surface); color: var(--ink); transition: border-color .15s, background-color .15s; white-space: nowrap; }
        .btn:hover { border-color: var(--accent); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: var(--accent-ink); }
        .btn-primary:hover { background: var(--accent-strong); border-color: var(--accent-strong); }
        .btn svg { width: 18px; height: 18px; flex: none; }

        /* ---------- Header ---------- */
        .site-header { position: sticky; top: env(safe-area-inset-top, 0px); z-index: 50; background: color-mix(in srgb, var(--bg) 88%, transparent); backdrop-filter: saturate(1.4) blur(10px); -webkit-backdrop-filter: saturate(1.4) blur(10px); border-bottom: 1px solid var(--line); }
        .header-row { display: flex; align-items: center; gap: 24px; min-height: 68px; }
        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; min-width: 0; margin-right: auto; }
        .brand img { height: 36px; width: auto; }
        .brand-mark { width: 36px; height: 36px; border-radius: 9px; background: var(--accent); color: var(--accent-ink); display: grid; place-items: center; font-family: var(--font-display); font-weight: 800; font-size: 0.8125rem; letter-spacing: 0.02em; flex: none; }
        .brand-name { font-family: var(--font-display); font-weight: 700; font-size: 1.0625rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .nav { display: flex; gap: 4px; }
        .nav a { text-decoration: none; color: var(--muted); font-weight: 500; font-size: 0.9375rem; padding: 8px 12px; border-radius: 8px; transition: color .15s, background-color .15s; }
        .nav a:hover { color: var(--ink); background: var(--surface-2); }
        .header-actions { display: flex; align-items: center; gap: 8px; }
        .icon-btn { width: 40px; height: 40px; border-radius: 10px; border: 1px solid var(--line); background: var(--surface); color: var(--muted); display: inline-grid; place-items: center; cursor: pointer; }
        .icon-btn:hover { color: var(--ink); border-color: var(--muted); }
        .icon-btn svg { width: 18px; height: 18px; }
        .menu-btn { display: none; }
        .mobile-nav { border-top: 1px solid var(--line); padding-block: 8px 16px; }
        .mobile-nav a { display: block; padding: 12px 4px; text-decoration: none; font-weight: 500; border-bottom: 1px solid var(--line); }

        /* ---------- Hero ---------- */
        .hero { padding-block: clamp(56px, 8vw, 104px) clamp(48px, 6vw, 80px); }
        .hero-grid { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr); gap: clamp(32px, 5vw, 72px); align-items: center; }
        .hero-copy { display: flex; flex-direction: column; gap: 24px; }
        .status { display: inline-flex; align-items: center; gap: 8px; font-size: var(--step--1); font-weight: 600; color: var(--muted); background: var(--surface); border: 1px solid var(--line); border-radius: 999px; padding: 6px 12px; align-self: flex-start; }
        .status::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--ok); }
        .hero h1 { font-size: var(--step-4); font-weight: 800; letter-spacing: -0.025em; }
        .hero-lead { font-size: var(--step-1); color: var(--muted); max-width: 56ch; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; }

        .summary { background: var(--surface); border: 1px solid var(--line); border-radius: 20px; padding: 28px; display: flex; flex-direction: column; gap: 24px; box-shadow: 0 1px 2px rgb(0 0 0 / .04), 0 18px 40px -24px rgb(0 0 0 / .18); }
        .summary-person { display: flex; align-items: center; gap: 16px; }
        .avatar { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; background: var(--accent-soft); flex: none; }
        .avatar-fallback { display: grid; place-items: center; color: var(--accent); font-family: var(--font-display); font-weight: 800; }
        .summary-person strong { display: block; font-family: var(--font-display); font-size: var(--step-1); line-height: 1.25; }
        .summary-person span { color: var(--muted); font-size: 0.9375rem; }
        .stats { display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: var(--line); border-radius: 12px; overflow: hidden; border: 1px solid var(--line); margin: 0; }
        .stats div { background: var(--surface); padding: 16px; display: flex; flex-direction: column-reverse; gap: 2px; }
        .stats dt { font-size: var(--step--1); color: var(--muted); }
        .stats dd { margin: 0; font-family: var(--font-display); font-weight: 800; font-size: var(--step-3); line-height: 1; letter-spacing: -0.02em; }

        .clients { margin-top: clamp(48px, 6vw, 72px); display: flex; flex-wrap: wrap; align-items: center; gap: 12px 32px; padding-top: 28px; border-top: 1px solid var(--line); }
        .clients-label { font-size: var(--step--1); color: var(--muted); font-weight: 500; }
        .clients ul { display: flex; flex-wrap: wrap; gap: 12px 32px; }
        .clients li { font-family: var(--font-display); font-weight: 700; font-size: var(--step-1); color: var(--muted); }

        /* ---------- Layanan ---------- */
        .services { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr)); gap: 20px; }
        .service { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 28px; display: flex; flex-direction: column; gap: 14px; transition: border-color .15s; }
        .service:hover { border-color: color-mix(in srgb, var(--accent) 50%, var(--line)); }
        .service-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--accent-soft); color: var(--accent); display: grid; place-items: center; }
        .service-icon svg { width: 24px; height: 24px; }
        .service h3 { font-size: var(--step-2); font-weight: 700; letter-spacing: -0.01em; }
        .service > p { color: var(--muted); }
        .checks { display: flex; flex-direction: column; gap: 8px; margin-top: 4px; padding-top: 16px; border-top: 1px solid var(--line); }
        .checks li { display: grid; grid-template-columns: 18px minmax(0, 1fr); gap: 10px; font-size: 0.9375rem; }
        .checks svg { width: 18px; height: 18px; color: var(--accent); margin-top: 3px; }

        /* ---------- Proyek ---------- */
        .projects { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr)); gap: 24px; }
        .project { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; display: flex; flex-direction: column; }
        .project-media { aspect-ratio: 16 / 10; width: 100%; object-fit: cover; background: var(--surface-2); border-bottom: 1px solid var(--line); }
        .project-placeholder { aspect-ratio: 16 / 10; display: grid; place-items: center; background: linear-gradient(var(--line) 1px, transparent 1px) 0 0 / 100% 24px, var(--accent-soft); border-bottom: 1px solid var(--line); font-family: var(--font-display); font-weight: 800; font-size: 2.75rem; color: var(--accent); }
        .project-body { padding: 24px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
        .project-meta { font-family: var(--font-mono); font-size: var(--step--1); color: var(--muted); }
        .project h3 { font-size: var(--step-2); font-weight: 700; }
        .project p { color: var(--muted); }
        .tags { display: flex; flex-wrap: wrap; gap: 6px; }
        .tags li { font-family: var(--font-mono); font-size: 0.75rem; padding: 4px 10px; border-radius: 999px; background: var(--surface-2); border: 1px solid var(--line); }
        .project-links { display: flex; gap: 20px; margin-top: auto; padding-top: 8px; }
        .project-links a { font-weight: 600; color: var(--accent); text-decoration: none; }
        .project-links a:hover { text-decoration: underline; text-underline-offset: 3px; }

        /* ---------- Tentang ---------- */
        .about-grid { display: grid; grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr); gap: clamp(32px, 5vw, 80px); align-items: start; }
        .founder { display: flex; flex-direction: column; gap: 20px; position: sticky; top: 100px; }
        .founder-photo { width: 100%; max-width: 360px; aspect-ratio: 4 / 5; object-fit: cover; border-radius: 18px; background: var(--accent-soft); }
        .founder-fallback { width: 100%; max-width: 360px; aspect-ratio: 4 / 5; border-radius: 18px; display: grid; place-items: center; background: linear-gradient(var(--line) 1px, transparent 1px) 0 0 / 100% 28px, var(--accent-soft); font-family: var(--font-display); font-weight: 800; font-size: 4rem; color: var(--accent); }
        .founder h3 { font-size: var(--step-2); }
        .founder-role { color: var(--muted); }
        .founder p { color: var(--muted); max-width: 52ch; }
        .about-col { display: flex; flex-direction: column; gap: 40px; min-width: 0; }
        .block-title { font-family: var(--font-display); font-size: var(--step-1); font-weight: 700; margin-bottom: 16px; }
        .timeline li { display: grid; grid-template-columns: 150px minmax(0, 1fr); gap: 4px 24px; padding-block: 16px; border-top: 1px solid var(--line); }
        .timeline li:first-child { border-top: 0; padding-top: 0; }
        .when { font-family: var(--font-mono); font-size: var(--step--1); color: var(--muted); padding-top: 3px; font-variant-numeric: tabular-nums; }
        .timeline strong { font-weight: 600; display: block; }
        .timeline .org { color: var(--accent); font-size: 0.9375rem; font-weight: 500; }
        .timeline p { color: var(--muted); font-size: 0.9375rem; margin-top: 6px; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chips li { display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 999px; background: var(--surface); border: 1px solid var(--line); font-size: 0.9375rem; font-weight: 500; }
        .chips small { font-size: 0.75rem; color: var(--muted); font-weight: 400; }
        .creds { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap: 12px; }
        .cred { background: var(--surface); border: 1px solid var(--line); border-radius: 12px; padding: 16px 18px; display: flex; flex-direction: column; gap: 4px; }
        .cred-type { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); }
        .cred strong { font-weight: 600; line-height: 1.35; }
        .cred span { color: var(--muted); font-size: 0.9375rem; }
        .cred .num { font-family: var(--font-mono); color: var(--ink); font-variant-numeric: tabular-nums; }
        .cred a { text-decoration: none; }
        .cred a:hover { color: var(--accent); }

        /* ---------- Tim ---------- */
        .team { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 240px), 1fr)); gap: 24px; }
        .member { display: flex; flex-direction: column; gap: 12px; }
        .member-photo { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: var(--radius); background: var(--accent-soft); }
        .member-fallback { width: 100%; aspect-ratio: 1; border-radius: var(--radius); background: var(--accent-soft); color: var(--accent); display: grid; place-items: center; font-family: var(--font-display); font-weight: 800; font-size: 2.5rem; }
        .member h3 { font-size: var(--step-1); }
        .member span { color: var(--accent); font-weight: 500; font-size: 0.9375rem; }
        .member p { color: var(--muted); font-size: 0.9375rem; }
        .member a { font-size: 0.9375rem; font-weight: 600; color: var(--accent); text-decoration: none; }

        /* ---------- CTA & kontak ---------- */
        .cta { background: var(--band); color: var(--band-ink); border-radius: 24px; padding: clamp(32px, 6vw, 64px); display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); gap: 40px; align-items: center; }
        .cta h2 { font-size: var(--step-3); font-weight: 800; letter-spacing: -0.015em; }
        .cta p { color: var(--band-muted); font-size: var(--step-1); margin-top: 12px; max-width: 46ch; }
        .cta .hero-actions { margin-top: 28px; }
        .cta .btn { background: transparent; color: var(--band-ink); border-color: color-mix(in srgb, var(--band-ink) 30%, transparent); }
        .cta .btn:hover { border-color: var(--band-ink); }
        .cta .btn-primary { background: var(--band-ink); color: var(--band); border-color: var(--band-ink); }
        .cta .btn-primary:hover { background: #FFFFFF; }
        .contact-list { margin: 0; display: flex; flex-direction: column; gap: 0; }
        .contact-list div { display: grid; grid-template-columns: 96px minmax(0, 1fr); gap: 16px; padding-block: 14px; border-top: 1px solid color-mix(in srgb, var(--band-ink) 16%, transparent); }
        .contact-list div:first-child { border-top: 0; }
        .contact-list dt { color: var(--band-muted); font-size: var(--step--1); padding-top: 2px; }
        .contact-list dd { margin: 0; overflow-wrap: anywhere; }
        .contact-list a { text-decoration: none; font-weight: 500; }
        .contact-list a:hover { text-decoration: underline; text-underline-offset: 3px; }

        /* ---------- Footer ---------- */
        .site-footer { border-top: 1px solid var(--line); padding-block: 48px 40px; margin-top: clamp(64px, 9vw, 112px); }
        .footer-grid { display: grid; grid-template-columns: minmax(0, 1.4fr) repeat(2, minmax(0, 1fr)); gap: 32px; }
        .footer-grid p, .footer-grid li { color: var(--muted); font-size: 0.9375rem; }
        .footer-grid h3 { font-family: var(--font-body); font-size: var(--step--1); font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); margin-bottom: 12px; }
        .footer-grid ul { display: flex; flex-direction: column; gap: 8px; }
        .footer-grid a { text-decoration: none; }
        .footer-grid a:hover { color: var(--ink); }
        .footer-brand { display: flex; flex-direction: column; gap: 14px; max-width: 40ch; }
        .footer-bottom { margin-top: 40px; padding-top: 24px; border-top: 1px solid var(--line); display: flex; flex-wrap: wrap; justify-content: space-between; gap: 8px 24px; color: var(--muted); font-size: var(--step--1); }
        .footer-bottom a { text-decoration: none; }
        .footer-bottom a:hover { color: var(--ink); }

        @media (max-width: 960px) {
            .hero-grid, .about-grid, .cta { grid-template-columns: minmax(0, 1fr); }
            .founder { position: static; flex-direction: row; align-items: flex-start; flex-wrap: wrap; }
            .founder-photo, .founder-fallback { max-width: 200px; }
            .footer-grid { grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); }
            .footer-brand { grid-column: 1 / -1; }
        }
        @media (max-width: 860px) {
            .nav, .header-cta { display: none; }
            .menu-btn { display: inline-grid; }
        }
        @media (max-width: 560px) {
            .timeline li { grid-template-columns: minmax(0, 1fr); }
            .founder { flex-direction: column; }
            .contact-list div { grid-template-columns: minmax(0, 1fr); gap: 2px; }
            .footer-grid { grid-template-columns: minmax(0, 1fr); }
            .summary { padding: 20px; }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="wrap">
        <div class="header-row">
            <a class="brand" href="#top" aria-label="{{ $brand }} — ke atas">
                @if ($profile->logoUrl())
                    <img src="{{ $profile->logoUrl() }}" alt="Logo {{ $brand }}" height="36">
                @else
                    <span class="brand-mark" aria-hidden="true">{{ $mark }}</span>
                @endif
                <span class="brand-name">{{ $brand }}</span>
            </a>
            <nav class="nav" aria-label="Navigasi utama">
                @foreach ($nav as $id => $label)
                    <a href="#{{ $id }}">{{ $label }}</a>
                @endforeach
            </nav>
            <div class="header-actions">
                <button type="button" class="icon-btn" id="theme-toggle" aria-label="Ganti tema" title="Tema: Otomatis">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 0 0 18z" fill="currentColor"/></svg>
                </button>
                <a class="btn btn-primary header-cta" href="#kontak">Hubungi kami</a>
                <button type="button" class="icon-btn menu-btn" id="menu-toggle" aria-expanded="false" aria-controls="mobile-nav" aria-label="Buka menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>
        <nav class="mobile-nav" id="mobile-nav" aria-label="Navigasi" hidden>
            @foreach ($nav as $id => $label)
                <a href="#{{ $id }}">{{ $label }}</a>
            @endforeach
        </nav>
    </div>
</header>

<main id="top">
    <section class="hero">
        <div class="wrap">
            <div class="hero-grid">
                <div class="hero-copy">
                    @if ($profile->badge_text)
                        <span class="status">{{ trim($profile->badge_text, "✦ \t") }}</span>
                    @endif
                    <h1>{{ $headline }}</h1>
                    @if ($profile->description)
                        <p class="hero-lead">{{ $profile->description }}</p>
                    @endif
                    <div class="hero-actions">
                        <a class="btn btn-primary" href="#kontak">
                            Diskusikan kebutuhan Anda
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
                        </a>
                        @if ($services->isNotEmpty())
                            <a class="btn" href="#layanan">Lihat layanan</a>
                        @endif
                    </div>
                </div>

                <aside class="summary" aria-label="Ringkasan">
                    <div class="summary-person">
                        @if ($profile->photoUrl())
                            <img class="avatar" src="{{ $profile->photoUrl() }}" alt="Foto {{ $person }}" width="64" height="64">
                        @else
                            <span class="avatar avatar-fallback" aria-hidden="true">{{ $profile->logo_text ?: mb_substr($person, 0, 1) }}</span>
                        @endif
                        <div>
                            <strong>{{ $person }}</strong>
                            @if ($profile->subtitle)<span>{{ $profile->subtitle }}</span>@endif
                        </div>
                    </div>
                    @if ($stats->isNotEmpty())
                        <dl class="stats">
                            @foreach ($stats as $stat)
                                <div><dt>{{ $stat->label }}</dt><dd>{{ $stat->number }}</dd></div>
                            @endforeach
                        </dl>
                    @endif
                    @if ($profile->cvUrl())
                        <a class="btn" href="{{ $profile->cvUrl() }}" download>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 20h14"/></svg>
                            Unduh CV
                        </a>
                    @endif
                </aside>
            </div>

            @if ($clients->isNotEmpty())
                <div class="clients">
                    <span class="clients-label">Berpengalaman bersama</span>
                    <ul>
                        @foreach ($clients as $client)<li>{{ $client }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    @if ($services->isNotEmpty())
        <section class="section" id="layanan">
            <div class="wrap">
                <div class="section-head">
                    <span class="kicker">Layanan</span>
                    <h2>Apa yang bisa kami kerjakan</h2>
                    @if ($profile->services_subtitle)<p>{{ $profile->services_subtitle }}</p>@endif
                </div>
                <div class="services">
                    @foreach ($services as $service)
                        <article class="service">
                            <span class="service-icon"><x-service-icon :name="$service->icon" /></span>
                            <h3>{{ $service->title }}</h3>
                            @if ($service->description)<p>{{ $service->description }}</p>@endif
                            @if (! empty($service->features))
                                <ul class="checks">
                                    @foreach ($service->features as $feature)
                                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg><span>{{ $feature }}</span></li>
                                    @endforeach
                                </ul>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($projects->isNotEmpty())
        <section class="section" id="proyek">
            <div class="wrap">
                <div class="section-head">
                    <span class="kicker">Proyek</span>
                    <h2>Hasil kerja terpilih</h2>
                    @if ($profile->projects_subtitle)<p>{{ $profile->projects_subtitle }}</p>@endif
                </div>
                <div class="projects">
                    @foreach ($projects as $project)
                        <article class="project">
                            @if ($project->imageUrl())
                                <img class="project-media" src="{{ $project->imageUrl() }}" alt="Tampilan {{ $project->title }}" loading="lazy" width="640" height="400">
                            @else
                                <div class="project-placeholder" aria-hidden="true">{{ mb_substr($project->title, 0, 1) }}</div>
                            @endif
                            <div class="project-body">
                                @if ($project->role || $project->period)
                                    <div class="project-meta">{{ collect([$project->role, $project->period])->filter()->implode(' · ') }}</div>
                                @endif
                                <h3>{{ $project->title }}</h3>
                                @if ($project->summary)<p>{{ $project->summary }}</p>@endif
                                @if ($project->description)<p>{{ $project->description }}</p>@endif
                                @if (! empty($project->tech_stack))
                                    <ul class="tags" aria-label="Teknologi">
                                        @foreach ($project->tech_stack as $tech)<li>{{ $tech }}</li>@endforeach
                                    </ul>
                                @endif
                                @if ($project->url || $project->repo_url)
                                    <div class="project-links">
                                        @if ($project->url)<a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer">Lihat situs ↗</a>@endif
                                        @if ($project->repo_url)<a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer">Kode sumber ↗</a>@endif
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" id="tentang">
        <div class="wrap">
            <div class="section-head">
                <span class="kicker">Tentang</span>
                <h2>{{ filled($profile->company_name) ? 'Di balik '.$brand : 'Profil profesional' }}</h2>
                @if ($profile->experience_subtitle)<p>{{ $profile->experience_subtitle }}</p>@endif
            </div>
            <div class="about-grid">
                <div class="founder">
                    @if ($profile->photoUrl())
                        <img class="founder-photo" src="{{ $profile->photoUrl() }}" alt="Foto {{ $person }}" width="360" height="450" loading="lazy">
                    @else
                        <div class="founder-fallback" aria-hidden="true">{{ $profile->logo_text ?: mb_substr($person, 0, 1) }}</div>
                    @endif
                    <div style="display:flex;flex-direction:column;gap:8px;min-width:0;flex:1">
                        <h3>{{ $person }}</h3>
                        @if ($profile->subtitle)<div class="founder-role">{{ filled($profile->company_name) ? 'Pendiri · ' : '' }}{{ $profile->subtitle }}</div>@endif
                        @if ($profile->linkedin_url)
                            <div><a class="btn" href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" style="margin-top:8px">LinkedIn ↗</a></div>
                        @endif
                    </div>
                </div>

                <div class="about-col">
                    @if ($experiences->isNotEmpty() || $organizations->isNotEmpty())
                        <div>
                            <h3 class="block-title">Pengalaman</h3>
                            <ul class="timeline">
                                @foreach ($experiences as $experience)
                                    <li>
                                        <span class="when">{{ $experience->period }}</span>
                                        <div>
                                            <strong>{{ $experience->title }}</strong>
                                            <span class="org">{{ $experience->company }}</span>
                                            @if ($experience->description)<p>{{ $experience->description }}</p>@endif
                                        </div>
                                    </li>
                                @endforeach
                                @foreach ($organizations as $organization)
                                    <li>
                                        <span class="when">{{ $organization->period ?: 'Organisasi' }}</span>
                                        <div>
                                            <strong>{{ $organization->title }}</strong>
                                            <span class="org">{{ $organization->organization }}</span>
                                            @if ($organization->description)<p>{{ $organization->description }}</p>@endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($skills->isNotEmpty())
                        <div>
                            <h3 class="block-title">Keahlian teknis</h3>
                            <ul class="chips">
                                @foreach ($skills as $skill)
                                    <li>{{ $skill->name }}@if ($skill->level_label)<small>{{ $skill->level_label }}</small>@endif</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($educations->isNotEmpty() || $certifications->isNotEmpty())
                        <div>
                            <h3 class="block-title">Pendidikan &amp; sertifikasi</h3>
                            <div class="creds">
                                @foreach ($educations as $education)
                                    <div class="cred">
                                        <span class="cred-type">Pendidikan</span>
                                        <strong>{{ $education->institution }}</strong>
                                        <span>{{ collect([$education->degree, $education->period])->filter()->implode(' · ') }}</span>
                                        @if ($education->gpa !== null)
                                            <span>IPK <span class="num">{{ number_format((float) $education->gpa, 2) }} / {{ number_format((float) $education->gpa_scale, 2) }}</span></span>
                                        @endif
                                    </div>
                                @endforeach
                                @foreach ($certifications as $certification)
                                    <div class="cred">
                                        <span class="cred-type">Sertifikasi</span>
                                        <strong>
                                            @if ($certification->credential_url)
                                                <a href="{{ $certification->credential_url }}" target="_blank" rel="noopener noreferrer">{{ $certification->name }} ↗</a>
                                            @else
                                                {{ $certification->name }}
                                            @endif
                                        </strong>
                                        @if ($certification->issuer)<span>{{ $certification->issuer }}</span>@endif
                                        @if ($certification->score)<span>Skor <span class="num">{{ $certification->score }}</span></span>@endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($team->isNotEmpty())
        <section class="section" id="tim">
            <div class="wrap">
                <div class="section-head">
                    <span class="kicker">Tim</span>
                    <h2>Orang-orang di balik layanan kami</h2>
                    @if ($profile->team_subtitle)<p>{{ $profile->team_subtitle }}</p>@endif
                </div>
                <div class="team">
                    @foreach ($team as $member)
                        <article class="member">
                            @if ($member->photoUrl())
                                <img class="member-photo" src="{{ $member->photoUrl() }}" alt="Foto {{ $member->name }}" loading="lazy" width="320" height="320">
                            @else
                                <div class="member-fallback" aria-hidden="true">{{ mb_substr($member->name, 0, 1) }}</div>
                            @endif
                            <div>
                                <h3>{{ $member->name }}</h3>
                                @if ($member->position)<span>{{ $member->position }}</span>@endif
                            </div>
                            @if ($member->bio)<p>{{ $member->bio }}</p>@endif
                            @if ($member->linkedin_url)<a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn ↗</a>@endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" id="kontak">
        <div class="wrap">
            <div class="cta">
                <div>
                    <h2>{{ $profile->contact_title ?: 'Siap memulai proyek?' }}</h2>
                    @if ($profile->contact_subtitle)<p>{{ $profile->contact_subtitle }}</p>@endif
                    <div class="hero-actions">
                        @if ($profile->whatsapp_number)<a class="btn btn-primary" href="https://wa.me/{{ $profile->whatsapp_number }}" target="_blank" rel="noopener noreferrer">Chat WhatsApp</a>@endif
                        @if ($profile->email)<a class="btn {{ $profile->whatsapp_number ? '' : 'btn-primary' }}" href="mailto:{{ $profile->email }}">Kirim email</a>@endif
                    </div>
                </div>
                <dl class="contact-list">
                    @if ($profile->email)<div><dt>Email</dt><dd><a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></dd></div>@endif
                    @if ($profile->phone)<div><dt>Telepon</dt><dd><a href="tel:{{ $profile->phoneHref() }}">{{ $profile->phone }}</a></dd></div>@endif
                    @if ($profile->address)<div><dt>Alamat</dt><dd>{{ $profile->address }}</dd></div>@endif
                    @if ($profile->linkedin_url)<div><dt>LinkedIn</dt><dd><a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">Lihat profil ↗</a></dd></div>@endif
                </dl>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="wrap">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand" href="#top">
                    @if ($profile->logoUrl())
                        <img src="{{ $profile->logoUrl() }}" alt="Logo {{ $brand }}" height="36">
                    @else
                        <span class="brand-mark" aria-hidden="true">{{ $mark }}</span>
                    @endif
                    <span class="brand-name">{{ $brand }}</span>
                </a>
                <p>{{ $headline }}</p>
            </div>
            @if ($services->isNotEmpty())
                <div>
                    <h3>Layanan</h3>
                    <ul>
                        @foreach ($services as $service)<li><a href="#layanan">{{ $service->title }}</a></li>@endforeach
                    </ul>
                </div>
            @endif
            <div>
                <h3>Kontak</h3>
                <ul>
                    @if ($profile->email)<li><a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></li>@endif
                    @if ($profile->phone)<li><a href="tel:{{ $profile->phoneHref() }}">{{ $profile->phone }}</a></li>@endif
                    @if ($profile->address)<li>{{ $profile->address }}</li>@endif
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ now()->year }} {{ $brand }}. Hak cipta dilindungi.</span>
            <a href="#top">Kembali ke atas ↑</a>
        </div>
    </div>
</footer>

<script>
    (function () {
        var root = document.documentElement;
        var themeBtn = document.getElementById('theme-toggle');
        var names = { system: 'Otomatis', light: 'Terang', dark: 'Gelap' };
        var order = ['system', 'light', 'dark'];
        function current() { return root.dataset.theme || 'system'; }
        function render() {
            var label = 'Tema: ' + names[current()];
            themeBtn.title = label;
            themeBtn.setAttribute('aria-label', label + ' (klik untuk ganti)');
        }
        themeBtn.addEventListener('click', function () {
            var next = order[(order.indexOf(current()) + 1) % order.length];
            if (next === 'system') { delete root.dataset.theme; } else { root.dataset.theme = next; }
            try { next === 'system' ? localStorage.removeItem('theme') : localStorage.setItem('theme', next); } catch (e) {}
            render();
        });
        render();

        var menuBtn = document.getElementById('menu-toggle');
        var mobileNav = document.getElementById('mobile-nav');
        function setMenu(open) { mobileNav.hidden = !open; menuBtn.setAttribute('aria-expanded', String(open)); }
        menuBtn.addEventListener('click', function () { setMenu(mobileNav.hidden); });
        mobileNav.addEventListener('click', function (e) { if (e.target.tagName === 'A') setMenu(false); });
    })();
</script>
</body>
</html>
