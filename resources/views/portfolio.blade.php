@php
    $name = $profile->singleLineName();
    $initials = $profile->logo_text ?: collect(explode(' ', $name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
    $hasWork = $experiences->isNotEmpty() || $organizations->isNotEmpty();
    $sections = array_filter([
        'tentang' => $profile->description ? 'Tentang' : null,
        'pengalaman' => $hasWork ? 'Pengalaman' : null,
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
    <meta name="description" content="{{ $profile->subtitle }}">
    <meta name="color-scheme" content="light dark">
    <script>
        // Terapkan tema tersimpan sebelum render agar tidak berkedip.
        try {
            var t = localStorage.getItem('theme');
            if (t === 'light' || t === 'dark') document.documentElement.dataset.theme = t;
        } catch (e) {}
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600&family=Schibsted+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Layout: resume dua kolom — kolom identitas sticky di kiri, isi bergulir di kanan; satu kolom di layar sempit. */
        :root {
            --bg: #F3F5F4;
            --surface: #FFFFFF;
            --ink: #111719;
            --muted: #586468;
            --line: #DCE2E0;
            --accent: #0A7563;
            --accent-ink: #FFFFFF;
            --accent-soft: #DDEFEB;
            --ok: #1C8A4B;

            --font-display: "Schibsted Grotesk", "Helvetica Neue", Arial, sans-serif;
            --font-body: "IBM Plex Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
            --font-mono: "IBM Plex Mono", ui-monospace, "SFMono-Regular", Menlo, Consolas, monospace;

            --step--1: 0.8125rem;
            --step-0: 1rem;
            --step-1: 1.1875rem;
            --step-2: 1.5rem;
            --step-3: clamp(1.75rem, 1.4rem + 1.2vw, 2.25rem);
            --step-4: clamp(2.25rem, 1.7rem + 2.4vw, 3.25rem);

            --radius: 14px;
            color-scheme: light;
        }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg: #0D1113;
                --surface: #151B1E;
                --ink: #E6ECEA;
                --muted: #95A3A2;
                --line: #263135;
                --accent: #3FC6AA;
                --accent-ink: #052019;
                --accent-soft: #123129;
                --ok: #4ADE80;
                color-scheme: dark;
            }
        }
        :root[data-theme="dark"] {
            --bg: #0D1113;
            --surface: #151B1E;
            --ink: #E6ECEA;
            --muted: #95A3A2;
            --line: #263135;
            --accent: #3FC6AA;
            --accent-ink: #052019;
            --accent-soft: #123129;
            --ok: #4ADE80;
            color-scheme: dark;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        @media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } * { transition: none !important; } }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: var(--font-body);
            font-size: var(--step-0);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        img { max-width: 100%; display: block; }
        a { color: inherit; }
        :focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; border-radius: 4px; }
        h1, h2, h3 { font-family: var(--font-display); line-height: 1.15; margin: 0; text-wrap: balance; }
        p { margin: 0; }
        .mono { font-family: var(--font-mono); font-variant-numeric: tabular-nums; }

        .page {
            max-width: 1180px;
            margin: 0 auto;
            padding-left: max(20px, env(safe-area-inset-left, 0px));
            padding-right: max(20px, env(safe-area-inset-right, 0px));
            display: grid;
            grid-template-columns: minmax(280px, 340px) minmax(0, 1fr);
            gap: clamp(32px, 5vw, 80px);
        }

        /* ---------- Kolom identitas ---------- */
        .identity {
            position: sticky;
            top: 0;
            align-self: start;
            max-height: 100vh;
            overflow-y: auto;
            padding-block: 48px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            scrollbar-width: none;
        }
        .identity::-webkit-scrollbar { display: none; }
        .identity > * { flex-shrink: 0; }
        .identity-top { display: flex; flex-direction: column; gap: 20px; }
        .identity-name { display: flex; flex-direction: column; gap: 10px; min-width: 0; }
        .portrait {
            width: 100%;
            max-width: 220px;
            aspect-ratio: 4 / 5;
            border-radius: var(--radius);
            object-fit: cover;
            background: var(--accent-soft);
        }
        .monogram {
            width: 96px;
            aspect-ratio: 1;
            border-radius: var(--radius);
            background: var(--accent);
            color: var(--accent-ink);
            display: grid;
            place-items: center;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: 0.02em;
        }
        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: var(--step--1);
            font-weight: 500;
            color: var(--muted);
        }
        .status::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--ok);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--ok) 18%, transparent);
        }
        .identity h1 { font-size: var(--step-4); font-weight: 800; letter-spacing: -0.02em; }
        .role { color: var(--muted); font-size: var(--step-1); line-height: 1.45; }

        .specs { display: grid; grid-template-columns: 1fr 1fr; gap: 1px; margin: 0; background: var(--line); border: 1px solid var(--line); border-radius: 10px; overflow: hidden; }
        .specs div { background: var(--bg); padding: 10px 12px; display: flex; flex-direction: column; gap: 2px; }
        .specs dt { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); }
        .specs dd { margin: 0; font-family: var(--font-mono); font-weight: 500; font-size: var(--step-1); font-variant-numeric: tabular-nums; }

        .actions { display: flex; flex-wrap: wrap; gap: 10px; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--ink);
            transition: border-color .15s, background-color .15s, transform .15s;
        }
        .btn:hover { border-color: var(--accent); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: var(--accent-ink); }
        .btn-primary:hover { background: color-mix(in srgb, var(--accent) 88%, var(--ink)); }
        .btn svg { width: 16px; height: 16px; flex: none; }

        .toc { display: flex; flex-direction: column; gap: 2px; border-top: 1px solid var(--line); padding-top: 16px; }
        .toc a {
            text-decoration: none;
            color: var(--muted);
            font-size: 0.9375rem;
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color .15s;
        }
        .toc a::before { content: ""; width: 14px; height: 1px; background: currentColor; transition: width .15s; }
        .toc a:hover, .toc a.is-active { color: var(--ink); }
        .toc a.is-active::before { width: 28px; background: var(--accent); }

        .theme-toggle {
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: 1px solid var(--line);
            color: var(--muted);
            border-radius: 999px;
            padding: 6px 12px;
            font: inherit;
            font-size: var(--step--1);
            cursor: pointer;
        }
        .theme-toggle:hover { color: var(--ink); border-color: var(--muted); }
        .theme-toggle svg { width: 14px; height: 14px; }

        /* ---------- Isi ---------- */
        main { padding-block: 48px 32px; display: flex; flex-direction: column; min-width: 0; }
        .section { padding-block: 40px; border-top: 1px solid var(--line); display: flex; flex-direction: column; gap: 28px; scroll-margin-top: 16px; }
        .section:first-child { border-top: 0; padding-top: 0; }
        .section-head { display: flex; flex-direction: column; gap: 6px; }
        .section-head h2 { font-size: var(--step-3); font-weight: 700; letter-spacing: -0.01em; }
        .section-head p { color: var(--muted); max-width: 60ch; }
        .lead { font-size: var(--step-2); line-height: 1.5; max-width: 34ch; font-family: var(--font-display); font-weight: 600; letter-spacing: -0.01em; }
        .lead-body { color: var(--muted); max-width: 62ch; font-size: var(--step-1); }

        /* Timeline pengalaman: periode di kolom kiri */
        .timeline { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; }
        .entry { display: grid; grid-template-columns: 170px minmax(0, 1fr); gap: 8px 32px; padding-block: 22px; border-top: 1px dashed var(--line); }
        .entry:first-child { border-top: 0; padding-top: 0; }
        .entry-when { font-family: var(--font-mono); font-size: var(--step--1); color: var(--muted); padding-top: 4px; font-variant-numeric: tabular-nums; }
        .entry-body { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
        .entry h3 { font-size: var(--step-1); font-weight: 700; }
        .entry-org { color: var(--accent); font-weight: 600; }
        .entry-org span { color: var(--muted); font-weight: 400; }
        .entry-desc { color: var(--muted); max-width: 65ch; }
        .subhead { font-family: var(--font-display); font-size: var(--step-1); font-weight: 700; margin-top: 12px; }

        /* Proyek: satu-satunya elemen berbentuk kartu */
        .projects { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr)); gap: 20px; }
        .project { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; display: flex; flex-direction: column; transition: border-color .15s, transform .15s; }
        .project:hover { border-color: color-mix(in srgb, var(--accent) 45%, var(--line)); }
        .project-media { aspect-ratio: 16 / 10; width: 100%; object-fit: cover; background: var(--accent-soft); border-bottom: 1px solid var(--line); }
        .project-placeholder {
            aspect-ratio: 16 / 10;
            display: grid;
            place-items: center;
            background:
                linear-gradient(var(--line) 1px, transparent 1px) 0 0 / 100% 24px,
                var(--accent-soft);
            border-bottom: 1px solid var(--line);
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 2.5rem;
            color: var(--accent);
        }
        .project-body { padding: 20px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
        .project h3 { font-size: var(--step-1); font-weight: 700; }
        .project-meta { font-family: var(--font-mono); font-size: var(--step--1); color: var(--muted); }
        .project-summary { color: var(--muted); }
        .tags { display: flex; flex-wrap: wrap; gap: 6px; list-style: none; margin: 0; padding: 0; }
        .tags li { font-family: var(--font-mono); font-size: 0.75rem; padding: 3px 9px; border-radius: 999px; background: var(--accent-soft); color: var(--ink); }
        .project-links { display: flex; gap: 16px; margin-top: auto; padding-top: 6px; }
        .project-links a { font-weight: 600; font-size: 0.9375rem; color: var(--accent); text-decoration: none; }
        .project-links a:hover { text-decoration: underline; text-underline-offset: 3px; }

        /* Keahlian: level 1–5 dari persentase */
        .skills { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr)); column-gap: 40px; }
        .skill { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: center; gap: 4px 16px; padding-block: 14px; border-bottom: 1px solid var(--line); }
        .skill-name { font-weight: 600; }
        .skill-level { grid-column: 1; font-size: var(--step--1); color: var(--muted); }
        .meter { grid-column: 2; grid-row: 1 / span 2; display: flex; gap: 4px; }
        .meter i { width: 10px; height: 18px; border-radius: 3px; background: var(--line); }
        .meter i.on { background: var(--accent); }

        /* Pendidikan & sertifikasi */
        .edu { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 24px; align-items: start; }
        .edu h3 { font-size: var(--step-2); font-weight: 700; }
        .edu-degree { color: var(--accent); font-weight: 600; margin-top: 4px; }
        .facts { margin: 14px 0 0; display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 4px 16px; color: var(--muted); font-size: 0.9375rem; }
        .facts dt { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.08em; padding-top: 3px; }
        .facts dd { margin: 0; color: var(--ink); }
        .gpa { text-align: right; }
        .gpa-value { font-family: var(--font-mono); font-weight: 500; font-size: var(--step-4); line-height: 1; font-variant-numeric: tabular-nums; letter-spacing: -0.03em; }
        .gpa-value span { font-size: var(--step-1); color: var(--muted); letter-spacing: 0; }
        .gpa-label { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); margin-top: 6px; }
        .certs { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; }
        .cert { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 4px 16px; padding-block: 14px; border-bottom: 1px solid var(--line); align-items: baseline; }
        .cert:first-child { border-top: 1px solid var(--line); }
        .cert-name { font-weight: 600; }
        .cert-name a { text-decoration: none; }
        .cert-name a:hover { color: var(--accent); }
        .cert-issuer { grid-column: 1; font-size: var(--step--1); color: var(--muted); }
        .cert-score { grid-column: 2; grid-row: 1; font-family: var(--font-mono); font-variant-numeric: tabular-nums; color: var(--muted); font-size: 0.9375rem; }
        .cert-score strong { color: var(--ink); font-weight: 500; }

        /* Kontak */
        .contact { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: clamp(24px, 4vw, 40px); display: flex; flex-direction: column; gap: 24px; }
        .contact h2 { font-size: var(--step-3); }
        .contact-list { margin: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr)); gap: 16px; }
        .contact-list div { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
        .contact-list dt { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); }
        .contact-list dd { margin: 0; overflow-wrap: anywhere; }
        .contact-list a { text-decoration: none; font-weight: 500; }
        .contact-list a:hover { color: var(--accent); }

        footer { padding-block: 24px 48px; color: var(--muted); font-size: var(--step--1); display: flex; flex-wrap: wrap; justify-content: space-between; gap: 8px 24px; border-top: 1px solid var(--line); margin-top: 24px; }

        @media (max-width: 900px) {
            .page { grid-template-columns: minmax(0, 1fr); gap: 0; }
            .identity { position: static; max-height: none; overflow: visible; padding-block: 32px 8px; }
            .identity-top { display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 20px; align-items: end; }
            .portrait { width: 120px; }
            .toc { display: none; }
            main { padding-top: 16px; }
            .section:first-child { border-top: 1px solid var(--line); padding-top: 40px; }
        }
        @media (max-width: 560px) {
            .entry { grid-template-columns: minmax(0, 1fr); }
            .entry-when { padding-top: 0; }
            .edu { grid-template-columns: minmax(0, 1fr); }
            .gpa { text-align: left; }
            .identity-top { grid-template-columns: minmax(0, 1fr); }
        }
    </style>
</head>
<body>
<div class="page">
    <aside class="identity" aria-label="Profil">
        <div class="identity-top">
            @if ($profile->photoUrl())
                <img class="portrait" src="{{ $profile->photoUrl() }}" alt="Foto {{ $name }}" width="220" height="275">
            @else
                <div class="monogram" aria-hidden="true">{{ $initials }}</div>
            @endif
            <div class="identity-name">
                @if ($profile->badge_text)
                    <span class="status">{{ trim($profile->badge_text, "✦ \t") }}</span>
                @endif
                <h1>{!! nl2br(e($profile->name), false) !!}</h1>
            </div>
        </div>

        @if ($profile->subtitle)
            <p class="role">{{ $profile->subtitle }}</p>
        @endif

        @if ($stats->isNotEmpty())
            <dl class="specs">
                @foreach ($stats as $stat)
                    <div>
                        <dt>{{ $stat->label }}</dt>
                        <dd>{{ $stat->number }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        <div class="actions">
            @if ($profile->cvUrl())
                <a class="btn btn-primary" href="{{ $profile->cvUrl() }}" download>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 20h14"/></svg>
                    Unduh CV
                </a>
            @endif
            <a class="btn {{ $profile->cvUrl() ? '' : 'btn-primary' }}" href="#kontak">Hubungi saya</a>
            @if ($profile->linkedin_url)
                <a class="btn" href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn</a>
            @endif
        </div>

        <nav class="toc" aria-label="Navigasi bagian">
            @foreach ($sections as $id => $label)
                <a href="#{{ $id }}" data-section="{{ $id }}">{{ $label }}</a>
            @endforeach
        </nav>

        <button type="button" class="theme-toggle" id="theme-toggle" aria-live="polite">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 0 0 18z" fill="currentColor"/></svg>
            <span>Tema: Otomatis</span>
        </button>
    </aside>

    <main>
        @if ($profile->description)
            <section class="section" id="tentang">
                <div class="section-head"><h2>Tentang</h2></div>
                <p class="lead-body">{{ $profile->description }}</p>
            </section>
        @endif

        @if ($hasWork)
            <section class="section" id="pengalaman">
                <div class="section-head">
                    <h2>Pengalaman kerja</h2>
                    @if ($profile->experience_subtitle)<p>{{ $profile->experience_subtitle }}</p>@endif
                </div>
                @if ($experiences->isNotEmpty())
                    <ol class="timeline">
                        @foreach ($experiences as $experience)
                            <li class="entry">
                                <div class="entry-when">{{ $experience->period }}</div>
                                <div class="entry-body">
                                    <h3>{{ $experience->title }}</h3>
                                    <div class="entry-org">{{ $experience->company }}@if ($experience->location)<span> · {{ $experience->location }}</span>@endif</div>
                                    @if ($experience->description)<p class="entry-desc">{{ $experience->description }}</p>@endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif

                @if ($organizations->isNotEmpty())
                    <h3 class="subhead">Organisasi</h3>
                    <ol class="timeline">
                        @foreach ($organizations as $organization)
                            <li class="entry">
                                <div class="entry-when">{{ $organization->period }}</div>
                                <div class="entry-body">
                                    <h3>{{ $organization->title }}</h3>
                                    <div class="entry-org">{{ $organization->organization }}</div>
                                    @if ($organization->description)<p class="entry-desc">{{ $organization->description }}</p>@endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </section>
        @endif

        @if ($projects->isNotEmpty())
            <section class="section" id="proyek">
                <div class="section-head">
                    <h2>Proyek</h2>
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
                                <h3>{{ $project->title }}</h3>
                                @if ($project->role || $project->period)
                                    <div class="project-meta">{{ collect([$project->role, $project->period])->filter()->implode(' · ') }}</div>
                                @endif
                                @if ($project->summary)<p class="project-summary">{{ $project->summary }}</p>@endif
                                @if ($project->description)<p class="project-summary">{{ $project->description }}</p>@endif
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
            </section>
        @endif

        @if ($skills->isNotEmpty())
            <section class="section" id="keahlian">
                <div class="section-head">
                    <h2>Keahlian</h2>
                    @if ($profile->skills_subtitle)<p>{{ $profile->skills_subtitle }}</p>@endif
                </div>
                <ul class="skills">
                    @foreach ($skills as $skill)
                        @php $level = max(1, min(5, (int) round($skill->percentage / 20))); @endphp
                        <li class="skill">
                            <span class="skill-name">{{ $skill->name }}</span>
                            <span class="meter" role="img" aria-label="Level {{ $level }} dari 5">
                                @for ($i = 1; $i <= 5; $i++)<i class="{{ $i <= $level ? 'on' : '' }}"></i>@endfor
                            </span>
                            @if ($skill->level_label)<span class="skill-level">{{ $skill->level_label }}</span>@endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($educations->isNotEmpty() || $certifications->isNotEmpty())
            <section class="section" id="pendidikan">
                <div class="section-head">
                    <h2>Pendidikan &amp; sertifikasi</h2>
                    @if ($profile->education_subtitle)<p>{{ $profile->education_subtitle }}</p>@endif
                </div>
                @foreach ($educations as $education)
                    <div class="edu">
                        <div>
                            <h3>{{ $education->institution }}</h3>
                            @if ($education->degree)<div class="edu-degree">{{ $education->degree }}</div>@endif
                            <dl class="facts">
                                @if ($education->period)<dt>Periode</dt><dd class="mono">{{ $education->period }}</dd>@endif
                                @if ($education->faculty)<dt>Fakultas</dt><dd>{{ $education->faculty }}</dd>@endif
                                @if ($education->location)<dt>Lokasi</dt><dd>{{ $education->location }}</dd>@endif
                            </dl>
                        </div>
                        @if ($education->gpa !== null)
                            <div class="gpa">
                                <div class="gpa-value">{{ number_format((float) $education->gpa, 2) }}<span> / {{ number_format((float) $education->gpa_scale, 2) }}</span></div>
                                <div class="gpa-label">IPK</div>
                            </div>
                        @endif
                    </div>
                @endforeach

                @if ($certifications->isNotEmpty())
                    <h3 class="subhead">Sertifikasi</h3>
                    @if ($profile->certification_subtitle)<p style="color:var(--muted);margin-top:-16px">{{ $profile->certification_subtitle }}</p>@endif
                    <ul class="certs">
                        @foreach ($certifications as $certification)
                            <li class="cert">
                                <span class="cert-name">
                                    @if ($certification->credential_url)
                                        <a href="{{ $certification->credential_url }}" target="_blank" rel="noopener noreferrer">{{ $certification->name }} ↗</a>
                                    @else
                                        {{ $certification->name }}
                                    @endif
                                </span>
                                @if ($certification->score)<span class="cert-score">Skor <strong>{{ $certification->score }}</strong></span>@endif
                                @if ($certification->issuer)<span class="cert-issuer">{{ $certification->issuer }}</span>@endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endif

        <section class="section" id="kontak">
            <div class="contact">
                <div class="section-head">
                    <h2>{{ $profile->contact_title ?: 'Kontak' }}</h2>
                    @if ($profile->contact_subtitle)<p>{{ $profile->contact_subtitle }}</p>@endif
                </div>
                <div class="actions">
                    @if ($profile->email)<a class="btn btn-primary" href="mailto:{{ $profile->email }}">Kirim email</a>@endif
                    @if ($profile->whatsapp_number)<a class="btn" href="https://wa.me/{{ $profile->whatsapp_number }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>@endif
                    @if ($profile->cvUrl())<a class="btn" href="{{ $profile->cvUrl() }}" download>Unduh CV</a>@endif
                </div>
                <dl class="contact-list">
                    @if ($profile->email)<div><dt>Email</dt><dd><a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></dd></div>@endif
                    @if ($profile->phone)<div><dt>Telepon</dt><dd><a class="mono" href="tel:{{ $profile->phoneHref() }}">{{ $profile->phone }}</a></dd></div>@endif
                    @if ($profile->linkedin_url)<div><dt>LinkedIn</dt><dd><a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">Lihat profil ↗</a></dd></div>@endif
                    @if ($profile->address)<div><dt>Alamat</dt><dd>{{ $profile->address }}</dd></div>@endif
                </dl>
            </div>
        </section>

        <footer>
            <span>&copy; {{ now()->year }} {{ $name }}</span>
            @if ($profile->subtitle)<span>{{ $profile->subtitle }}</span>@endif
        </footer>
    </main>
</div>

<script>
    (function () {
        // Tombol tema: Otomatis -> Terang -> Gelap
        var root = document.documentElement;
        var btn = document.getElementById('theme-toggle');
        var label = btn.querySelector('span');
        var names = { system: 'Otomatis', light: 'Terang', dark: 'Gelap' };
        var order = ['system', 'light', 'dark'];
        function current() { return root.dataset.theme || 'system'; }
        function render() { label.textContent = 'Tema: ' + names[current()]; }
        btn.addEventListener('click', function () {
            var next = order[(order.indexOf(current()) + 1) % order.length];
            if (next === 'system') { delete root.dataset.theme; } else { root.dataset.theme = next; }
            try { next === 'system' ? localStorage.removeItem('theme') : localStorage.setItem('theme', next); } catch (e) {}
            render();
        });
        render();

        // Tandai bagian yang sedang dibaca di navigasi
        var links = document.querySelectorAll('.toc a');
        if (!('IntersectionObserver' in window) || !links.length) return;
        var map = {};
        links.forEach(function (a) { map[a.dataset.section] = a; });
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                links.forEach(function (a) { a.classList.remove('is-active'); });
                if (map[e.target.id]) map[e.target.id].classList.add('is-active');
            });
        }, { rootMargin: '-35% 0px -60% 0px' });
        document.querySelectorAll('main .section[id]').forEach(function (s) { io.observe(s); });
    })();
</script>
</body>
</html>
