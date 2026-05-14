<!DOCTYPE html>
  <?php include 'header1.php'; ?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Coding4Schools – Coding Curriculum for Schools</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
     
  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --accent: #f97316;
      --bg: #f4f5fb;
      --text: #111827;
      --muted: #6b7280;
      --card-bg: #ffffff;
      --border: #e5e7eb;
      --radius-lg: 14px;
      --radius-sm: 8px;
      --shadow-md: 0 12px 30px rgba(15, 23, 42, 0.12);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
        sans-serif;
      color: var(--text);
      background: #ffffff;
      line-height: 1.6;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    img {
      max-width: 100%;
      display: block;
    }

    /* Layout helpers */
    .container {
      width: 100%;
      max-width: 1120px;
      margin: 0 auto;
      padding: 0 1.25rem;
    }

    .section {
      padding: 4.5rem 0;
    }

    .section-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
    }

    .section-subtitle {
      font-size: 1rem;
      color: var(--muted);
      max-width: 640px;
    }

    /* Header / Nav */
    header {
      position: sticky;
      top: 0;
      z-index: 40;
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(229, 231, 235, 0.8);
    }

    .nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 64px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 700;
      font-size: 1.1rem;
    }

    .brand-mark {
      width: 32px;
      height: 32px;
      border-radius: 999px;
      background: radial-gradient(circle at 30% 30%, #bfdbfe, #2563eb);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 0.8rem;
      font-weight: 800;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 1.75rem;
      font-size: 0.95rem;
    }

    .nav-links a {
      color: var(--muted);
      padding-bottom: 2px;
      border-bottom: 2px solid transparent;
      transition: color 0.15s ease, border-color 0.15s ease;
    }

    .nav-links a:hover {
      color: var(--primary);
      border-color: var(--primary);
    }

    .nav-cta {
      padding: 0.45rem 0.95rem;
      border-radius: 999px;
      background: var(--primary);
      color: #fff;
      font-size: 0.9rem;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      transition: background 0.15s ease, transform 0.1s ease;
      box-shadow: 0 8px 18px rgba(37, 99, 235, 0.25);
    }

    .nav-cta:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
    }

    .nav-cta span.icon {
      font-size: 1.1rem;
    }

    /* Mobile nav */
    .nav-toggle {
      display: none;
      background: none;
      border: none;
      font-size: 1.4rem;
      cursor: pointer;
    }

    .nav-links-mobile {
      display: none;
      flex-direction: column;
      gap: 1rem;
      padding: 1rem 0 1.5rem;
      border-top: 1px solid var(--border);
      font-size: 0.95rem;
    }

    .nav-links-mobile a {
      color: var(--muted);
    }

    .nav-links-mobile a:hover {
      color: var(--primary);
    }

    /* Hero */
    .hero {
      /* background: radial-gradient(circle at top left, #eff6ff, #ffffff); */
      background-color: lightblue;
      padding: 3.5rem 0 4.5rem;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: minmax(0, 6fr) minmax(0, 5fr);
      gap: 3rem;
      align-items: center;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.2rem 0.75rem;
      border-radius: 999px;
      font-size: 0.8rem;
      background: #eff6ff;
      color: #1d4ed8;
      margin-bottom: 0.9rem;
    }

    .badge-dot {
      width: 8px;
      height: 8px;
      border-radius: 999px;
      background: #22c55e;
    }

    .hero-title {
      font-size: clamp(2.2rem, 4vw, 2.9rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      margin-bottom: 1rem;
    }

    .hero-highlight {
      color: var(--primary);
    }

    .hero-text {
      color: var(--muted);
      max-width: 540px;
      font-size: 0.98rem;
      margin-bottom: 1.6rem;
    }

    .hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      margin-bottom: 1.6rem;
    }

    .btn-primary {
      padding: 0.75rem 1.35rem;
      border-radius: 999px;
      background: var(--primary);
      color: #fff;
      border: none;
      cursor: pointer;
      font-size: 0.95rem;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      box-shadow: var(--shadow-md);
      transition: background 0.15s ease, transform 0.1s ease,
        box-shadow 0.15s ease;
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
      box-shadow: 0 16px 35px rgba(37, 99, 235, 0.3);
    }

    .btn-ghost {
      padding: 0.7rem 1.1rem;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--muted);
      font-size: 0.9rem;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      transition: background 0.15s ease, border-color 0.15s ease,
        color 0.15s ease;
    }

    .btn-ghost:hover {
      background: #f9fafb;
      border-color: var(--primary);
      color: var(--primary);
    }

    .hero-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 1.25rem;
      font-size: 0.83rem;
      color: var(--muted);
    }

    .hero-meta span {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
    }

    .hero-meta-dot {
      width: 6px;
      height: 6px;
      border-radius: 999px;
      background: #10b981;
    }

    .hero-card {
      background: #ffffff;
      border-radius: 22px;
      padding: 1.75rem;
      box-shadow: var(--shadow-md);
      border: 1px solid rgba(209, 213, 219, 0.7);
    }

    .hero-card-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .hero-pill-row {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
      margin-bottom: 1.1rem;
    }

    .pill {
      padding: 0.25rem 0.7rem;
      border-radius: 999px;
      background: #eff6ff;
      color: #1d4ed8;
      font-size: 0.8rem;
    }

    .hero-stats {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 0.75rem;
      font-size: 0.85rem;
      margin-bottom: 1.2rem;
    }

    .hero-stat {
      background: #f9fafb;
      border-radius: 12px;
      padding: 0.55rem 0.7rem;
    }

    .hero-stat-label {
      color: var(--muted);
      font-size: 0.75rem;
      margin-bottom: 0.1rem;
    }

    .hero-stat-value {
      font-weight: 600;
      font-size: 0.95rem;
    }

    .hero-note {
      font-size: 0.8rem;
      color: var(--muted);
    }

    /* Feature cards */
    .features {
      /* background: var(--bg); */
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1.4rem;
      margin-top: 2.5rem;
    }

    .feature-card {
      background: var(--card-bg);
      border-radius: var(--radius-lg);
      padding: 1.35rem 1.3rem;
      box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
      border: 1px solid rgba(209, 213, 219, 0.7);
    }

    .feature-icon {
      width: 34px;
      height: 34px;
      border-radius: 999px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #eef2ff;
      color: #4f46e5;
      font-size: 1rem;
      margin-bottom: 0.75rem;
    }

    .feature-title {
      font-size: 0.98rem;
      font-weight: 600;
      margin-bottom: 0.35rem;
    }

    .feature-text {
      font-size: 0.86rem;
      color: var(--muted);
    }

    /* Pathway */
    .pathway-header {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .pathway-tag {
      font-size: 0.8rem;
      color: var(--primary);
      text-transform: uppercase;
      letter-spacing: 0.12em;
      margin-bottom: 0.35rem;
    }

    .pathway-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1.4rem;
    }

    .pathway-card {
      background: #ffffff;
      border-radius: var(--radius-lg);
      padding: 1.4rem 1.3rem;
      border: 1px solid var(--border);
      box-shadow: 0 12px 25px rgba(15, 23, 42, 0.05);
    }

    .pathway-grade {
      font-size: 0.8rem;
      color: var(--primary);
      font-weight: 600;
      margin-bottom: 0.35rem;
    }

    .pathway-title {
      font-size: 0.98rem;
      font-weight: 600;
      margin-bottom: 0.4rem;
    }

    .pathway-list {
      list-style: disc;
      padding-left: 1.05rem;
      margin-top: 0.4rem;
      font-size: 0.85rem;
      color: var(--muted);
    }

    .pathway-list li + li {
      margin-top: 0.2rem;
    }

    /* Grade table */
    .grade-table-wrap {
      margin-top: 2.3rem;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      overflow: hidden;
      background: #ffffff;
      box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .grade-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
    }

    .grade-table thead {
      background: #eff6ff;
    }

    .grade-table th,
    .grade-table td {
      padding: 0.75rem 0.9rem;
      text-align: left;
      border-bottom: 1px solid #e5e7eb;
      vertical-align: top;
    }

    .grade-table th {
      font-weight: 600;
      font-size: 0.85rem;
      color: #374151;
    }

    .grade-table tr:last-child td {
      border-bottom: none;
    }

    .grade-col-grade {
      width: 80px;
      white-space: nowrap;
    }

    .grade-col-course {
      width: 260px;
    }

    /* Platform explanation */
    .platform-grid {
      display: grid;
      grid-template-columns: minmax(0, 6fr) minmax(0, 5fr);
      gap: 2.5rem;
      align-items: center;
      margin-top: 2.5rem;
    }

    .platform-box {
      background: #ffffff;
      border-radius: var(--radius-lg);
      padding: 1.4rem 1.3rem;
      border: 1px solid var(--border);
      margin-bottom: 1rem;
      box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    }

    .platform-box h3 {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .platform-box p {
      font-size: 0.9rem;
      color: var(--muted);
    }

    .platform-stats {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.75rem;
      font-size: 0.85rem;
    }

    .platform-stat {
      background: #f9fafb;
      border-radius: 12px;
      padding: 0.7rem 0.8rem;
    }

    .platform-stat span {
      display: block;
    }

    .platform-stat-label {
      color: var(--muted);
      font-size: 0.75rem;
      margin-bottom: 0.15rem;
    }

    .platform-stat-value {
      font-weight: 600;
    }

    /* CTA */
    .cta {
      background: radial-gradient(circle at top, #2563eb, #1e293b);
      color: #ffffff;
    }

    .cta-inner {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem;
    }

    .cta-title {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 0.4rem;
    }

    .cta-text {
      font-size: 0.95rem;
      color: rgba(241, 245, 249, 0.9);
      max-width: 440px;
    }

    .cta-actions .btn-primary {
      background: #f97316;
      box-shadow: 0 14px 35px rgba(249, 115, 22, 0.4);
    }

    .cta-actions .btn-primary:hover {
      background: #ea580c;
    }

    /* Footer */
   

    .footer-grid {
      display: grid;
      grid-template-columns: minmax(0, 2.5fr) repeat(2, minmax(0, 2fr));
      gap: 1.8rem;
      margin-bottom: 1.8rem;
    }

    .footer-title {
      font-size: 0.9rem;
      font-weight: 600;
      color: #e5e7eb;
      margin-bottom: 0.45rem;
    }

    .footer-links {
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
    }

    .footer-links a {
      color: #9ca3af;
    }

    .footer-links a:hover {
      color: #e5e7eb;
    }

    .footer-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      border-top: 1px solid rgba(31, 41, 55, 0.9);
      padding-top: 1.1rem;
    }

    .footer-small-links {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 960px) {
      .hero-grid,
      .platform-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .feature-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .pathway-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .hero-card {
        margin-top: 1.5rem;
      }
    }

    @media (max-width: 768px) {
      .nav-links {
        display: none;
      }

      .nav-toggle {
        display: block;
      }

      .hero {
        padding-top: 2.25rem;
      }

      .feature-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .pathway-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .footer-grid {
        grid-template-columns: minmax(0, 1fr);
      }
    }

    @media (max-width: 640px) {
      .grade-table-wrap {
        border-radius: 0;
      }

      .grade-table thead {
        display: none;
      }

      .grade-table,
      .grade-table tbody,
      .grade-table tr,
      .grade-table td {
        display: block;
        width: 100%;
      }

      .grade-table tr {
        border-bottom: 1px solid #e5e7eb;
      }

      .grade-table td {
        border: none;
        padding: 0.5rem 0.95rem;
      }

      .grade-table td::before {
        content: attr(data-label);
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.1rem;
      }
    }
  </style>
</head>
<body>
  
  




  <main id="top">
    <!-- Hero -->
    <section class="hero" style="background-color: lightblue;">
      <div class="container hero-grid">
        <div>
          <div class="badge">
            
          </div>
          <h1 class="hero-title">
            A complete <span class="hero-highlight hero-title">coding curriculum</span> for
            every classroom.
          </h1>
          <p class="hero-text">
            Coding4Schools provides a structured K–12 pathway that builds
            real programming and problem-solving skills, while supporting
            teachers with ready-to-use lessons, assessments, and projects.
          </p>
          <div class="hero-actions">
            <button class="btn-primary" onclick="scrollToSection('contact')">
              Get a quote for your school
              <span>→</span>
            </button>
            <button class="btn-ghost" onclick="scrollToSection('platform')">
              Learn about the platform
            </button>
          </div>
          <div class="hero-meta">
            <span>
              <span class="hero-meta-dot"></span>
              Aligned with major international curricula
            </span>
            <span>Designed for primary, middle & high school</span>
          </div>
        </div>

        <aside class="hero-card" aria-label="Curriculum summary">
          <h2 class="hero-card-title">Your all-in-one coding curriculum</h2>
          <div class="hero-pill-row">
            <span class="pill">Lesson plans</span>
            <span class="pill">Projects</span>
            <span class="pill">Quizzes</span>
            <span class="pill">Teacher tools</span>
          </div>
          <div class="hero-stats">
            <div class="hero-stat">
              <div class="hero-stat-label">Teaching material</div>
              <div class="hero-stat-value">1,000+ hrs</div>
            </div>
            <div class="hero-stat">
              <div class="hero-stat-label">Grade coverage</div>
              <div class="hero-stat-value">G1–G12</div>
            </div>
            <div class="hero-stat">
              <div class="hero-stat-label">Delivery</div>
              <div class="hero-stat-value">Web-based</div>
            </div>
          </div>
          <p class="hero-note">
            Each lesson includes objectives, pacing, exercises, and answer keys,
            so both new and experienced teachers can run confident coding
            classes.
          </p>
        </aside>
      </div>
    </section>

    <!-- Features -->
    <section id="features" class="features section">
      <div class="container">
        <h2 class="section-title">Why schools choose Coding4Schools</h2>
        <p class="section-subtitle">
          A single platform that brings together curriculum, coding tools, and
          teacher support, so schools can offer meaningful computer science
          education with minimal setup.
        </p>

        <div class="feature-grid">
          <article class="feature-card">
            <div class="feature-icon">📚</div>
            <h3 class="feature-title">Structured curriculum</h3>
            <p class="feature-text">
              A coherent sequence of courses with detailed lesson plans,
              exercises, and projects that build skills year by year.
            </p>
          </article>

          <article class="feature-card">
            <div class="feature-icon">💻</div>
            <h3 class="feature-title">Built-in coding workspace</h3>
            <p class="feature-text">
              Students code directly in the browser, with no software
              installation required, enabling smooth use in labs or at home.
            </p>
          </article>

          <article class="feature-card">
            <div class="feature-icon">📝</div>
            <h3 class="feature-title">Paperless assessment</h3>
            <p class="feature-text">
              Quizzes, checkpoints, and challenges are delivered online with
              auto-grading where possible, reducing marking time.
            </p>
          </article>

          <article class="feature-card">
            <div class="feature-icon">👩‍🏫</div>
            <h3 class="feature-title">Teacher training & support</h3>
            <p class="feature-text">
              Onboarding sessions and ongoing support help teachers with any
              background feel comfortable delivering coding lessons.
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- Pathway -->
    <section id="pathway" class="section" style="background-color: lightblue;">
      <div class="container" >
        <div class="pathway-header">
          <div class="pathway-tag">K–12 Pathway</div>
          <h2 class="section-title">A clear route from first steps to advanced coding</h2>
          <p class="section-subtitle" style="margin: 0 auto;">
            From block-based programming in early grades to text-based coding,
            data, and AI in secondary levels, the pathway grows with your
            students.
          </p>
        </div>

        <div class="pathway-grid">
          <article class="pathway-card">
            <div class="pathway-grade">G1–G3</div>
            <h3 class="pathway-title">Lower Elementary</h3>
            <p class="feature-text">
              Gentle introduction to computational thinking without overwhelming
              syntax.
            </p>
            <ul class="pathway-list">
              <li>Coding puzzles and simple stories</li>
              <li>Basic sequences and events</li>
            </ul>
          </article>

          <article class="pathway-card">
            <div class="pathway-grade">G4–G6</div>
            <h3 class="pathway-title">Upper Elementary</h3>
            <p class="feature-text">
              Visual programming projects that solidify core computer science
              ideas.
            </p>
            <ul class="pathway-list">
              <li>Games and animations</li>
              <li>Loops, conditionals, and variables</li>
            </ul>
          </article>

          <article class="pathway-card">
            <div class="pathway-grade">G7–G9</div>
            <h3 class="pathway-title">Middle School</h3>
            <p class="feature-text">
              Transition from blocks to text-based coding with real, visible
              outcomes.
            </p>
            <ul class="pathway-list">
              <li>Web pages and interactive sketches</li>
              <li>Deeper problem-solving strategies</li>
            </ul>
          </article>

          <article class="pathway-card">
            <div class="pathway-grade">G10–G12</div>
            <h3 class="pathway-title">Secondary School</h3>
            <p class="feature-text">
              Full programming courses that prepare students for exams, study,
              or careers in computing.
            </p>
            <ul class="pathway-list">
              <li>Projects in Python, JavaScript & more</li>
              <li>Algorithms, data, and software design</li>
            </ul>
          </article>
        </div>

        <!-- Grade overview table -->
        <div id="grades" class="grade-table-wrap">
          <table class="grade-table">
            <thead>
              <tr>
                <th class="grade-col-grade">Grade</th>
                <th class="grade-col-course">Course</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="grade-col-grade" data-label="Grade">1</td>
                <td class="grade-col-course" data-label="Course">
                  Coding without computers
                </td>
                <td data-label="Description">
                  Early activities that introduce directions, simple algorithms,
                  and step-by-step thinking.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">2</td>
                <td data-label="Course">
                  Block coding – level I
                </td>
                <td data-label="Description">
                  First contact with visual blocks: building tiny stories and
                  puzzles using drag-and-drop code.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">3</td>
                <td data-label="Course">
                  Block coding – level II
                </td>
                <td data-label="Description">
                  Students design simple games while learning loops,
                  conditionals, and events.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">4</td>
                <td data-label="Course">
                  Game making with blocks – I
                </td>
                <td data-label="Description">
                  Larger projects show how characters, scoring, and interactions
                  come together in complete games.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">5</td>
                <td data-label="Course">
                  Game making with blocks – II
                </td>
                <td data-label="Description">
                  More advanced mechanics and project work deepen understanding
                  of core concepts.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">6</td>
                <td data-label="Course">
                  Advanced visual coding
                </td>
                <td data-label="Description">
                  Abstraction, functions, and modular thinking using familiar
                  visual tools.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">7</td>
                <td data-label="Course">
                  Web development I (HTML & CSS)
                </td>
                <td data-label="Description">
                  Students build real webpages while learning layout, structure,
                  and styling basics.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">8</td>
                <td data-label="Course">
                  Web development II (JavaScript)
                </td>
                <td data-label="Description">
                  Interactivity is added through JavaScript to create dynamic,
                  engaging web experiences.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">9</td>
                <td data-label="Course">
                  Introductory Java or Python
                </td>
                <td data-label="Description">
                  Text-based coding fundamentals through graphics, logic
                  puzzles, and small applications.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">10</td>
                <td data-label="Course">
                  Advanced programming concepts
                </td>
                <td data-label="Description">
                  Functions, structured data, and problem decomposition in
                  preparation for formal examinations.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">11</td>
                <td data-label="Course">
                  Python for problem solving – I
                </td>
                <td data-label="Description">
                  Real-world style challenges introduce algorithms, data
                  handling, and debugging.
                </td>
              </tr>
              <tr>
                <td data-label="Grade">12</td>
                <td data-label="Course">
                  Python for problem solving – II
                </td>
                <td data-label="Description">
                  Tackling more complex tasks and exploring key computer
                  science patterns and techniques.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Platform section -->
    <section id="platform" class="section">
      <div class="container">
        <h2 class="section-title">What is the Coding4Schools platform?</h2>
        <p class="section-subtitle">
          Coding4Schools brings curriculum, coding tools, and teacher resources
          into one browser-based environment, replacing scattered files and
          disconnected apps.
        </p>

        <div class="platform-grid">
          <div>
            <div class="platform-box">
              <h3>All your teaching tools in one place</h3>
              <p>
                Teachers access lesson plans, slides, starter code, answer
                keys, and assessments directly through the platform. Students
                log in to view instructions and code in an embedded editor, so
                the entire class stays in sync.
              </p>
            </div>
            <div class="platform-box">
              <h3>Designed to empower non-specialist teachers</h3>
              <p>
                The curriculum assumes minimal prior experience with computer
                science. Each lesson clearly states objectives, estimated time,
                and step-by-step guidance, making it practical for any motivated
                teacher to deliver high-quality coding lessons.
              </p>
            </div>
          </div>
          <div>
            <div class="platform-stats">
              <div class="platform-stat">
                <span class="platform-stat-label">Curriculum span</span>
                <span class="platform-stat-value">Primary → Secondary</span>
              </div>
              <div class="platform-stat">
                <span class="platform-stat-label">Modes</span>
                <span class="platform-stat-value">In-class & remote</span>
              </div>
              <div class="platform-stat">
                <span class="platform-stat-label">Setup</span>
                <span class="platform-stat-value">Runs in a browser</span>
              </div>
              <div class="platform-stat">
                <span class="platform-stat-label">Support</span>
                <span class="platform-stat-value">
                  Onboarding & year-round help
                </span>
              </div>
            </div>
            <div class="platform-box" style="margin-top: 1rem;">
              <h3>Platform overview</h3>
              <p>
                Schools can request a guided tour of the platform, including
                teacher and student views, sample courses, and reporting tools.
                No video player is required – demonstrations can be given live
                or through screenshots and sample accounts.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section id="contact" class="cta section">
      <div class="container cta-inner">
        <div>
          <h2 class="cta-title">Ready to explore Coding4Schools for your campus?</h2>
          <p class="cta-text">
            Share a few details about your school and we’ll prepare a proposal
            including recommended courses by grade, an implementation timeline,
            and licensing options.
          </p>
        </div>
        <div class="cta-actions">
          <button class="btn-primary">
            Pre-register and request a quote
          </button>
        </div>
      </div>
    </section>
  </main>


<?php 
include 'footer.php';
?>
  

  <script>
    // Simple scroll helper
    function scrollToSection(id) {
      const el = document.getElementById(id);
      if (el) {
        window.scrollTo({
          top: el.offsetTop - 70,
          behavior: "smooth",
        });
      }
    }

    // Mobile nav toggle
    const navToggle = document.querySelector(".nav-toggle");
    const mobileNav = document.getElementById("mobileNav");

    function toggleMobileNav() {
      if (!mobileNav) return;
      const isVisible = mobileNav.style.display === "flex";
      mobileNav.style.display = isVisible ? "none" : "flex";
    }

    if (navToggle && mobileNav) {
      navToggle.addEventListener("click", toggleMobileNav);
    }

    // Current year in footer
    document.getElementById("year").textContent =
      new Date().getFullYear();
  </script>
</body>
</html>