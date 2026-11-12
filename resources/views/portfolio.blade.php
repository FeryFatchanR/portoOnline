<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ str_replace("\n", ' ', $profile->name) }} - Portfolio</title>
    <meta name="description" content="{{ $profile->subtitle }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Sora:wght@300;400;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
        :root {
            --color-bg-primary: #0B0E1A;
            --color-bg-secondary: #141825;
            --color-accent-primary: #00FFA3;
            --color-accent-secondary: #7B61FF;
            --color-text-primary: #FFFFFF;
            --color-text-secondary: #A8B2D1;
            --color-surface: #1A1F35;
            --color-border: rgba(0, 255, 163, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Sora', sans-serif;
            background: var(--color-bg-primary);
            color: var(--color-text-primary);
            overflow-x: hidden;
            line-height: 1.7;
        }

        /* Animated Grid Background */
        .grid-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 255, 163, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 163, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
        }

        .gradient-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.3;
            z-index: 0;
            pointer-events: none;
        }

        .orb-1 {
            width: 600px;
            height: 600px;
            background: var(--color-accent-primary);
            top: -200px;
            right: -100px;
            animation: float 25s infinite ease-in-out;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: var(--color-accent-secondary);
            bottom: -150px;
            left: -100px;
            animation: float 20s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(100px, -80px) rotate(120deg); }
            66% { transform: translate(-50px, 100px) rotate(240deg); }
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 80px;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 24px;
            }
        }

        /* Navigation */
        nav {
            padding: 30px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--color-border);
            animation: slideDown 0.6s ease-out;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 28px;
            font-weight: 900;
            color: var(--color-accent-primary);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .nav-links {
            display: flex;
            gap: 50px;
            list-style: none;
        }

        .nav-links a {
            color: var(--color-text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--color-accent-primary);
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--color-accent-primary);
        }

        .nav-links a:hover::before {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            padding: 120px 0;
            animation: fadeIn 1s ease-out;
        }

        .hero-badge {
            display: inline-block;
            padding: 12px 24px;
            background: rgba(0, 255, 163, 0.1);
            border: 1px solid var(--color-accent-primary);
            border-radius: 50px;
            color: var(--color-accent-primary);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 30px;
        }

        .hero h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 80px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 24px;
            background: linear-gradient(135deg, var(--color-text-primary) 0%, var(--color-accent-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: -2px;
        }

        .hero-subtitle {
            font-size: 28px;
            color: var(--color-text-secondary);
            margin-bottom: 40px;
            font-weight: 300;
            max-width: 700px;
        }

        .hero-description {
            font-size: 18px;
            color: var(--color-text-secondary);
            margin-bottom: 50px;
            max-width: 600px;
            line-height: 1.8;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-top: 80px;
        }

        .stat-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--color-accent-primary), var(--color-accent-secondary));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: var(--color-accent-primary);
            box-shadow: 0 20px 60px rgba(0, 255, 163, 0.15);
        }

        .stat-number {
            font-family: 'Orbitron', sans-serif;
            font-size: 48px;
            font-weight: 900;
            color: var(--color-accent-primary);
            display: block;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* CTA Buttons */
        .cta-buttons {
            display: flex;
            gap: 20px;
            margin-top: 40px;
        }

        .btn {
            padding: 18px 45px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: var(--color-accent-primary);
            color: var(--color-bg-primary);
            border-color: var(--color-accent-primary);
        }

        .btn-primary:hover {
            background: transparent;
            color: var(--color-accent-primary);
            box-shadow: 0 10px 40px rgba(0, 255, 163, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--color-text-primary);
            border-color: var(--color-text-secondary);
        }

        .btn-secondary:hover {
            border-color: var(--color-accent-primary);
            color: var(--color-accent-primary);
        }

        /* Section Styles */
        section {
            padding: 100px 0;
            animation: fadeIn 0.8s ease-out;
        }

        .section-header {
            margin-bottom: 70px;
        }

        .section-tag {
            display: inline-block;
            font-size: 13px;
            color: var(--color-accent-primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 16px;
            font-weight: 700;
        }

        .section-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 56px;
            font-weight: 900;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .section-subtitle {
            font-size: 18px;
            color: var(--color-text-secondary);
            max-width: 600px;
        }

        /* Skills Section */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .skill-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            padding: 35px;
            border-radius: 16px;
            transition: all 0.3s ease;
            position: relative;
        }

        .skill-card::after {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            background: linear-gradient(135deg, var(--color-accent-primary), var(--color-accent-secondary));
            border-radius: 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .skill-card:hover::after {
            opacity: 1;
        }

        .skill-card:hover {
            transform: translateY(-5px);
            background: var(--color-bg-secondary);
        }

        .skill-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .skill-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-text-primary);
        }

        .skill-badge {
            padding: 6px 14px;
            background: rgba(0, 255, 163, 0.1);
            border-radius: 20px;
            font-size: 11px;
            color: var(--color-accent-primary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .skill-bar {
            width: 100%;
            height: 6px;
            background: var(--color-bg-secondary);
            border-radius: 10px;
            overflow: hidden;
        }

        .skill-progress {
            height: 100%;
            background: linear-gradient(90deg, var(--color-accent-primary), var(--color-accent-secondary));
            border-radius: 10px;
            animation: progressLoad 1.5s ease-out forwards;
        }

        @keyframes progressLoad {
            from { width: 0; }
        }

        /* Experience Section */
        .timeline {
            position: relative;
            padding-left: 50px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--color-accent-primary), var(--color-accent-secondary));
        }

        .timeline-item {
            position: relative;
            margin-bottom: 50px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -56px;
            top: 8px;
            width: 14px;
            height: 14px;
            background: var(--color-accent-primary);
            border-radius: 50%;
            box-shadow: 0 0 0 4px var(--color-bg-primary), 0 0 0 6px var(--color-accent-primary);
        }

        .experience-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            padding: 40px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .experience-card:hover {
            transform: translateX(10px);
            border-color: var(--color-accent-primary);
        }

        .experience-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
            gap: 20px;
        }

        .experience-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 8px;
        }

        .experience-company {
            font-size: 16px;
            color: var(--color-accent-primary);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .experience-location {
            font-size: 14px;
            color: var(--color-text-secondary);
        }

        .experience-period {
            font-size: 13px;
            color: var(--color-accent-primary);
            padding: 8px 16px;
            background: rgba(0, 255, 163, 0.1);
            border-radius: 6px;
            white-space: nowrap;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .experience-description {
            color: var(--color-text-secondary);
            line-height: 1.8;
            font-size: 15px;
        }

        /* Education Card */
        .education-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            padding: 50px;
            border-radius: 16px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .education-info h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .education-degree {
            font-size: 18px;
            color: var(--color-accent-primary);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .education-meta {
            display: flex;
            flex-direction: column;
            gap: 12px;
            color: var(--color-text-secondary);
            font-size: 15px;
        }

        .education-meta div {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .gpa-container {
            text-align: center;
            padding: 40px;
            background: var(--color-bg-secondary);
            border: 2px solid var(--color-accent-primary);
            border-radius: 16px;
        }

        .gpa-number {
            font-family: 'Orbitron', sans-serif;
            font-size: 72px;
            font-weight: 900;
            color: var(--color-accent-primary);
            display: block;
            line-height: 1;
        }

        .gpa-label {
            color: var(--color-text-secondary);
            font-size: 13px;
            margin-top: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Certificates */
        .certificates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .certificate-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            padding: 40px;
            border-radius: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .certificate-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--color-accent-primary), var(--color-accent-secondary));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .certificate-card:hover::before {
            transform: scaleY(1);
        }

        .certificate-card:hover {
            transform: translateY(-5px);
            border-color: var(--color-accent-primary);
        }

        .certificate-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--color-accent-primary), var(--color-accent-secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 24px;
        }

        .certificate-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--color-text-primary);
        }

        .certificate-score {
            font-family: 'Orbitron', sans-serif;
            font-size: 36px;
            font-weight: 900;
            color: var(--color-accent-primary);
        }

        /* Contact Section */
        .contact-section {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            padding: 80px;
            border-radius: 20px;
            text-align: center;
        }

        .contact-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 48px;
            font-weight: 900;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .contact-subtitle {
            font-size: 18px;
            color: var(--color-text-secondary);
            margin-bottom: 50px;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .contact-item {
            background: var(--color-bg-secondary);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid var(--color-border);
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            border-color: var(--color-accent-primary);
            transform: translateY(-5px);
        }

        .contact-icon {
            font-size: 32px;
            margin-bottom: 16px;
        }

        .contact-item a {
            color: var(--color-text-primary);
            text-decoration: none;
            font-weight: 600;
            display: block;
            transition: color 0.3s ease;
        }

        .contact-item a:hover {
            color: var(--color-accent-primary);
        }

        .contact-item .contact-label {
            font-size: 12px;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 50px 0;
            color: var(--color-text-secondary);
            border-top: 1px solid var(--color-border);
            margin-top: 100px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .education-card {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 60px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 42px;
            }

            .hero-subtitle {
                font-size: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 36px;
            }

            .contact-section {
                padding: 50px 30px;
            }

            .contact-title {
                font-size: 32px;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .timeline {
                padding-left: 30px;
            }

            .timeline-item::before {
                left: -36px;
            }

            .experience-header {
                flex-direction: column;
            }

            .experience-period {
                align-self: flex-start;
            }
        }
    </style>
    @endverbatim
</head>
<body>
    <div class="grid-background"></div>
    <div class="gradient-orb orb-1"></div>
    <div class="gradient-orb orb-2"></div>

    <div class="container">
        <!-- Navigation -->
        <nav>
            <div class="logo">{{ $profile->logo_text }}</div>
            <ul class="nav-links">
                <li><a href="#about">About</a></li>
                @if ($skills->isNotEmpty())<li><a href="#skills">Skills</a></li>@endif
                @if ($experiences->isNotEmpty() || $organizations->isNotEmpty())<li><a href="#experience">Experience</a></li>@endif
                @if ($educations->isNotEmpty())<li><a href="#education">Education</a></li>@endif
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>

        <!-- Hero Section -->
        <section class="hero" id="about">
            @if ($profile->badge_text)
                <div class="hero-badge">{{ $profile->badge_text }}</div>
            @endif
            <h1>{!! nl2br(e($profile->name), false) !!}</h1>
            @if ($profile->subtitle)
                <div class="hero-subtitle">{{ $profile->subtitle }}</div>
            @endif
            @if ($profile->description)
                <p class="hero-description">{{ $profile->description }}</p>
            @endif
            <div class="cta-buttons">
                <a href="#contact" class="btn btn-primary">Get In Touch</a>
                @if ($profile->linkedin_url)
                    <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">View LinkedIn</a>
                @endif
            </div>
            @if ($stats->isNotEmpty())
                <div class="stats-grid">
                    @foreach ($stats as $stat)
                        <div class="stat-card">
                            <span class="stat-number">{{ $stat->number }}</span>
                            <span class="stat-label">{{ $stat->label }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        @if ($skills->isNotEmpty())
        <!-- Skills Section -->
        <section id="skills">
            <div class="section-header">
                <div class="section-tag">// Expertise</div>
                <h2 class="section-title">Technical Skills</h2>
                @if ($profile->skills_subtitle)<p class="section-subtitle">{{ $profile->skills_subtitle }}</p>@endif
            </div>
            <div class="skills-grid">
                @foreach ($skills as $skill)
                    <div class="skill-card">
                        <div class="skill-header">
                            <div class="skill-name">{{ $skill->name }}</div>
                            @if ($skill->level_label)<div class="skill-badge">{{ $skill->level_label }}</div>@endif
                        </div>
                        <div class="skill-bar">
                            <div class="skill-progress" style="width: {{ max(0, min(100, (int) $skill->percentage)) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if ($experiences->isNotEmpty() || $organizations->isNotEmpty())
        <!-- Experience Section -->
        <section id="experience">
            @if ($experiences->isNotEmpty())
                <div class="section-header">
                    <div class="section-tag">// Career Journey</div>
                    <h2 class="section-title">Work Experience</h2>
                    @if ($profile->experience_subtitle)<p class="section-subtitle">{{ $profile->experience_subtitle }}</p>@endif
                </div>
                <div class="timeline">
                    @foreach ($experiences as $experience)
                        <div class="timeline-item">
                            <div class="experience-card">
                                <div class="experience-header">
                                    <div>
                                        <div class="experience-title">{{ $experience->title }}</div>
                                        <div class="experience-company">{{ $experience->company }}</div>
                                        @if ($experience->location)<div class="experience-location">📍 {{ $experience->location }}</div>@endif
                                    </div>
                                    @if ($experience->period)<div class="experience-period">{{ $experience->period }}</div>@endif
                                </div>
                                @if ($experience->description)<p class="experience-description">{{ $experience->description }}</p>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($organizations->isNotEmpty())
                <div class="section-header" @if ($experiences->isNotEmpty()) style="margin-top: 80px;" @endif>
                    <div class="section-tag">// Leadership</div>
                    <h2 class="section-title">Organization</h2>
                    @if ($profile->organization_subtitle)<p class="section-subtitle">{{ $profile->organization_subtitle }}</p>@endif
                </div>
                <div class="timeline">
                    @foreach ($organizations as $organization)
                        <div class="timeline-item">
                            <div class="experience-card">
                                <div class="experience-header">
                                    <div>
                                        <div class="experience-title">{{ $organization->title }}</div>
                                        <div class="experience-company">{{ $organization->organization }}</div>
                                    </div>
                                    @if ($organization->period)<div class="experience-period">{{ $organization->period }}</div>@endif
                                </div>
                                @if ($organization->description)<p class="experience-description">{{ $organization->description }}</p>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
        @endif

        @if ($educations->isNotEmpty())
        <!-- Education Section -->
        <section id="education">
            <div class="section-header">
                <div class="section-tag">// Academic Background</div>
                <h2 class="section-title">Education</h2>
                @if ($profile->education_subtitle)<p class="section-subtitle">{{ $profile->education_subtitle }}</p>@endif
            </div>
            @foreach ($educations as $education)
                <div class="education-card" @if (! $loop->first) style="margin-top: 24px;" @endif>
                    <div class="education-info">
                        <h3>{{ $education->institution }}</h3>
                        @if ($education->degree)<div class="education-degree">{{ $education->degree }}</div>@endif
                        <div class="education-meta">
                            @if ($education->period)<div>📅 <strong>Periode:</strong> {{ $education->period }}</div>@endif
                            @if ($education->faculty)<div>🏛️ <strong>Fakultas:</strong> {{ $education->faculty }}</div>@endif
                            @if ($education->location)<div>📍 <strong>Lokasi:</strong> {{ $education->location }}</div>@endif
                        </div>
                    </div>
                    @if ($education->gpa !== null)
                        <div class="gpa-container">
                            <span class="gpa-number">{{ number_format((float) $education->gpa, 2) }}</span>
                            <div class="gpa-label">GPA / {{ number_format((float) $education->gpa_scale, 2) }}</div>
                        </div>
                    @endif
                </div>
            @endforeach
        </section>
        @endif

        @if ($certifications->isNotEmpty())
        <!-- Certificates Section -->
        <section id="certifications">
            <div class="section-header">
                <div class="section-tag">// Credentials</div>
                <h2 class="section-title">Certifications</h2>
                @if ($profile->certification_subtitle)<p class="section-subtitle">{{ $profile->certification_subtitle }}</p>@endif
            </div>
            <div class="certificates-grid">
                @foreach ($certifications as $certification)
                    <div class="certificate-card">
                        @if ($certification->icon)<div class="certificate-icon">{{ $certification->icon }}</div>@endif
                        <div class="certificate-name">
                            @if ($certification->credential_url)
                                <a href="{{ $certification->credential_url }}" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">{{ $certification->name }}</a>
                            @else
                                {{ $certification->name }}
                            @endif
                        </div>
                        @if ($certification->issuer)<div class="experience-location">{{ $certification->issuer }}</div>@endif
                        @if ($certification->score)<div class="certificate-score">{{ $certification->score }}</div>@endif
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Contact Section -->
        <section id="contact">
            <div class="contact-section">
                @if ($profile->contact_title)<h2 class="contact-title">{{ $profile->contact_title }}</h2>@endif
                @if ($profile->contact_subtitle)<p class="contact-subtitle">{{ $profile->contact_subtitle }}</p>@endif
                <div class="cta-buttons" style="justify-content: center;">
                    @if ($profile->email)<a href="mailto:{{ $profile->email }}" class="btn btn-primary">Send Email</a>@endif
                    @if ($profile->whatsapp_number)<a href="https://wa.me/{{ $profile->whatsapp_number }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">WhatsApp</a>@endif
                </div>
                <div class="contact-info">
                    @if ($profile->email)
                        <div class="contact-item">
                            <div class="contact-icon">📧</div>
                            <div class="contact-label">Email</div>
                            <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
                        </div>
                    @endif
                    @if ($profile->phone)
                        <div class="contact-item">
                            <div class="contact-icon">📱</div>
                            <div class="contact-label">Phone</div>
                            <a href="tel:{{ $profile->phoneHref() }}">{{ $profile->phone }}</a>
                        </div>
                    @endif
                    @if ($profile->linkedin_url)
                        <div class="contact-item">
                            <div class="contact-icon">💼</div>
                            <div class="contact-label">LinkedIn</div>
                            <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">Connect on LinkedIn</a>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer>
            <p>&copy; {{ now()->year }} {{ str_replace("\n", ' ', $profile->name) }}. All Rights Reserved.</p>
            @if ($profile->address)
                <p style="margin-top: 12px; font-size: 14px;">📍 {{ $profile->address }}</p>
            @endif
        </footer>
    </div>

    @verbatim
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });

        // Parallax effect for gradient orbs
        document.addEventListener('mousemove', (e) => {
            const orb1 = document.querySelector('.orb-1');
            const orb2 = document.querySelector('.orb-2');
            
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            orb1.style.transform = `translate(${x * 50}px, ${y * 50}px)`;
            orb2.style.transform = `translate(${-x * 30}px, ${-y * 30}px)`;
        });
    </script>
    @endverbatim
</body>
</html>
