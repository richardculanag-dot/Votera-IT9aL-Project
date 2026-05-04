<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Votera — department-scoped campus voting with admin controls, student ballots, results, and audit-friendly workflows. Built with Laravel.">
    <title>Votera — Campus voting, managed with clarity</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/votera.css') }}">
    <style>
        .lp { min-height: 100vh; display: flex; flex-direction: column; background: var(--surface); color: var(--ink); }
        .lp a:hover { text-decoration: none; }

        .lp-header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(250, 250, 248, 0.88);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-light);
        }
        .lp-header__inner {
            max-width: 1120px; margin: 0 auto; padding: 14px 24px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
        }
        .lp-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; color: var(--ink); }
        .lp-brand:hover { opacity: 0.88; }
        .lp-brand__icon {
            width: 42px; height: 42px; border-radius: 11px; background: var(--ink); color: var(--white);
            display: flex; align-items: center; justify-content: center;
        }
        .lp-brand__icon svg { width: 22px; height: 22px; }
        .lp-brand__text {
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            font-weight: 800; font-size: 1.32rem; letter-spacing: -0.04em;
        }
        .lp-nav { display: flex; align-items: center; gap: 6px 14px; flex-wrap: wrap; }
        .lp-nav__link {
            font-size: 0.8rem; font-weight: 600; color: var(--ash); padding: 6px 4px;
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
        }
        .lp-nav__link:hover { color: var(--ink); }
        .lp-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 18px; border-radius: 10px; font-size: 0.82rem; font-weight: 600;
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            border: 1px solid var(--border); text-decoration: none; transition: var(--transition);
        }
        .lp-btn--solid { background: var(--ink); color: var(--white); border-color: var(--ink); }
        .lp-btn--solid:hover { opacity: 0.92; }
        .lp-btn--lg { padding: 12px 22px; font-size: 0.9rem; border-radius: 11px; }
        .lp-btn--outline { background: var(--white); color: var(--ink); border-color: var(--border); }
        .lp-btn--outline:hover { box-shadow: var(--shadow-sm); border-color: #cac8c4; }

        .lp-main { flex: 1; }

        .lp-wrap { max-width: 1120px; margin: 0 auto; padding-left: 24px; padding-right: 24px; }

        .lp-hero {
            padding: 48px 0 72px;
            background: linear-gradient(180deg, var(--surface) 0%, var(--surface-2) 55%, #e9e7e3 100%);
            border-bottom: 1px solid var(--border-light);
        }
        .lp-hero__grid {
            display: grid; grid-template-columns: 1.05fr 0.95fr; gap: 44px; align-items: start;
        }
        @media (max-width: 900px) { .lp-hero__grid { grid-template-columns: 1fr; gap: 32px; } }

        .lp-kicker {
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.14em;
            color: var(--ash); margin-bottom: 14px;
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
        }
        .lp-title {
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            font-weight: 700; font-size: clamp(2.1rem, 4vw, 3.05rem);
            line-height: 1.1; letter-spacing: -0.038em; color: var(--ink); margin-bottom: 18px;
        }
        .lp-lead {
            font-size: 1.06rem; color: var(--ash); max-width: 38ch; line-height: 1.65; margin-bottom: 26px;
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif; font-weight: 400;
        }
        .lp-hero__cta { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; align-items: center; }
        .lp-hero__meta {
            display: flex; flex-wrap: wrap; gap: 10px 20px; font-size: 0.78rem; color: var(--ash-light);
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
        }
        .lp-hero__meta span { display: inline-flex; align-items: center; gap: 6px; }
        .lp-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--success); }

        .lp-card {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 24px; box-shadow: var(--shadow-sm);
        }
        .lp-card h2 {
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            font-size: 0.95rem; font-weight: 700; margin-bottom: 16px; color: var(--ink);
        }
        .lp-checklist { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 12px; }
        .lp-checklist li {
            display: flex; gap: 12px; align-items: flex-start; font-size: 0.84rem; color: var(--ash);
            line-height: 1.5; font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
        }
        .lp-checklist svg { flex-shrink: 0; width: 18px; height: 18px; color: var(--success); margin-top: 2px; }
        .lp-checklist strong { color: var(--ink); font-weight: 600; }

        .lp-section { padding: 64px 0; border-bottom: 1px solid var(--border-light); }
        .lp-section:last-of-type { border-bottom: none; }
        .lp-section--muted { background: var(--white); }
        .lp-section--dark {
            background: linear-gradient(145deg, #1c1917 0%, #292524 100%);
            color: #fafaf9; border-bottom: none;
        }
        .lp-section--dark .lp-section__title { color: #fafaf9; }
        .lp-section--dark .lp-section__sub { color: #a8a29e; }
        .lp-section__head { text-align: center; max-width: 640px; margin: 0 auto 44px; }
        .lp-section__title {
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            font-weight: 700; font-size: clamp(1.45rem, 2.5vw, 1.85rem);
            letter-spacing: -0.03em; margin-bottom: 10px; color: var(--ink);
        }
        .lp-section__sub {
            font-size: 0.95rem; color: var(--ash); line-height: 1.6; margin: 0;
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif; font-weight: 400;
        }

        .lp-feature-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px;
        }
        @media (max-width: 900px) { .lp-feature-grid { grid-template-columns: 1fr; } }

        .lp-feat {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 22px 20px; transition: box-shadow var(--transition), border-color var(--transition);
        }
        .lp-feat:hover { box-shadow: var(--shadow); border-color: #dcd9d4; }
        .lp-feat__icon {
            width: 44px; height: 44px; border-radius: 11px; background: var(--surface);
            display: flex; align-items: center; justify-content: center; margin-bottom: 14px; color: var(--ink);
        }
        .lp-feat__icon svg { width: 22px; height: 22px; }
        .lp-feat h3 {
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            font-size: 0.95rem; font-weight: 700; margin-bottom: 8px; color: var(--ink);
        }
        .lp-feat p { font-size: 0.82rem; color: var(--ash); margin: 0; line-height: 1.58; }

        .lp-steps {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; counter-reset: step;
        }
        @media (max-width: 900px) { .lp-steps { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 520px) { .lp-steps { grid-template-columns: 1fr; } }

        .lp-step {
            position: relative; padding: 20px; border-radius: var(--radius); border: 1px solid var(--border);
            background: var(--surface);
        }
        .lp-step::before {
            counter-increment: step; content: counter(step);
            display: flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; border-radius: 8px; background: var(--ink); color: var(--white);
            font-size: 0.75rem; font-weight: 700; margin-bottom: 12px;
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
        }
        .lp-step h3 { font-size: 0.88rem; font-weight: 700; margin-bottom: 6px; color: var(--ink); font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif; }
        .lp-step p { font-size: 0.78rem; color: var(--ash); margin: 0; line-height: 1.55; }

        .lp-split {
            display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;
        }
        @media (max-width: 768px) { .lp-split { grid-template-columns: 1fr; } }

        .lp-prose h3 {
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            font-size: 0.92rem; font-weight: 700; margin: 0 0 12px; color: var(--ink);
        }
        .lp-prose ul { margin: 0; padding-left: 1.15rem; color: var(--ash); font-size: 0.84rem; line-height: 1.65; }
        .lp-prose li { margin-bottom: 6px; }

        .lp-tech {
            display: grid; grid-template-columns: 1fr 1.1fr; gap: 28px; align-items: start;
        }
        @media (max-width: 800px) { .lp-tech { grid-template-columns: 1fr; } }

        .lp-laravel {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 14px; border-radius: 999px; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            background: rgba(255, 45, 32, 0.1); color: #c61c14; border: 1px solid rgba(255, 45, 32, 0.22);
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            margin-bottom: 16px;
        }
        .lp-tech p { font-size: 0.88rem; color: #d6d3d1; line-height: 1.65; margin: 0 0 14px; }
        .lp-tech p:last-child { margin-bottom: 0; }
        .lp-tech__aside {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            border-radius: var(--radius-lg); padding: 22px;
        }
        .lp-tech__aside h3 { font-size: 0.8rem; font-weight: 700; margin-bottom: 14px; color: #fafaf9; font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif; }
        .lp-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .lp-tag {
            font-size: 0.72rem; font-weight: 600; padding: 6px 11px; border-radius: 8px;
            background: rgba(255,255,255,0.08); color: #e7e5e4; border: 1px solid rgba(255,255,255,0.12);
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
        }

        .lp-req-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        @media (max-width: 640px) { .lp-req-grid { grid-template-columns: 1fr; } }
        .lp-req {
            border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; background: var(--surface);
        }
        .lp-req h3 { font-size: 0.85rem; font-weight: 700; margin-bottom: 10px; color: var(--ink); font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif; }
        .lp-req p { font-size: 0.8rem; color: var(--ash); margin: 0; line-height: 1.55; }

        .lp-cta {
            text-align: center; padding: 56px 24px;
            background: linear-gradient(135deg, var(--surface) 0%, #ebe8e3 100%);
            border-top: 1px solid var(--border-light);
        }
        .lp-cta h2 {
            font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif;
            font-weight: 700; font-size: clamp(1.35rem, 2.2vw, 1.65rem); margin-bottom: 10px; letter-spacing: -0.02em;
        }
        .lp-cta p { color: var(--ash); font-size: 0.92rem; margin: 0 0 22px; max-width: 480px; margin-left: auto; margin-right: auto; }

        .lp-footer {
            background: var(--white); border-top: 1px solid var(--border);
            padding: 40px 24px 28px; font-size: 0.78rem; color: var(--ash);
        }
        .lp-footer__grid {
            max-width: 1120px; margin: 0 auto;
            display: grid; grid-template-columns: 1.2fr repeat(2, 1fr); gap: 28px;
        }
        @media (max-width: 720px) { .lp-footer__grid { grid-template-columns: 1fr; } }
        .lp-footer__brand { font-weight: 800; color: var(--ink); font-family: "Plus Jakarta Sans", var(--font-sans), system-ui, sans-serif; font-size: 1rem; margin-bottom: 8px; }
        .lp-footer__links { display: flex; flex-direction: column; gap: 8px; }
        .lp-footer__links a { color: var(--ash); font-weight: 500; }
        .lp-footer__links a:hover { color: var(--ink); }
        .lp-footer__bottom {
            max-width: 1120px; margin: 28px auto 0; padding-top: 20px; border-top: 1px solid var(--border-light);
            text-align: center; color: var(--ash-light); font-size: 0.72rem;
        }
    </style>
</head>
<body>
<div class="lp">
    <header class="lp-header">
        <div class="lp-header__inner">
            <a href="{{ route('landing') }}" class="lp-brand" aria-label="Votera home">
                <span class="lp-brand__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0121 17.5c0 2.485-3.582 4.5-8 4.5S5 19.985 5 17.5c0-1.127.38-2.18 1.04-3.078L12 14z"/></svg>
                </span>
                <span class="lp-brand__text">Votera</span>
            </a>
            <nav class="lp-nav" aria-label="Page">
                <a class="lp-nav__link" href="#features">Features</a>
                <a class="lp-nav__link" href="#how-it-works">How it works</a>
                <a class="lp-nav__link" href="#roles">Who uses it</a>
                <a class="lp-nav__link" href="#tech">Tech stack</a>
                <a class="lp-nav__link" href="#access">Access</a>
                <a href="{{ route('login') }}" class="lp-btn lp-btn--solid">Sign in</a>
            </nav>
        </div>
    </header>

    <main class="lp-main">
        <section class="lp-hero">
            <div class="lp-wrap lp-hero__grid">
                <div>
                    <p class="lp-kicker">Voting management system</p>
                    <h1 class="lp-title">Fair elections, transparent results</h1>
                    <p class="lp-lead">Votera is a web application for running department-scoped student elections: configure ballots, protect data integrity with roles and logs, and give voters a focused experience from sign-in to results.</p>
                    <div class="lp-hero__cta">
                        <a href="{{ route('login') }}" class="lp-btn lp-btn--solid lp-btn--lg">Sign in to the portal</a>
                        <a href="#features" class="lp-btn lp-btn--outline lp-btn--lg">Explore features</a>
                    </div>
                    <div class="lp-hero__meta" role="list">
                        <span role="listitem"><span class="lp-dot" aria-hidden="true"></span> Roles: admin, staff, student</span>
                        <span role="listitem">Soft-delete trash &amp; scheduled cleanup</span>
                        <span role="listitem">Audit-friendly activity trail</span>
                    </div>
                </div>
                <aside class="lp-card" aria-label="Highlights">
                    <h2>Built for academic workflows</h2>
                    <ul class="lp-checklist">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Election lifecycle</strong> — pending, ongoing, and ended states with controlled transitions.</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Ballot structure</strong> — positions and candidates per election; read-only after voting ends.</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Results &amp; exports</strong> — aggregated views and PDF export for reporting.</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Governance</strong> — election trash bin (restore, clear, auto-purge), optional locks, audit log.</span>
                        </li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="lp-section lp-section--muted" id="features" aria-labelledby="features-heading">
            <div class="lp-wrap">
                <header class="lp-section__head">
                    <h2 class="lp-section__title" id="features-heading">Features that cover the full voting story</h2>
                    <p class="lp-section__sub">From setup to tally, Votera keeps administrators, staff, and students on separate rails with shared, consistent data.</p>
                </header>
                <div class="lp-feature-grid">
                    <article class="lp-feat">
                        <div class="lp-feat__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3>Elections &amp; departments</h3>
                        <p>Create elections tied to departments, set timelines, and move through statuses without losing history.</p>
                    </article>
                    <article class="lp-feat">
                        <div class="lp-feat__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        </div>
                        <h3>Positions &amp; candidates</h3>
                        <p>Model each race with ordered positions and candidate slates. Editing respects election state so ended ballots stay intact.</p>
                    </article>
                    <article class="lp-feat">
                        <div class="lp-feat__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <h3>Student ballot experience</h3>
                        <p>Students see eligible elections, cast votes per position, and get confirmation with history for accountability.</p>
                    </article>
                    <article class="lp-feat">
                        <div class="lp-feat__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3>Results &amp; PDF export</h3>
                        <p>Review turnout-friendly summaries and export PDFs for faculty meetings, accreditation folders, or archives.</p>
                    </article>
                    <article class="lp-feat">
                        <div class="lp-feat__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3>Audit log</h3>
                        <p>Important actions are recorded with context so administrators can trace what changed and when.</p>
                    </article>
                    <article class="lp-feat">
                        <div class="lp-feat__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <h3>Election trash</h3>
                        <p>Soft-deleted elections land in an admin trash bin: restore, delete permanently, clear all, or rely on scheduled auto-purge after retention.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="lp-section" id="how-it-works" aria-labelledby="how-heading">
            <div class="lp-wrap">
                <header class="lp-section__head">
                    <h2 class="lp-section__title" id="how-heading">How it works</h2>
                    <p class="lp-section__sub">A straightforward path from configuration to participation—without paper ballots or ad-hoc spreadsheets.</p>
                </header>
                <div class="lp-steps">
                    <div class="lp-step">
                        <h3>Configure</h3>
                        <p>An administrator defines the election, department scope, dates, positions, and candidates before opening the vote.</p>
                    </div>
                    <div class="lp-step">
                        <h3>Open voting</h3>
                        <p>When rules are satisfied, the election moves to ongoing. Students authenticate and submit choices per position.</p>
                    </div>
                    <div class="lp-step">
                        <h3>Close &amp; lock</h3>
                        <p>Ending the election freezes ballot structure and details, preserving a trustworthy record for review.</p>
                    </div>
                    <div class="lp-step">
                        <h3>Report</h3>
                        <p>Staff and admins consult in-app results and exports; the audit log supports post-event questions.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="lp-section lp-section--muted" id="roles" aria-labelledby="roles-heading">
            <div class="lp-wrap">
                <header class="lp-section__head">
                    <h2 class="lp-section__title" id="roles-heading">Who uses what</h2>
                    <p class="lp-section__sub">Separation of duties keeps day-to-day operations smooth while protecting sensitive configuration.</p>
                </header>
                <div class="lp-split">
                    <div class="lp-card lp-prose">
                        <h3>Administrator</h3>
                        <ul>
                            <li>Full election CRUD, trash, and restore workflows</li>
                            <li>Staff and student directory views; vote visibility</li>
                            <li>Results export, audit log, and system-wide oversight</li>
                        </ul>
                    </div>
                    <div class="lp-card lp-prose">
                        <h3>Staff &amp; students</h3>
                        <ul>
                            <li><strong>Staff:</strong> help manage positions and candidates where allowed, view elections and results</li>
                            <li><strong>Students:</strong> see eligible ballots, vote once per election rules, and review personal vote history</li>
                            <li>Everyone signs in through the same secure portal with role-based routing after login</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="lp-section lp-section--dark" id="tech" aria-labelledby="tech-heading">
            <div class="lp-wrap lp-tech">
                <div>
                    <span class="lp-laravel">Built with Laravel</span>
                    <h2 class="lp-section__title" id="tech-heading" style="text-align:left;margin-bottom:14px;">About this application</h2>
                    <p>Votera is implemented as a <strong style="color:#fafaf9;">Laravel</strong> PHP project: server-rendered <strong style="color:#fafaf9;">Blade</strong> views, <strong style="color:#fafaf9;">Eloquent ORM</strong> for relational data, middleware-based <strong style="color:#fafaf9;">authentication and roles</strong>, and the scheduler for maintenance such as purging expired items from the election trash.</p>
                    <p>That stack gives you mature routing, validation, CSRF protection, migrations, and a clear path to deploy on typical LAMP/LEMP or managed PHP hosting—well suited for academic demos, capstone submissions, or a department pilot.</p>
                </div>
                <aside class="lp-tech__aside" aria-label="Technologies used">
                    <h3>Pieces you will find in the codebase</h3>
                    <div class="lp-tags">
                        <span class="lp-tag">Laravel framework</span>
                        <span class="lp-tag">Blade templates</span>
                        <span class="lp-tag">Eloquent models</span>
                        <span class="lp-tag">Migrations</span>
                        <span class="lp-tag">Middleware (roles)</span>
                        <span class="lp-tag">Artisan commands</span>
                        <span class="lp-tag">Task scheduling</span>
                        <span class="lp-tag">Soft deletes</span>
                        <span class="lp-tag">Session auth</span>
                    </div>
                </aside>
            </div>
        </section>

        <section class="lp-section" id="access" aria-labelledby="access-heading">
            <div class="lp-wrap">
                <header class="lp-section__head">
                    <h2 class="lp-section__title" id="access-heading">Access &amp; necessities</h2>
                    <p class="lp-section__sub">This deployment does not expose a public registration flow—accounts are expected to be created and distributed by your institution.</p>
                </header>
                <div class="lp-req-grid">
                    <div class="lp-req">
                        <h3>Sign-in only</h3>
                        <p>Use <strong style="color:var(--ink);">Sign in</strong> with credentials your registrar or IT office provides. There is no self-service account creation on this site.</p>
                    </div>
                    <div class="lp-req">
                        <h3>Browser &amp; device</h3>
                        <p>A current version of Chrome, Edge, Firefox, or Safari on desktop or mobile is recommended. JavaScript should be enabled for forms and session security.</p>
                    </div>
                    <div class="lp-req">
                        <h3>HTTPS in production</h3>
                        <p>For real campus use, serve the app over HTTPS so passwords and cookies stay protected in transit.</p>
                    </div>
                    <div class="lp-req">
                        <h3>Backups &amp; policy</h3>
                        <p>Database backups and institutional data-retention rules remain the responsibility of whoever operates the server.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="lp-cta" aria-labelledby="cta-heading">
            <h2 id="cta-heading">Ready to open the portal?</h2>
            <p>Continue to the sign-in screen to reach the dashboard for your role (admin, staff, or student).</p>
            <a href="{{ route('login') }}" class="lp-btn lp-btn--solid lp-btn--lg">Sign in</a>
        </section>
    </main>

    <footer class="lp-footer">
        <div class="lp-footer__grid">
            <div>
                <div class="lp-footer__brand">Votera</div>
                <p style="margin:0;line-height:1.55;max-width:280px;">Campus voting management: elections, ballots, results, and audit support in one Laravel application.</p>
            </div>
            <div>
                <div class="lp-footer__brand" style="font-size:0.75rem;letter-spacing:0.08em;text-transform:uppercase;color:var(--ash);font-weight:700;">On this page</div>
                <div class="lp-footer__links" style="margin-top:10px;">
                    <a href="#features">Features</a>
                    <a href="#how-it-works">How it works</a>
                    <a href="#roles">Who uses it</a>
                    <a href="#tech">Tech stack</a>
                    <a href="#access">Access</a>
                </div>
            </div>
            <div>
                <div class="lp-footer__brand" style="font-size:0.75rem;letter-spacing:0.08em;text-transform:uppercase;color:var(--ash);font-weight:700;">Account</div>
                <div class="lp-footer__links" style="margin-top:10px;">
                    <a href="{{ route('login') }}">Sign in</a>
                    <a href="{{ route('landing') }}">Home</a>
                </div>
            </div>
        </div>
        <div class="lp-footer__bottom">
            Academic voting toolkit — public landing page. Authenticated users are routed to the correct panel after sign-in.
        </div>
    </footer>
</div>
</body>
</html>
