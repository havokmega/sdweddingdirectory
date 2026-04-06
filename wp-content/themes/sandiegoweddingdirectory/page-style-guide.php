<?php
/**
 *  SDWeddingDirectory - Style Guide
 *  Template Name: Style Guide
 */
get_header();
?>
<div class="main-content content wide-tb-90">
<div class="container">
<div class="row">
<section id="primary" class="content-area primary col-12">

<!-- ============================================================
     DESIGN TOKENS
     ============================================================ -->
<h1>Style Guide</h1>
<p>Living reference for every visual component in the SD Wedding Directory theme.</p>

<hr style="margin: 2rem 0;">

<h2>Design Tokens</h2>

<h3>Brand Palette</h3>
<p>The approved site palette. All new work should pull from these colors only.</p>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
    <?php
    $brand_colors = [
        '--sdwd-accent'      => ['#2BCFCE', 'Brand accent, links, identity'],
        '--sdwd-accent-dark' => ['#007f80', 'Darker accent, hover states'],
        '--sdwd-cta'         => ['#EC4D25', 'CTA buttons, urgent actions'],
        '--sdwd-dark'        => ['#1d2733', 'Dark backgrounds, footer'],
        '--sdwd-subtext'     => ['#2d2d2d', 'Headings, strong body text'],
        '--sdwd-body-text'   => ['#444444', 'Default body text'],
        '--sdwd-muted'       => ['#5f6d7b', 'Secondary text, icons'],
        '--sdwd-soft'        => ['#f3f5f7', 'Soft backgrounds, fills'],
        '--sdwd-accent-bg'   => ['#d5f5f5', 'Teal-tinted backgrounds'],
        '--sdwd-accent-bg-soft' => ['#f2fbfb', 'Subtle teal tint (Why SDWD section)'],
        '--sdwd-border'      => ['#dce4ea', 'Borders, dividers'],
    ];
    foreach ( $brand_colors as $var => $info ) :
    ?>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 70px; background: <?php echo $info[0]; ?>; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;"><?php echo $var; ?></code>
        <small style="color: #888; display: block;"><?php echo $info[0]; ?></small>
        <small style="color: #666; font-size: 11px;"><?php echo $info[1]; ?></small>
    </div>
    <?php endforeach; ?>
</div>

<h3>Considerations</h3>
<p>Color scale convention: <strong>500 = base (brand)</strong>, 600–700 = hover/active, 400 = secondary buttons / lighter accents, 100–200 = backgrounds, 800–900 = text on light backgrounds.</p>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 2rem;">

    <!-- Teal (primary) -->
    <div>
        <h4 style="margin-bottom: 12px;">Teal (primary)</h4>
        <?php
        $teal = [
            ['#00AEAF', 'Brand color (500)'],
            ['#008D8E', 'Hover / active (600)'],
            ['#2FC6C7', 'Secondary UI elements (400)'],
            ['#D5F5F5', 'Backgrounds (100) — token: --sdwd-accent-bg'],
            ['#F2FBFB', 'Subtle bg (50) — token: --sdwd-accent-bg-soft'],
            ['#10595A', 'Text / icons (800)'],
        ];
        foreach ( $teal as $c ) :
        ?>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
            <span style="width: 36px; height: 36px; border-radius: 6px; background: <?php echo $c[0]; ?>; border: 1px solid #ddd; flex-shrink: 0;"></span>
            <span><code style="font-size: 12px;"><?php echo $c[0]; ?></code> <small style="color: #666;">— <?php echo $c[1]; ?></small></span>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Orange (CTA only) -->
    <div>
        <h4 style="margin-bottom: 12px;">Orange (CTA only)</h4>
        <?php
        $orange = [
            ['#EC4D25', 'Primary CTA (500)'],
            ['#C93A17', 'Hover / active (600)'],
            ['#FFE0D8', 'Subtle highlights / alerts (100)'],
        ];
        foreach ( $orange as $c ) :
        ?>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
            <span style="width: 36px; height: 36px; border-radius: 6px; background: <?php echo $c[0]; ?>; border: 1px solid #ddd; flex-shrink: 0;"></span>
            <span><code style="font-size: 12px;"><?php echo $c[0]; ?></code> <small style="color: #666;">— <?php echo $c[1]; ?></small></span>
        </div>
        <?php endforeach; ?>
        <p style="margin-top: 8px; font-size: 13px; color: var(--sdwd-muted);">Do not use for body text.</p>
    </div>

    <!-- Purple (dashboard only) -->
    <div>
        <h4 style="margin-bottom: 12px;">Purple (dashboard only)</h4>
        <?php
        $purple = [
            ['#A142F4', 'Dashboard brand (500)'],
            ['#8835D0', 'Hover / active (600)'],
            ['#E6D7FE', 'Panels / backgrounds (100)'],
            ['#582388', 'Text / icons inside dashboard (800)'],
        ];
        foreach ( $purple as $c ) :
        ?>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
            <span style="width: 36px; height: 36px; border-radius: 6px; background: <?php echo $c[0]; ?>; border: 1px solid #ddd; flex-shrink: 0;"></span>
            <span><code style="font-size: 12px;"><?php echo $c[0]; ?></code> <small style="color: #666;">— <?php echo $c[1]; ?></small></span>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<h3>Typography Scale</h3>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
    <thead>
        <tr style="text-align: left; border-bottom: 2px solid var(--sdwd-border);">
            <th style="padding: 8px;">Token</th>
            <th style="padding: 8px;">Value</th>
            <th style="padding: 8px;">Preview</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-h1</code></td>
            <td style="padding: 8px;">32px / weight 800 / Inter</td>
            <td style="padding: 8px; font-size: var(--sdwd-h1); font-weight: 800; font-family: var(--sdwd-heading-font); line-height: 1.2;">Heading 1</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-h2</code></td>
            <td style="padding: 8px;">24px / weight 800 / Inter</td>
            <td style="padding: 8px; font-size: var(--sdwd-h2); font-weight: 800; font-family: var(--sdwd-heading-font); line-height: 1.2;">Heading 2</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-h3</code></td>
            <td style="padding: 8px;">20px / weight 700 / Inter</td>
            <td style="padding: 8px; font-size: var(--sdwd-h3); font-weight: 700; font-family: var(--sdwd-heading-font); line-height: 1.2;">Heading 3</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-h4</code></td>
            <td style="padding: 8px;">18px / weight 700 / Inter</td>
            <td style="padding: 8px; font-size: var(--sdwd-h4); font-weight: 700; font-family: var(--sdwd-heading-font); line-height: 1.2;">Heading 4</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-h5</code></td>
            <td style="padding: 8px;">16px / weight 700 / Inter</td>
            <td style="padding: 8px; font-size: var(--sdwd-h5); font-weight: 700; font-family: var(--sdwd-heading-font); line-height: 1.2;">Heading 5</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-h6</code></td>
            <td style="padding: 8px;">14px / weight 700 / Inter</td>
            <td style="padding: 8px; font-size: var(--sdwd-h6); font-weight: 700; font-family: var(--sdwd-heading-font); line-height: 1.2;">Heading 6</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-body</code></td>
            <td style="padding: 8px;">18px / Work Sans</td>
            <td style="padding: 8px; font-family: var(--sdwd-body-font); font-size: var(--sdwd-body);">This is body text at the default size using Work Sans.</td>
        </tr>
    </tbody>
</table>

<h3>Spacing Tokens</h3>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
    <thead>
        <tr style="text-align: left; border-bottom: 2px solid var(--sdwd-border);">
            <th style="padding: 8px;">Token</th>
            <th style="padding: 8px;">Value</th>
            <th style="padding: 8px;">Usage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-section-gap</code></td>
            <td style="padding: 8px;">64px</td>
            <td style="padding: 8px;">Only used between major sections</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-row-gap</code></td>
            <td style="padding: 8px;">32px</td>
            <td style="padding: 8px;">Gap between cards, grid items, and rows of content</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-title-gap</code></td>
            <td style="padding: 8px;">24px</td>
            <td style="padding: 8px;">Space between a section title and its immediate content</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>--sdwd-container-width</code></td>
            <td style="padding: 8px;">1320px</td>
            <td style="padding: 8px;">Max width of content containers</td>
        </tr>
    </tbody>
</table>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     HEADINGS
     ============================================================ -->
<h2>Headings</h2>
<div style="margin-bottom: 2rem;">
    <h1>Heading 1 — Find the Perfect Wedding Vendor</h1>
    <h2>Heading 2 — San Diego Wedding Vendors</h2>
    <h3>Heading 3 — Browse Wedding Venues by City</h3>
    <h4>Heading 4 — San Diego Wedding Vendors, All in One Place</h4>
    <h5>Heading 5 — Subheading Level</h5>
    <h6>Heading 6 — Smallest Heading</h6>
</div>

<h3>Section Title (default — left-aligned)</h3>
<div style="margin-bottom: 2rem;">
    <div class="section-title col text-start">
        <h2>San Diego Wedding Vendors</h2>
        <p>Quickly connect with the industry's top wedding professionals</p>
    </div>
</div>

<h3>Section Title (centered variant)</h3>
<div style="margin-bottom: 2rem;">
    <div class="section-title col text-center">
        <h2>San Diego Wedding Vendors</h2>
        <p>Quickly connect with the industry's top wedding professionals</p>
    </div>
</div>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     BUTTONS
     ============================================================ -->
<h2>Buttons</h2>

<h3>Button System</h3>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
    <thead>
        <tr style="text-align: left; border-bottom: 2px solid var(--sdwd-border);">
            <th style="padding: 8px;">Type</th>
            <th style="padding: 8px;">Default</th>
            <th style="padding: 8px;">Hover</th>
            <th style="padding: 8px;">Pressed</th>
            <th style="padding: 8px;">Disabled</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 12px 8px;"><code>.btn--primary</code></td>
            <td style="padding: 12px 8px;"><button class="btn btn--primary" type="button">Primary</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--primary is-hover" type="button">Primary</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--primary is-active" type="button">Primary</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--primary" type="button" disabled>Primary</button></td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 12px 8px;"><code>.btn--outline-primary</code></td>
            <td style="padding: 12px 8px;"><button class="btn btn--outline-primary" type="button">Primary Outline</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--outline-primary is-hover" type="button">Primary Outline</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--outline-primary is-active" type="button">Primary Outline</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--outline-primary" type="button" disabled>Primary Outline</button></td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 12px 8px;"><code>.btn--outline</code></td>
            <td style="padding: 12px 8px;"><button class="btn btn--outline" type="button">Outline</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--outline is-hover" type="button">Outline</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--outline is-active" type="button">Outline</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--outline" type="button" disabled>Outline</button></td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 12px 8px;"><code>.btn--cta</code></td>
            <td style="padding: 12px 8px;"><button class="btn btn--cta" type="button">Major CTA</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--cta is-hover" type="button">Major CTA</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--cta is-active" type="button">Major CTA</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--cta" type="button" disabled>Major CTA</button></td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 12px 8px;"><code>.btn--secondary</code></td>
            <td style="padding: 12px 8px;"><button class="btn btn--secondary" type="button">Secondary</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--secondary is-hover" type="button">Secondary</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--secondary is-active" type="button">Secondary</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--secondary" type="button" disabled>Secondary</button></td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 12px 8px;"><code>.btn--ghost</code></td>
            <td style="padding: 12px 8px;"><button class="btn btn--ghost" type="button">Ghost</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--ghost is-hover" type="button">Ghost</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--ghost is-active" type="button">Ghost</button></td>
            <td style="padding: 12px 8px;"><button class="btn btn--ghost" type="button" disabled>Ghost</button></td>
        </tr>
    </tbody>
</table>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     CARDS
     ============================================================ -->
<h2>Cards</h2>

<h3>Card Shadow Pattern</h3>
<div class="row" style="margin-bottom: 2rem;">
    <div class="col-md-6">
        <div class="card-shadow">
            <div class="card-shadow-header">
                <h3><i class="fa fa-info-circle"></i> Card Shadow Header</h3>
            </div>
            <div class="card-shadow-body">
                <p>This is the card-shadow pattern used for sidebar widgets and content blocks. It has a subtle shadow, rounded corners, and a clean header/body split.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-shadow">
            <div class="card-shadow-header">
                <h3><i class="fa fa-star"></i> Another Card</h3>
            </div>
            <div class="card-shadow-body">
                <p>Cards can hold any content — text, lists, forms, or other components.</p>
                <a class="btn btn--small btn--primary" href="#">Action</a>
            </div>
        </div>
    </div>
</div>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     FORMS
     ============================================================ -->
<h2>Form Elements</h2>

<div class="row" style="margin-bottom: 2rem;">
    <div class="col-md-6">
        <h3>Default Input</h3>
        <div style="margin-bottom: 1rem;">
            <label style="margin-bottom: 4px; display: block; font-weight: 600;">Text Input</label>
            <input class="form-control" type="text" placeholder="Enter your name">
        </div>
        <div style="margin-bottom: 1rem;">
            <label style="margin-bottom: 4px; display: block; font-weight: 600;">Email Input</label>
            <input class="form-control" type="email" placeholder="you@example.com">
        </div>
        <div style="margin-bottom: 1rem;">
            <label style="margin-bottom: 4px; display: block; font-weight: 600;">Select</label>
            <select class="form-control">
                <option>Choose an option</option>
                <option>Photography</option>
                <option>DJ</option>
                <option>Catering</option>
            </select>
        </div>
        <div style="margin-bottom: 1rem;">
            <label style="margin-bottom: 4px; display: block; font-weight: 600;">Textarea</label>
            <textarea class="form-control" rows="3" placeholder="Tell us about your wedding..."></textarea>
        </div>
    </div>
    <div class="col-md-6">
        <h3>Radio Buttons</h3>
        <div class="form-check" style="margin-bottom: 0.5rem;">
            <input class="form-check-input" type="radio" name="sg-radio" id="sg-radio-1" checked>
            <label class="form-check-label" for="sg-radio-1">Venues</label>
        </div>
        <div class="form-check" style="margin-bottom: 0.5rem;">
            <input class="form-check-input" type="radio" name="sg-radio" id="sg-radio-2">
            <label class="form-check-label" for="sg-radio-2">Vendors</label>
        </div>

        <h3 style="margin-top: 1.5rem;">Checkboxes</h3>
        <div class="form-check" style="margin-bottom: 0.5rem;">
            <input class="form-check-input" type="checkbox" id="sg-check-1" checked>
            <label class="form-check-label" for="sg-check-1">Outdoor ceremony</label>
        </div>
        <div class="form-check" style="margin-bottom: 0.5rem;">
            <input class="form-check-input" type="checkbox" id="sg-check-2">
            <label class="form-check-label" for="sg-check-2">Indoor reception</label>
        </div>
        <div class="form-check" style="margin-bottom: 0.5rem;">
            <input class="form-check-input" type="checkbox" id="sg-check-3">
            <label class="form-check-label" for="sg-check-3">Ocean view</label>
        </div>

        <h3 style="margin-top: 1.5rem;">Inline Radio</h3>
        <div style="display: flex; gap: 1.5rem;">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sg-inline" id="sg-inline-1" checked>
                <label class="form-check-label" for="sg-inline-1">Venues</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sg-inline" id="sg-inline-2">
                <label class="form-check-label" for="sg-inline-2">Vendors</label>
            </div>
        </div>
    </div>
</div>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     BREADCRUMBS
     ============================================================ -->
<h2>Breadcrumbs</h2>
<div style="margin-bottom: 2rem;">
    <ul class="sdwd-breadcrumb">
        <li><a href="<?php echo esc_url( home_url() ); ?>">Home</a></li>
        <li><span class="sdwd-breadcrumb-sep">/</span></li>
        <li><a href="#">Vendors</a></li>
        <li><span class="sdwd-breadcrumb-sep">/</span></li>
        <li><a href="#">Photography</a></li>
        <li><span class="sdwd-breadcrumb-sep">/</span></li>
        <li><span>John Smith Photography</span></li>
    </ul>
</div>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     GRID LAYOUT
     ============================================================ -->
<h2>Grid Layout</h2>
<p>This theme uses CSS Grid only. Bootstrap row/col classes are not part of the v2 system.</p>

<h3>4-Column Grid</h3>
<div class="grid grid--4col" style="margin-bottom: 1.5rem;">
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
</div>
<p style="margin-bottom: 1.5rem;"><code>.grid</code> + <code>.grid--4col</code></p>

<h3>3-Column Grid</h3>
<div class="grid grid--3col" style="margin-bottom: 1.5rem;">
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
</div>
<p style="margin-bottom: 1.5rem;"><code>.grid</code> + <code>.grid--3col</code></p>

<h3>2-Column Grid</h3>
<div class="grid grid--2col" style="margin-bottom: 1.5rem;">
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
    <div style="background: var(--sdwd-soft); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid var(--sdwd-border);">grid item</div>
</div>
<p style="margin-bottom: 1.5rem;"><code>.grid</code> + <code>.grid--2col</code></p>

<ul style="margin-bottom: 2rem; line-height: 1.7;">
    <li>CSS Grid only</li>
    <li>No Bootstrap row / col-* classes</li>
    <li>Mobile-first layout</li>
    <li>Grid behavior controlled in layout.css</li>
    <li>Shared grid utilities should use <code>.grid</code> and <code>.grid--*</code> naming only</li>
</ul>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     TEXT STYLES
     ============================================================ -->
<h2>Text Styles</h2>
<div style="margin-bottom: 2rem;">
    <p style="margin-bottom: 1rem;"><strong>Body text (default):</strong> This is standard body text using Work Sans at the default size. It should be easy to read and well-spaced for directory content, descriptions, and general page copy.</p>
    <p style="margin-bottom: 1rem; color: var(--sdwd-muted);"><strong>Muted text (--sdwd-muted):</strong> Used for secondary information, timestamps, and supporting details that shouldn't compete with primary content.</p>
    <p style="margin-bottom: 1rem; color: var(--sdwd-subtext);"><strong>Subtext (--sdwd-subtext):</strong> Used for section subtitles and descriptive text below headings.</p>
    <p style="margin-bottom: 1rem;"><a href="#" style="color: var(--sdwd-accent);">Accent link color</a> — used for interactive elements and CTAs.</p>
    <p style="margin-bottom: 1rem;"><strong>Bold text</strong>, <em>italic text</em>, and <code>inline code</code> for reference.</p>
</div>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     BADGES
     ============================================================ -->
<h2>Badges &amp; Labels</h2>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; margin-bottom: 2rem;">
    <span style="background: var(--sdwd-accent); color: #fff; padding: 4px 12px; border-radius: 999px; font-size: 13px; font-weight: 700;">Featured</span>
    <span style="background: var(--sdwd-dark); color: #fff; padding: 4px 12px; border-radius: 999px; font-size: 13px; font-weight: 700;">Verified</span>
    <span style="background: #f48f00; color: #fff; padding: 4px 12px; border-radius: 999px; font-size: 13px; font-weight: 700;">Popular</span>
    <span style="background: var(--sdwd-soft); color: var(--sdwd-dark); padding: 4px 12px; border-radius: 999px; font-size: 13px; font-weight: 600; border: 1px solid var(--sdwd-border);">Outdoor</span>
    <span style="background: var(--sdwd-soft); color: var(--sdwd-dark); padding: 4px 12px; border-radius: 999px; font-size: 13px; font-weight: 600; border: 1px solid var(--sdwd-border);">Ocean View</span>
</div>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     HOVER & INTERACTIONS
     ============================================================ -->
<h2>Hover &amp; Interactions</h2>
<p>All transitions use <code>--sdwd-ease</code> (0.2s ease). Hover the elements below to see their behavior.</p>

<h3>Buttons</h3>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
    <a class="btn btn--primary" href="#">Primary — lifts on hover</a>
    <a class="btn btn--outline" href="#">Outline — black border on hover</a>
    <a class="btn btn--outline-primary" href="#">Outline Primary — fills teal on hover</a>
    <a class="btn btn-dark btn-rounded" href="#">Dark — lightens on hover</a>
</div>

<h3>Carousel Arrows</h3>
<p>Reusable arrow buttons for all carousels. Size controlled by <code>--sdwd-carousel-arrow-size</code> (40px). Use <code>.carousel-arrow</code> with <code>.carousel-arrow--prev</code> or <code>.carousel-arrow--next</code>.</p>
<div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
    <a class="carousel-arrow carousel-arrow--prev" href="#">
        <span class="icon-chevron-left carousel-arrow__icon" aria-hidden="true"></span>
    </a>
    <a class="carousel-arrow carousel-arrow--next" href="#">
        <span class="icon-chevron-left carousel-arrow__icon" aria-hidden="true"></span>
    </a>
    <span style="color: var(--sdwd-muted); font-size: 14px;">Hover to see accent highlight</span>
</div>

<h3>Links</h3>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem;">
    <thead>
        <tr style="text-align: left; border-bottom: 2px solid var(--sdwd-border);">
            <th style="padding: 8px;">Context</th>
            <th style="padding: 8px;">Default</th>
            <th style="padding: 8px;">Hover Behavior</th>
            <th style="padding: 8px;">Try It</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Body link</td>
            <td style="padding: 8px;">Accent color (<code>--sdwd-accent</code>)</td>
            <td style="padding: 8px;">Darkens to <code>--sdwd-accent-dark</code></td>
            <td style="padding: 8px;"><a href="#">Sample link</a></td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Inline category link</td>
            <td style="padding: 8px;">Dark text (<code>#2d2d2d</code>)</td>
            <td style="padding: 8px;">Orange + underline</td>
            <td style="padding: 8px;"><a class="sd-home-link-row" href="#" style="color: #2d2d2d; text-decoration: none; border-bottom: 1px solid transparent;">Wedding Venues</a></td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Breadcrumb link</td>
            <td style="padding: 8px;">Muted (<code>--sdwd-muted</code>)</td>
            <td style="padding: 8px;">Accent color</td>
            <td style="padding: 8px;">
                <ul class="sdwd-breadcrumb" style="margin: 0;">
                    <li><a href="#">Vendors</a></li>
                    <li><span class="sdwd-breadcrumb-sep">/</span></li>
                    <li><span>Current</span></li>
                </ul>
            </td>
        </tr>
    </tbody>
</table>

<h3>Cards</h3>
<div class="row" style="margin-bottom: 1.5rem;">
    <div class="col-md-4">
        <div class="card-shadow" style="transition: box-shadow 0.2s ease, transform 0.2s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.12)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow=''; this.style.transform='';">
            <div class="card-shadow-body">
                <h4>Card Hover</h4>
                <p>Cards lift slightly and deepen their shadow on hover.</p>
            </div>
        </div>
    </div>
</div>

<h3>Form Inputs</h3>
<div style="max-width: 400px; margin-bottom: 1.5rem;">
    <p style="margin-bottom: 0.5rem; color: var(--sdwd-muted); font-size: 13px;">Click into the input to see focus state: border darkens, background goes white.</p>
    <input class="form-control" type="text" placeholder="Focus me to see the transition">
</div>

<h3>Interaction Rules</h3>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
    <thead>
        <tr style="text-align: left; border-bottom: 2px solid var(--sdwd-border);">
            <th style="padding: 8px;">Element</th>
            <th style="padding: 8px;">Hover Effect</th>
            <th style="padding: 8px;">Transition</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">SDWD buttons</td>
            <td style="padding: 8px;"><code>translateY(-1px)</code> lift</td>
            <td style="padding: 8px;">0.2s ease (background, color, border, transform)</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Primary button</td>
            <td style="padding: 8px;">Background darkens to <code>--sdwd-accent-dark</code></td>
            <td style="padding: 8px;">0.2s ease</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Outline button</td>
            <td style="padding: 8px;">Border turns black</td>
            <td style="padding: 8px;">0.2s ease</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Outline primary button</td>
            <td style="padding: 8px;">Fills with accent color, text goes white</td>
            <td style="padding: 8px;">0.2s ease</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Inline links (category, city)</td>
            <td style="padding: 8px;">Color → orange, underline appears</td>
            <td style="padding: 8px;">0.4s ease (inherited from base <code>.btn</code>)</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Breadcrumb links</td>
            <td style="padding: 8px;">Color → accent</td>
            <td style="padding: 8px;">None (instant)</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Form inputs</td>
            <td style="padding: 8px;">Border darkens (#acacac), background → white</td>
            <td style="padding: 8px;">0.4s ease-in-out</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;">Cards (where applicable)</td>
            <td style="padding: 8px;">Shadow deepens, <code>translateY(-2px)</code> lift</td>
            <td style="padding: 8px;">0.2s ease</td>
        </tr>
    </tbody>
</table>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     HARDCODED HEX COLORS (NOT YET TOKENIZED)
     ============================================================ -->
<h2>Extended Token Palette</h2>
<p>These design tokens were added to <code>foundation.css</code> to eliminate all raw hex values from the CSS. Every color in the system is now a CSS custom property.</p>

<h4>Header Buttons</h4>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #f48f00; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#f48f00</code>
        <small style="color: #666; font-size: 11px;">Join as Couple btn bg</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #d47c00; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#d47c00</code>
        <small style="color: #666; font-size: 11px;">Join as Couple hover</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #00aeaf; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#00aeaf</code>
        <small style="color: #666; font-size: 11px;">Join as Vendor btn bg</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #009091; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#009091</code>
        <small style="color: #666; font-size: 11px;">Join as Vendor hover</small>
    </div>
</div>

<h4>Primary Button States</h4>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #24b5b4; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#24b5b4</code>
        <small style="color: #666; font-size: 11px;">btn--primary :hover</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #1d9b9a; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#1d9b9a</code>
        <small style="color: #666; font-size: 11px;">btn--primary :active + outline-primary :active</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #bfeded; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#bfeded</code>
        <small style="color: #666; font-size: 11px;">btn--primary :disabled</small>
    </div>
</div>

<h4>CTA Button States</h4>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #d74420; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#d74420</code>
        <small style="color: #666; font-size: 11px;">btn--cta :hover + hero submit :hover</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #b83a1b; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#b83a1b</code>
        <small style="color: #666; font-size: 11px;">btn--cta :active + hero submit :active</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #f2b3a5; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#f2b3a5</code>
        <small style="color: #666; font-size: 11px;">btn--cta :disabled</small>
    </div>
</div>

<h4>Footer Dark Theme</h4>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #1f1d24; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#1f1d24</code>
        <small style="color: #666; font-size: 11px;">Footer background</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #252428; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#252428</code>
        <small style="color: #666; font-size: 11px;">Footer tiny bar bg</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #17151b; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#17151b</code>
        <small style="color: #666; font-size: 11px;">Newsletter input bg</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #f5f7fa; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#f5f7fa</code>
        <small style="color: #666; font-size: 11px;">Footer body text</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #d7d5de; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#d7d5de</code>
        <small style="color: #666; font-size: 11px;">Footer link + widget text</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #4c4957; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#4c4957</code>
        <small style="color: #666; font-size: 11px;">Newsletter input border</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #b9b7c5; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#b9b7c5</code>
        <small style="color: #666; font-size: 11px;">Newsletter placeholder</small>
    </div>
</div>

<h4>Mega Menu</h4>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #202020; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#202020</code>
        <small style="color: #666; font-size: 11px;">Mega menu headings</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #1f1f1f; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#1f1f1f</code>
        <small style="color: #666; font-size: 11px;">Mega card link + title</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #3a3a3a; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#3a3a3a</code>
        <small style="color: #666; font-size: 11px;">Submenu links</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #5c5c5c; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#5c5c5c</code>
        <small style="color: #666; font-size: 11px;">Mega card description</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #7a7a7a; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#7a7a7a / #777</code>
        <small style="color: #666; font-size: 11px;">Menu icons</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #e9edef; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#e9edef</code>
        <small style="color: #666; font-size: 11px;">Mega menu top border</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #e2e2e2; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#e2e2e2</code>
        <small style="color: #666; font-size: 11px;">Mega card border</small>
    </div>
</div>

<h4>Hero Search</h4>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #d9d9d9; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#d9d9d9</code>
        <small style="color: #666; font-size: 11px;">Search form border</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #9a9a9a; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#9a9a9a</code>
        <small style="color: #666; font-size: 11px;">Search field icon</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #6b6b6b; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#6b6b6b</code>
        <small style="color: #666; font-size: 11px;">Search input text + placeholder</small>
    </div>
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #8f98a3; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#8f98a3</code>
        <small style="color: #666; font-size: 11px;">Toggle radio border</small>
    </div>
</div>

<h4>Utility</h4>
<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="text-align: center; width: 140px;">
        <div style="width: 100%; height: 50px; background: #f0a030; border-radius: 8px; border: 1px solid #ddd;"></div>
        <code style="font-size: 11px; display: block; margin-top: 4px;">#f0a030</code>
        <small style="color: #666; font-size: 11px;">Review star color</small>
    </div>
</div>

<hr style="margin: 2rem 0;">

<!-- ============================================================
     CURRENT CSS FILES
     ============================================================ -->
<h2>CSS Architecture (Current)</h2>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
    <thead>
        <tr style="text-align: left; border-bottom: 2px solid var(--sdwd-border);">
            <th style="padding: 8px;">File</th>
            <th style="padding: 8px;">Purpose</th>
            <th style="padding: 8px;">Load Order</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>foundation.css</code></td>
            <td style="padding: 8px;">CSS custom properties (design tokens), reset, @font-face, base typography</td>
            <td style="padding: 8px;">1</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>components.css</code></td>
            <td style="padding: 8px;">Reusable UI (cards, mega menus, section titles, breadcrumbs, accordions)</td>
            <td style="padding: 8px;">2</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>layout.css</code></td>
            <td style="padding: 8px;">Container, grid, header, footer, responsive breakpoints</td>
            <td style="padding: 8px;">3</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>pages/*.css</code></td>
            <td style="padding: 8px;">Page-specific styles (loaded conditionally per page type)</td>
            <td style="padding: 8px;">4</td>
        </tr>
        <tr style="border-bottom: 1px solid var(--sdwd-border);">
            <td style="padding: 8px;"><code>style.css</code></td>
            <td style="padding: 8px;">Theme metadata only (no styles)</td>
            <td style="padding: 8px;">—</td>
        </tr>
    </tbody>
</table>

<p style="color: var(--sdwd-muted); font-size: 13px; margin-top: 2rem;">Last updated: <?php echo date( 'Y-m-d' ); ?></p>

</section>
</div>
</div>
</div>
<?php get_footer(); ?>
