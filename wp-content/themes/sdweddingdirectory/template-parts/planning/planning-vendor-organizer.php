<?php
/**
 * Wedding planning vendor organizer feature section.
 */
?>
<section class="sdwd-plan-feature-section sdwd-plan-full-bleed" aria-label="Vendor Manager Feature Section">
    <div class="sdwd-plan-wrap">
      <div class="sdwd-plan-feature-shell">
        <div class="sdwd-plan-feature-grid">
          <div class="sdwd-plan-feature-left">
            <h2>Vendor Organizer</h2>
            <p>Search, organize, and communicate with vendors all in one place.</p>
            <a class="sdwd-plan-learn" href="/wedding-planning/">Learn more</a>

            <div class="sdwd-plan-feature-stack">
              <div class="sdwd-plan-feature-item">
                <h3>Reach out with ease</h3>
                <p>Browse professionals and send messages directly through your SDWeddingDirectory account.</p>
              </div>
              <div class="sdwd-plan-feature-item">
                <h3>Keep detailed notes</h3>
                <p>Store important information and reminders for each vendor so nothing gets forgotten.</p>
              </div>
              <div class="sdwd-plan-feature-item">
                <h3>Save and compare</h3>
                <p>Bookmark top choices and review pricing and feedback side by side to make confident decisions.</p>
              </div>
            </div>

            <a
              class="sdwd-plan-btn"
              href="/couple-dashboard/?dashboard=vendor-manager"
              data-bs-toggle="modal"
              data-bs-target="#sdweddingdirectory_couple_login_model_popup"
              data-modal-id="couple_login"
              data-modal-redirection="/couple-dashboard/?dashboard=vendor-manager"
              data-sdweddingdirectory-login="couple"
            >Browse Vendors</a>
          </div>

          <div class="sdwd-plan-feature-right">
            <img loading="lazy" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/planning/wedding-planning-vendor-manager.png' ); ?>" alt="Vendor Manager" class="sdwd-plan-screenshot" style="width:100%;height:auto;border-radius:12px;box-shadow:0 2px 16px rgba(0,0,0,0.10);"><div class="sdwd-plan-testimonial">
              <div class="sdwd-plan-testimonial-top">
                <span class="sdwd-plan-avatar">JM</span>
                <strong>Jordan and Mia</strong>
              </div>
              <p>"Having notes and messages in one place made vendor decisions so much faster."</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
