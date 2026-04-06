<?php
/**
 * Wedding planning checklist feature section.
 */
?>
<section class="sdwd-plan-feature-section sdwd-plan-full-bleed" aria-label="Checklist Feature Section">
    <div class="sdwd-plan-wrap">
      <div class="sdwd-plan-feature-shell">
        <div class="sdwd-plan-feature-grid">
          <div class="sdwd-plan-feature-left">
            <h2>Wedding Planning Checklist</h2>
            <p>Stay on track with a complete planning timeline so nothing slips through the cracks.</p>
            <a class="sdwd-plan-learn" href="/wedding-planning/">Learn more</a>

            <div class="sdwd-plan-feature-stack">
              <div class="sdwd-plan-feature-item">
                <h3>Make it your own</h3>
                <p>Customize your checklist by adding new tasks, editing details, or removing items whenever you need.</p>
              </div>
              <div class="sdwd-plan-feature-item">
                <h3>Monitor your progress</h3>
                <p>Instantly see what is finished and what still needs attention as your wedding date approaches.</p>
              </div>
              <div class="sdwd-plan-feature-item">
                <h3>Connected to your budget</h3>
                <p>Your SDWeddingDirectory budget and checklist work together so every task aligns with your spending plan.</p>
              </div>
            </div>

            <a
              class="sdwd-plan-btn"
              href="/couple-dashboard/?dashboard=checklist"
              data-bs-toggle="modal"
              data-bs-target="#sdweddingdirectory_couple_login_model_popup"
              data-modal-id="couple_login"
              data-modal-redirection="/couple-dashboard/?dashboard=checklist"
              data-sdweddingdirectory-login="couple"
            >Personalize Checklist</a>
          </div>

          <div class="sdwd-plan-feature-right">
            <img loading="lazy" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/planning/wedding-planning-checklist.png' ); ?>" alt="Wedding Planning Checklist" class="sdwd-plan-screenshot" style="width:100%;height:auto;border-radius:12px;box-shadow:0 2px 16px rgba(0,0,0,0.10);"><div class="sdwd-plan-testimonial">
              <div class="sdwd-plan-testimonial-top">
                <span class="sdwd-plan-avatar">AL</span>
                <strong>Alex and Lauren</strong>
              </div>
              <p>"The checklist made it simple to stay focused and keep every deadline moving forward."</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
