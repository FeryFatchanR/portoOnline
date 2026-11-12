@php
    $name = $profile->singleLineName();
    $mark = $profile->logo_text ?: collect(explode(' ', $name))->map(fn ($w) => mb_substr($w, 0, 1))->take(3)->implode('');
    $headline = $profile->tagline ?: ($profile->subtitle ?: $name);
    $roles = collect(preg_split('/\s*\|\s*/', (string) $profile->subtitle))->filter()->values();
    $workplaces = $experiences->pluck('company')
        ->map(fn ($c) => trim(explode(' - ', $c)[0]))
        ->merge($organizations->pluck('organization'))
        ->filter()->unique()->values();
    $nav = array_filter([
        'tentang' => 'Tentang',
        'pengalaman' => $experiences->isNotEmpty() || $organizations->isNotEmpty() ? 'Pengalaman' : null,
        'proyek' => $projects->isNotEmpty() ? 'Proyek' : null,
        'keahlian' => $skills->isNotEmpty() ? 'Keahlian' : null,
        'pendidikan' => $educations->isNotEmpty() || $certifications->isNotEmpty() ? 'Pendidikan' : null,
        'kontak' => 'Kontak',
    ]);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $name }} · Portfolio</title>
    <meta name="description" content="{{ $profile->description ?: $headline }}">
    <meta property="og:title" content="{{ $name }}">
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
        /* Layout: portfolio pribadi bergaya website company profile — header sticky, hero lebar, section penuh berselang warna, kartu, CTA band, footer. */
        :root {
            --bg: #F5F7F6;
            --bg-alt: #ECF1EF;
            --surface: #FFFFFF;
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
                --bg-alt: #101619;
                --surface: #151C1F;
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
            --bg-alt: #101619;
            --surface: #151C1F;
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
        html { scroll-behavior: smooth; scroll-padding-top: 76px; }
        @media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } *, *::before, *::after { transition: none !important; } }
        body { margin: 0; background: var(--bg); color: var(--ink); font-family: var(--font-body); font-size: var(--step-0); line-height: 1.6; -webkit-font-smoothing: antialiased; }
        body::before { content: ""; position: fixed; inset: 0 0 auto; height: env(safe-area-inset-top, 0px); z-index: 60; background: var(--bg); }
        img { max-width: 100%; display: block; }
        a { color: inherit; }
        :focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; border-radius: 6px; }
        h1, h2, h3 { font-family: var(--font-display); line-height: 1.12; margin: 0; text-wrap: balance; }
        p { margin: 0; }
        ul { margin: 0; padding: 0; list-style: none; }
        .num { font-family: var(--font-mono); font-variant-numeric: tabular-nums; }

        .wrap { max-width: var(--wrap); margin: 0 auto; padding-left: max(20px, env(safe-area-inset-left, 0px)); padding-right: max(20px, env(safe-area-inset-right, 0px)); }
        .section { padding-block: clamp(64px, 9vw, 112px); }
        .section.alt { background: var(--bg-alt); border-block: 1px solid var(--line); }
        .section-head { display: flex; flex-direction: column; gap: 12px; max-width: 660px; margin-bottom: 48px; }
        .kicker { font-size: var(--step--1); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--accent); }
        .section-head h2 { font-size: var(--step-3); font-weight: 700; letter-spacing: -0.015em; }
        .section-head p { color: var(--muted); font-size: var(--step-1); }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 20px; border-radius: 10px; font-weight: 600; font-size: 0.9375rem; text-decoration: none; border: 1px solid var(--line); background: var(--surface); color: var(--ink); transition: border-color .15s, background-color .15s; white-space: nowrap; }
        .btn:hover { border-color: var(--accent); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: var(--accent-ink); }
        .btn-primary:hover { background: var(--accent-strong); border-color: var(--accent-strong); }
        .btn svg { width: 18px; height: 18px; flex: none; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; }

        /* ---------- Header ---------- */
        .site-header { position: sticky; top: env(safe-area-inset-top, 0px); z-index: 50; background: color-mix(in srgb, var(--bg) 88%, transparent); backdrop-filter: saturate(1.4) blur(10px); -webkit-backdrop-filter: saturate(1.4) blur(10px); border-bottom: 1px solid var(--line); }
        .header-row { display: flex; align-items: center; gap: 24px; min-height: 68px; }
        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; min-width: 0; margin-right: auto; }
        .brand-mark { width: 36px; height: 36px; border-radius: 9px; background: var(--accent); color: var(--accent-ink); display: grid; place-items: center; font-family: var(--font-display); font-weight: 800; font-size: 0.8125rem; letter-spacing: 0.02em; flex: none; }
        .brand-name { font-family: var(--font-display); font-weight: 700; font-size: 1.0625rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .nav { display: flex; gap: 2px; }
        .nav a { text-decoration: none; color: var(--muted); font-weight: 500; font-size: 0.9375rem; padding: 8px 12px; border-radius: 8px; transition: color .15s, background-color .15s; }
        .nav a:hover { color: var(--ink); background: var(--bg-alt); }
        .header-actions { display: flex; align-items: center; gap: 8px; }
        .icon-btn { width: 40px; height: 40px; border-radius: 10px; border: 1px solid var(--line); background: var(--surface); color: var(--muted); display: inline-grid; place-items: center; cursor: pointer; }
        .icon-btn:hover { color: var(--ink); border-color: var(--muted); }
        .icon-btn svg { width: 18px; height: 18px; }
        .menu-btn { display: none; }
        .mobile-nav { border-top: 1px solid var(--line); padding-block: 8px 16px; }
        .mobile-nav a { display: block; padding: 12px 4px; text-decoration: none; font-weight: 500; border-bottom: 1px solid var(--line); }

        /* ---------- Hero ---------- */
        .hero { padding-block: clamp(56px, 8vw, 104px) clamp(40px, 5vw, 64px); }
        .hero-grid { display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr); gap: clamp(32px, 5vw, 72px); align-items: center; }
        .hero-copy { display: flex; flex-direction: column; gap: 24px; }
        .status { display: inline-flex; align-items: center; gap: 8px; font-size: var(--step--1); font-weight: 600; color: var(--muted); background: var(--surface); border: 1px solid var(--line); border-radius: 999px; padding: 6px 12px; align-self: flex-start; }
        .status::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--ok); }
        .hero h1 { font-size: var(--step-4); font-weight: 800; letter-spacing: -0.025em; }
        .hero-name { font-size: var(--step-1); color: var(--ink); font-weight: 600; }
        .hero-name span { color: var(--muted); font-weight: 400; }
        .hero-lead { font-size: var(--step-1); color: var(--muted); max-width: 56ch; }

        .profile-card { background: var(--surface); border: 1px solid var(--line); border-radius: 20px; overflow: hidden; box-shadow: 0 1px 2px rgb(0 0 0 / .04), 0 18px 40px -24px rgb(0 0 0 / .18); }
        .profile-photo { width: 100%; aspect-ratio: 5 / 4; object-fit: cover; background: var(--accent-soft); }
        .profile-fallback { width: 100%; aspect-ratio: 5 / 4; display: grid; place-items: center; background: linear-gradient(var(--line) 1px, transparent 1px) 0 0 / 100% 28px, var(--accent-soft); font-family: var(--font-display); font-weight: 800; font-size: clamp(3rem, 6vw, 4.5rem); color: var(--accent); }
        .stats { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); margin: 0; }
        .stats div { padding: 18px 20px; display: flex; flex-direction: column-reverse; gap: 2px; border-top: 1px solid var(--line); }
        .stats div:nth-child(odd) { border-right: 1px solid var(--line); }
        .stats dt { font-size: var(--step--1); color: var(--muted); }
        .stats dd { margin: 0; font-family: var(--font-display); font-weight: 800; font-size: var(--step-3); line-height: 1; letter-spacing: -0.02em; }

        .workplaces { margin-top: clamp(40px, 5vw, 64px); display: flex; flex-wrap: wrap; align-items: center; gap: 12px 32px; padding-top: 28px; border-top: 1px solid var(--line); }
        .workplaces-label { font-size: var(--step--1); color: var(--muted); font-weight: 500; }
        .workplaces ul { display: flex; flex-wrap: wrap; gap: 12px 32px; }
        .workplaces li { font-family: var(--font-display); font-weight: 700; font-size: var(--step-1); color: var(--muted); }

        /* ---------- Tentang ---------- */
        .about-grid { display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(0, 1fr); gap: clamp(32px, 5vw, 72px); align-items: start; }
        .about-text { display: flex; flex-direction: column; gap: 20px; }
        .about-text p { font-size: var(--step-1); color: var(--muted); max-width: 60ch; }
        .roles { display: flex; flex-wrap: wrap; gap: 8px; }
        .roles li { padding: 6px 14px; border-radius: 999px; background: var(--accent-soft); color: var(--ink); font-weight: 500; font-size: 0.9375rem; }
        .facts { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); margin: 0; }
        .facts div { display: grid; grid-template-columns: 110px minmax(0, 1fr); gap: 16px; padding: 16px 20px; border-top: 1px solid var(--line); }
        .facts div:first-child { border-top: 0; }
        .facts dt { color: var(--muted); font-size: var(--step--1); padding-top: 2px; }
        .facts dd { margin: 0; overflow-wrap: anywhere; font-weight: 500; }
        .facts a { text-decoration: none; }
        .facts a:hover { color: var(--accent); }

        /* ---------- Pengalaman ---------- */
        .timeline { display: flex; flex-direction: column; gap: 16px; }
        .job { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 24px 28px; display: grid; grid-template-columns: 190px minmax(0, 1fr); gap: 8px 32px; }
        .job-when { display: flex; flex-direction: column; gap: 4px; }
        .job-when .num { font-size: var(--step--1); color: var(--muted); }
        .job-when span:last-child { font-size: var(--step--1); color: var(--muted); }
        .job h3 { font-size: var(--step-2); font-weight: 700; letter-spacing: -0.01em; }
        .job-org { color: var(--accent); font-weight: 600; margin-top: 4px; }
        .job p { color: var(--muted); margin-top: 10px; max-width: 70ch; }
        .subhead { font-family: var(--font-display); font-size: var(--step-2); font-weight: 700; margin: 48px 0 16px; }

        /* ---------- Proyek ---------- */
        .projects { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr)); gap: 24px; }
        .project { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; display: flex; flex-direction: column; }
        .project-media { aspect-ratio: 16 / 10; width: 100%; object-fit: cover; background: var(--bg-alt); border-bottom: 1px solid var(--line); }
        .project-placeholder { aspect-ratio: 16 / 10; display: grid; place-items: center; background: linear-gradient(var(--line) 1px, transparent 1px) 0 0 / 100% 24px, var(--accent-soft); border-bottom: 1px solid var(--line); font-family: var(--font-display); font-weight: 800; font-size: 2.75rem; color: var(--accent); }
        .project-body { padding: 24px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
        .project-meta { font-family: var(--font-mono); font-size: var(--step--1); color: var(--muted); }
        .project h3 { font-size: var(--step-2); font-weight: 700; }
        .project p { color: var(--muted); }
        .tags { display: flex; flex-wrap: wrap; gap: 6px; }
        .tags li { font-family: var(--font-mono); font-size: 0.75rem; padding: 4px 10px; border-radius: 999px; background: var(--bg-alt); border: 1px solid var(--line); }
        .project-links { display: flex; gap: 20px; margin-top: auto; padding-top: 8px; }
        .project-links a { font-weight: 600; color: var(--accent); text-decoration: none; }
        .project-links a:hover { text-decoration: underline; text-underline-offset: 3px; }

        /* ---------- Keahlian ---------- */
        .skills { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 250px), 1fr)); gap: 16px; }
        .skill { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 20px; display: flex; flex-direction: column; gap: 14px; }
        .skill-top { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; }
        .skill h3 { font-family: var(--font-body); font-size: var(--step-0); font-weight: 600; }
        .skill-level { font-size: var(--step--1); color: var(--muted); white-space: nowrap; }
        .meter { display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px; }
        .meter i { height: 6px; border-radius: 3px; background: var(--line); }
        .meter i.on { background: var(--accent); }

        /* ---------- Pendidikan ---------- */
        .creds { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr)); gap: 16px; }
        .cred { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 24px; display: flex; flex-direction: column; gap: 6px; }
        .cred-type { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent); font-weight: 600; }
        .cred h3 { font-size: var(--step-1); font-weight: 700; line-height: 1.3; }
        .cred h3 a { text-decoration: none; }
        .cred h3 a:hover { color: var(--accent); }
        .cred p { color: var(--muted); font-size: 0.9375rem; }
        .cred-figure { margin-top: auto; padding-top: 14px; border-top: 1px solid var(--line); display: flex; align-items: baseline; gap: 8px; }
        .cred-figure strong { font-family: var(--font-mono); font-weight: 500; font-size: var(--step-2); font-variant-numeric: tabular-nums; }
        .cred-figure span { color: var(--muted); font-size: var(--step--1); }

        /* ---------- Kontak ---------- */
        .cta { background: var(--band); color: var(--band-ink); border-radius: 24px; padding: clamp(32px, 6vw, 64px); display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); gap: 40px; align-items: center; }
        .cta h2 { font-size: var(--step-3); font-weight: 800; letter-spacing: -0.015em; }
        .cta p { color: var(--band-muted); font-size: var(--step-1); margin-top: 12px; max-width: 46ch; }
        .cta .actions { margin-top: 28px; }
        .cta .btn { background: transparent; color: var(--band-ink); border-color: color-mix(in srgb, var(--band-ink) 30%, transparent); }
        .cta .btn:hover { border-color: var(--band-ink); }
        .cta .btn-primary { background: var(--band-ink); color: var(--band); border-color: var(--band-ink); }
        .cta .btn-primary:hover { background: #FFFFFF; }
        .contact-list { margin: 0; display: flex; flex-direction: column; }
        .contact-list div { display: grid; grid-template-columns: 96px minmax(0, 1fr); gap: 16px; padding-block: 14px; border-top: 1px solid color-mix(in srgb, var(--band-ink) 16%, transparent); }
        .contact-list div:first-child { border-top: 0; }
        .contact-list dt { color: var(--band-muted); font-size: var(--step--1); padding-top: 2px; }
        .contact-list dd { margin: 0; overflow-wrap: anywhere; }
        .contact-list a { text-decoration: none; font-weight: 500; }
        .contact-list a:hover { text-decoration: underline; text-underline-offset: 3px; }

        /* ---------- Footer ---------- */
        .site-footer { border-top: 1px solid var(--line); padding-block: 40px; }
        .footer-row { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px 32px; }
        .footer-row p { color: var(--muted); font-size: var(--step--1); }
        .footer-links { display: flex; flex-wrap: wrap; gap: 4px 20px; }
        .footer-links a { color: var(--muted); text-decoration: none; font-size: 0.9375rem; }
        .footer-links a:hover { color: var(--ink); }

        @media (max-width: 960px) {
            .hero-grid, .about-grid, .cta { grid-template-columns: minmax(0, 1fr); }
            .profile-card { max-width: 480px; }
        }
        @media (max-width: 900px) {
            .nav, .header-cta { display: none; }
            .menu-btn { display: inline-grid; }
        }
        @media (max-width: 600px) {
            .job { grid-template-columns: minmax(0, 1fr); padding: 20px; }
            .facts div, .contact-list div { grid-template-columns: minmax(0, 1fr); gap: 2px; }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="wrap">
        <div class="header-row">
            <a class="brand" href="#top" aria-label="{{ $name }}, ke atas">
                <span class="brand-mark" aria-hidden="true">{{ $mark }}</span>
                <span class="brand-name">{{ $name }}</span>
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
                <a class="btn btn-primary header-cta" href="#kontak">Hubungi saya</a>
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
                    <p class="hero-name">{{ $name }}@if ($profile->subtitle && $profile->tagline)<span> · {{ $profile->subtitle }}</span>@endif</p>
                    <div class="actions">
                        <a class="btn btn-primary" href="#kontak">
                            Hubungi saya
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
                        </a>
                        @if ($profile->cvUrl())
                            <a class="btn" href="{{ $profile->cvUrl() }}" download>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 20h14"/></svg>
                                Unduh CV
                            </a>
                        @elseif (isset($nav['pengalaman']))
                            <a class="btn" href="#pengalaman">Lihat pengalaman</a>
                        @endif
                    </div>
                </div>

                <aside class="profile-card" aria-label="Ringkasan">
                    @if ($profile->photoUrl())
                        <img class="profile-photo" src="{{ $profile->photoUrl() }}" alt="Foto {{ $name }}" width="480" height="384">
                    @else
                        <div class="profile-fallback" aria-hidden="true">{{ $mark }}</div>
                    @endif
                    @if ($stats->isNotEmpty())
                        <dl class="stats">
                            @foreach ($stats as $stat)
                                <div><dt>{{ $stat->label }}</dt><dd>{{ $stat->number }}</dd></div>
                            @endforeach
                        </dl>
                    @endif
                </aside>
            </div>

            @if ($workplaces->isNotEmpty())
                <div class="workplaces">
                    <span class="workplaces-label">Pengalaman di</span>
                    <ul>
                        @foreach ($workplaces as $place)<li>{{ $place }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    <section class="section alt" id="tentang">
        <div class="wrap">
            <div class="section-head">
                <span class="kicker">Tentang saya</span>
                <h2>Profil singkat</h2>
            </div>
            <div class="about-grid">
                <div class="about-text">
                    @if ($profile->description)<p>{{ $profile->description }}</p>@endif
                    @if ($roles->isNotEmpty())
                        <ul class="roles" aria-label="Peran">
                            @foreach ($roles as $role)<li>{{ $role }}</li>@endforeach
                        </ul>
                    @endif
                </div>
                <dl class="facts">
                    <div><dt>Nama</dt><dd>{{ $name }}</dd></div>
                    @if ($profile->address)<div><dt>Domisili</dt><dd>{{ $profile->address }}</dd></div>@endif
                    @if ($profile->email)<div><dt>Email</dt><dd><a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></dd></div>@endif
                    @if ($profile->phone)<div><dt>Telepon</dt><dd><a class="num" href="tel:{{ $profile->phoneHref() }}">{{ $profile->phone }}</a></dd></div>@endif
                    @if ($profile->linkedin_url)<div><dt>LinkedIn</dt><dd><a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">Lihat profil ↗</a></dd></div>@endif
                </dl>
            </div>
        </div>
    </section>

    @if (isset($nav['pengalaman']))
        <section class="section" id="pengalaman">
            <div class="wrap">
                <div class="section-head">
                    <span class="kicker">Pengalaman</span>
                    <h2>Riwayat pekerjaan</h2>
                    @if ($profile->experience_subtitle)<p>{{ $profile->experience_subtitle }}</p>@endif
                </div>
                @if ($experiences->isNotEmpty())
                    <ol class="timeline" style="list-style:none;margin:0;padding:0">
                        @foreach ($experiences as $experience)
                            <li class="job">
                                <div class="job-when">
                                    @if ($experience->period)<span class="num">{{ $experience->period }}</span>@endif
                                    @if ($experience->location)<span>{{ $experience->location }}</span>@endif
                                </div>
                                <div>
                                    <h3>{{ $experience->title }}</h3>
                                    <div class="job-org">{{ $experience->company }}</div>
                                    @if ($experience->description)<p>{{ $experience->description }}</p>@endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif

                @if ($organizations->isNotEmpty())
                    <h3 class="subhead">Organisasi</h3>
                    @if ($profile->organization_subtitle)<p style="color:var(--muted);margin:-8px 0 16px">{{ $profile->organization_subtitle }}</p>@endif
                    <ol class="timeline" style="list-style:none;margin:0;padding:0">
                        @foreach ($organizations as $organization)
                            <li class="job">
                                <div class="job-when">
                                    <span class="num">{{ $organization->period ?: 'Organisasi' }}</span>
                                </div>
                                <div>
                                    <h3>{{ $organization->title }}</h3>
                                    <div class="job-org">{{ $organization->organization }}</div>
                                    @if ($organization->description)<p>{{ $organization->description }}</p>@endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </section>
    @endif

    @if ($projects->isNotEmpty())
        <section class="section alt" id="proyek">
            <div class="wrap">
                <div class="section-head">
                    <span class="kicker">Proyek</span>
                    <h2>Proyek yang pernah saya kerjakan</h2>
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

    @if ($skills->isNotEmpty())
        <section class="section" id="keahlian">
            <div class="wrap">
                <div class="section-head">
                    <span class="kicker">Keahlian</span>
                    <h2>Kemampuan teknis</h2>
                    @if ($profile->skills_subtitle)<p>{{ $profile->skills_subtitle }}</p>@endif
                </div>
                <div class="skills">
                    @foreach ($skills as $skill)
                        @php $level = max(1, min(5, (int) round($skill->percentage / 20))); @endphp
                        <div class="skill">
                            <div class="skill-top">
                                <h3>{{ $skill->name }}</h3>
                                @if ($skill->level_label)<span class="skill-level">{{ $skill->level_label }}</span>@endif
                            </div>
                            <div class="meter" role="img" aria-label="Level {{ $level }} dari 5">
                                @for ($i = 1; $i <= 5; $i++)<i class="{{ $i <= $level ? 'on' : '' }}"></i>@endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (isset($nav['pendidikan']))
        <section class="section alt" id="pendidikan">
            <div class="wrap">
                <div class="section-head">
                    <span class="kicker">Pendidikan &amp; sertifikasi</span>
                    <h2>Latar belakang akademik</h2>
                    @if ($profile->education_subtitle)<p>{{ $profile->education_subtitle }}</p>@endif
                </div>
                <div class="creds">
                    @foreach ($educations as $education)
                        <article class="cred">
                            <span class="cred-type">Pendidikan</span>
                            <h3>{{ $education->institution }}</h3>
                            @if ($education->degree)<p>{{ $education->degree }}</p>@endif
                            <p>{{ collect([$education->period, $education->faculty, $education->location])->filter()->implode(' · ') }}</p>
                            @if ($education->gpa !== null)
                                <div class="cred-figure"><strong>{{ number_format((float) $education->gpa, 2) }}</strong><span>IPK dari {{ number_format((float) $education->gpa_scale, 2) }}</span></div>
                            @endif
                        </article>
                    @endforeach
                    @foreach ($certifications as $certification)
                        <article class="cred">
                            <span class="cred-type">Sertifikasi</span>
                            <h3>
                                @if ($certification->credential_url)
                                    <a href="{{ $certification->credential_url }}" target="_blank" rel="noopener noreferrer">{{ $certification->name }} ↗</a>
                                @else
                                    {{ $certification->name }}
                                @endif
                            </h3>
                            @if ($certification->issuer)<p>{{ $certification->issuer }}</p>@endif
                            @if ($certification->score)
                                <div class="cred-figure"><strong>{{ $certification->score }}</strong><span>Skor</span></div>
                            @endif
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
                    <h2>{{ $profile->contact_title ?: 'Mari bekerja sama' }}</h2>
                    @if ($profile->contact_subtitle)<p>{{ $profile->contact_subtitle }}</p>@endif
                    <div class="actions">
                        @if ($profile->whatsapp_number)<a class="btn btn-primary" href="https://wa.me/{{ $profile->whatsapp_number }}" target="_blank" rel="noopener noreferrer">Chat WhatsApp</a>@endif
                        @if ($profile->email)<a class="btn {{ $profile->whatsapp_number ? '' : 'btn-primary' }}" href="mailto:{{ $profile->email }}">Kirim email</a>@endif
                        @if ($profile->cvUrl())<a class="btn" href="{{ $profile->cvUrl() }}" download>Unduh CV</a>@endif
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
    <div class="wrap footer-row">
        <a class="brand" href="#top" style="margin-right:0">
            <span class="brand-mark" aria-hidden="true">{{ $mark }}</span>
            <span class="brand-name">{{ $name }}</span>
        </a>
        <nav class="footer-links" aria-label="Navigasi bawah">
            @foreach ($nav as $id => $label)<a href="#{{ $id }}">{{ $label }}</a>@endforeach
        </nav>
        <p>&copy; {{ now()->year }} {{ $name }}</p>
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
