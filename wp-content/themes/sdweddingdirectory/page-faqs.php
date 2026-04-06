<?php
/**
 *  SDWeddingDirectory - FAQs Page
 *  -----------------------------
 */
global $wp_query;

$paged = get_query_var( 'paged' )
    ? absint( get_query_var( 'paged' ) )
    : absint( '1' );

get_header();
get_template_part( 'template-parts/policy-subnav' );
?>
<div class="main-content content wide-tb-90">
<div class="container">
<div class="row">
<section id="primary" class="content-area primary col-12">

<div class="section-title col text-start">
    <h1>Frequently Asked Questions</h1>
    <p>FAQ's</p>
</div>

<ul class="nav nav-pills theme-tabbing nav-fill" id="sdweddingdirectory_faq-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="sdweddingdirectory_faq_general-tab" data-bs-toggle="pill" href="#sdweddingdirectory_faq_general" role="tab" aria-controls="sdweddingdirectory_faq_general" aria-selected="true">General</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="sdweddingdirectory_faq_vendor-tab" data-bs-toggle="pill" href="#sdweddingdirectory_faq_vendor" role="tab" aria-controls="sdweddingdirectory_faq_vendor" aria-selected="false">Vendor</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="sdweddingdirectory_faq_couples-tab" data-bs-toggle="pill" href="#sdweddingdirectory_faq_couples" role="tab" aria-controls="sdweddingdirectory_faq_couples" aria-selected="false">Groom &amp; Brides</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="sdweddingdirectory_faq_pricing-tab" data-bs-toggle="pill" href="#sdweddingdirectory_faq_pricing" role="tab" aria-controls="sdweddingdirectory_faq_pricing" aria-selected="false">Pricing</a>
    </li>
</ul>

<div class="tab-content theme-tabbing" id="sdweddingdirectory_faq-tabContent">
    <div id="sdweddingdirectory_faq_general" class="tab-pane fade show active" role="tabpanel" aria-labelledby="sdweddingdirectory_faq_general-tab">
        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    </div>
    <div id="sdweddingdirectory_faq_vendor" class="tab-pane fade" role="tabpanel" aria-labelledby="sdweddingdirectory_faq_vendor-tab">
        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    </div>
    <div id="sdweddingdirectory_faq_couples" class="tab-pane fade" role="tabpanel" aria-labelledby="sdweddingdirectory_faq_couples-tab">
        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    </div>
    <div id="sdweddingdirectory_faq_pricing" class="tab-pane fade" role="tabpanel" aria-labelledby="sdweddingdirectory_faq_pricing-tab">
        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    </div>
</div>

<div class="accordion theme-accordian" id="sdweddingdirectory_faq_accordion">
    <div class="card-header" id="sdweddingdirectory_faq_heading_one">
        <a href="javascript:" data-bs-toggle="collapse" data-bs-target="#sdweddingdirectory_faq_collapse_one" aria-expanded="true" aria-controls="sdweddingdirectory_faq_collapse_one">How to contact with Customer Service ?</a>
    </div>
    <div id="sdweddingdirectory_faq_collapse_one" class="collapse show" aria-labelledby="sdweddingdirectory_faq_heading_one" data-bs-parent="#sdweddingdirectory_faq_accordion">
        <div class="card-body">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
    </div>

    <div class="card-header" id="sdweddingdirectory_faq_heading_two">
        <a href="javascript:" class="collapsed" data-bs-toggle="collapse" data-bs-target="#sdweddingdirectory_faq_collapse_two" aria-expanded="false" aria-controls="sdweddingdirectory_faq_collapse_two">How delete my account?</a>
    </div>
    <div id="sdweddingdirectory_faq_collapse_two" class="collapse" aria-labelledby="sdweddingdirectory_faq_heading_two" data-bs-parent="#sdweddingdirectory_faq_accordion">
        <div class="card-body">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
    </div>

    <div class="card-header" id="sdweddingdirectory_faq_heading_three">
        <a href="javascript:" class="collapsed" data-bs-toggle="collapse" data-bs-target="#sdweddingdirectory_faq_collapse_three" aria-expanded="false" aria-controls="sdweddingdirectory_faq_collapse_three">Where is the edit option on dashboard</a>
    </div>
    <div id="sdweddingdirectory_faq_collapse_three" class="collapse" aria-labelledby="sdweddingdirectory_faq_heading_three" data-bs-parent="#sdweddingdirectory_faq_accordion">
        <div class="card-body">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
    </div>

    <div class="card-header" id="sdweddingdirectory_faq_heading_four">
        <a href="javascript:" class="collapsed" data-bs-toggle="collapse" data-bs-target="#sdweddingdirectory_faq_collapse_four" aria-expanded="false" aria-controls="sdweddingdirectory_faq_collapse_four">How to contact with Customer Service ?</a>
    </div>
    <div id="sdweddingdirectory_faq_collapse_four" class="collapse" aria-labelledby="sdweddingdirectory_faq_heading_four" data-bs-parent="#sdweddingdirectory_faq_accordion">
        <div class="card-body">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
    </div>
</div>

<div class="row row-cols-xxl-3 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">
    <div class="col">
        <div class="contact-details-wrap">
            <i class="sdweddingdirectory-support"></i>
            <h3 class="text-primary">Call our 24-hour helpline</h3>
            <p class="my-4">Call our 24-hour helpline</p>
            <div>
                <p>Phone number: <a href="javascript:" class="btn-link btn-link-default">+800-123-4567</a></p>
                <p>Email Us: <a href="mailto:info@sdweddingdirectory.com" class="btn-link btn-link-primary">info@sdweddingdirectory.com</a></p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="contact-details-wrap">
            <i class="sdweddingdirectory-location"></i>
            <h3 class="text-primary">Our Address</h3>
            <p class="my-4">Our offices are located in the Gujarat.</p>
            <p>Address: 4998 Elk Creek Road Canton, GA 30114</p>
        </div>
    </div>
    <div class="col">
        <div class="contact-details-wrap">
            <i class="sdweddingdirectory-support"></i>
            <h3 class="text-primary">Other Enquiries</h3>
            <p class="my-4">Please contact us at the email below for all other inquiries.</p>
            <div>Email Us: <a href="mailto:info@sdweddingdirectory.com" class="btn-link btn-link-primary">info@sdweddingdirectory.com</a></div>
        </div>
    </div>
</div>

<?php
if ( isset( $wp_query ) ) {
    print apply_filters(
        'sdweddingdirectory/pagination',
        [
            'numpages' => absint( $wp_query->max_num_pages ),
            'paged'    => absint( $paged ),
        ]
    );
}
?>
</section>
</div>
</div>
</div>
<?php get_footer();

