<?php
/**
 * Planning: Child Page Orchestrator
 *
 * Full-width section layout for planning child pages (parent ID 4180).
 * Sections: banner → signup form → section title → icon cards →
 *           3 feature blocks (alternating) → 5 tool cards → detailed copy → FAQ
 */

$current_slug = get_post_field( 'post_name', get_the_ID() );
$theme_uri    = get_template_directory_uri();

// ---------------------------------------------------------------------------
// Per-page data keyed by slug
// ---------------------------------------------------------------------------
$child_data = [

    'wedding-checklist' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Checklist', 'sdweddingdirectory' ),
        'title'       => __( 'Your wedding checklist', 'sdweddingdirectory' ),
        'desc'        => __( 'A personalized planning timeline that keeps every task on track from engagement to the big day.', 'sdweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'checklist/1_clipboard.svg', 'title' => __( 'Discover how to start', 'sdweddingdirectory' ),   'desc' => __( 'Start with our recommended checklist, pre-filled with essential tasks organized by your wedding date.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'checklist/2_timeline.svg',  'title' => __( 'Track your progress', 'sdweddingdirectory' ),     'desc' => __( 'See what is done and what is next at a glance, with progress that updates as you check things off.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'checklist/3_vendor.svg',    'title' => __( 'Let\'s keep planning', 'sdweddingdirectory' ),    'desc' => __( 'Add custom tasks, adjust due dates, and tailor the list to match your unique celebration.', 'sdweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Your wedding at a glance', 'sdweddingdirectory' ),
                'desc'     => __( 'The Checklist dashboard gives you a complete overview of every task, deadline, and milestone so you always know where you stand.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Organized by timeline', 'sdweddingdirectory' ), 'desc' => __( 'Tasks are arranged months, weeks, and days before your wedding so you tackle the right things at the right time.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Progress at a glance', 'sdweddingdirectory' ),  'desc' => __( 'A visual progress bar shows how far along you are and what percentage of tasks are complete.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Your Checklist', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'checklist/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Budget meets checklist', 'sdweddingdirectory' ),
                'desc'     => __( 'Your checklist and budget work together so every task has a cost estimate and nothing surprises you financially.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Linked spending', 'sdweddingdirectory' ),  'desc' => __( 'Each checklist task can connect to a budget line item, keeping finances and planning aligned.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Stay on budget', 'sdweddingdirectory' ),   'desc' => __( 'Flag tasks that push you over budget before you commit, so there are no last-minute surprises.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'View Budget Tool', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'checklist/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Your wedding, your list', 'sdweddingdirectory' ),
                'desc'     => __( 'No two weddings are the same. Customize every detail of your checklist to match your vision.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Add custom tasks', 'sdweddingdirectory' ),   'desc' => __( 'Create tasks for anything unique to your day, from venue walk-throughs to dress fittings.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Adjust dates freely', 'sdweddingdirectory' ), 'desc' => __( 'Move deadlines as plans change, and your timeline updates automatically.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Personalize Checklist', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'checklist/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'When should I start?', 'sdweddingdirectory' ),   'text' => __( 'Most couples begin their checklist 12 to 18 months before the wedding. The earlier you start, the more time you have to compare vendors, secure dates, and avoid last-minute stress.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'What tasks are included?', 'sdweddingdirectory' ), 'text' => __( 'We include everything from booking your venue and choosing a caterer to mailing invitations and confirming your timeline with vendors the week before.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Can I share the list?', 'sdweddingdirectory' ),   'text' => __( 'Yes. Your partner, wedding planner, or family members can view and update the checklist from their own devices so everyone stays in sync.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'How does it connect to other tools?', 'sdweddingdirectory' ), 'text' => __( 'Checklist tasks link to your budget, vendor manager, and guest list. Completing a task in one place updates the others automatically.', 'sdweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sdweddingdirectory' ),
            'desc'    => __( 'Have questions about the Checklist tool? We\'ve got you.', 'sdweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is your Wedding Checklist free?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. The Wedding Checklist is included with your free SD Wedding Directory account, so you can start organizing tasks as soon as you sign up.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'What do I need to put on my Wedding Checklist?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'We provide helpful default items to begin with. If you\'re skipping certain traditions, you can easily edit or add tasks to match your personal plans.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I plan my entire wedding with your Wedding Checklist?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'The Checklist is built to guide your full planning process, and it works even better alongside the budget, guest list, seating chart, and vendor organizer in your dashboard.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does your Wedding Checklist include a timeline?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Default checklist items are organized around your wedding date, with tasks scheduled months, weeks, and days before the celebration.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I print my Wedding Checklist?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'The Checklist is meant to stay editable online, but if you want a paper copy you can use your browser\'s print option whenever you need one.', 'sdweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'wedding-seating-chart' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Seating Chart', 'sdweddingdirectory' ),
        'title'       => __( 'Your wedding seating chart', 'sdweddingdirectory' ),
        'desc'        => __( 'Arrange tables and assign guests with a simple drag-and-drop layout that keeps your reception organized.', 'sdweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'seating-chart/1_seating-chart.svg', 'title' => __( 'Design your layout', 'sdweddingdirectory' ),  'desc' => __( 'Add round, rectangular, or custom tables and drag them into position to match your venue floor plan.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'seating-chart/2_chair.svg',         'title' => __( 'Assign with ease', 'sdweddingdirectory' ),    'desc' => __( 'Drag guest names onto seats and rearrange instantly until every table feels right.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'seating-chart/3_guest-list.svg',    'title' => __( 'Synced with guests', 'sdweddingdirectory' ),  'desc' => __( 'Your seating chart and guest list stay connected, so RSVPs and meal choices update everywhere.', 'sdweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Visualize your reception', 'sdweddingdirectory' ),
                'desc'     => __( 'See your entire venue layout in one view and make sure every guest has a seat before the big day.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Drag-and-drop tables', 'sdweddingdirectory' ), 'desc' => __( 'Position tables anywhere on the floor plan and resize them to match your venue\'s dimensions.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Label everything', 'sdweddingdirectory' ),     'desc' => __( 'Name each table, mark the head table, and add notes for your venue coordinator.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Seating Chart', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'seating-chart/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Guest list integration', 'sdweddingdirectory' ),
                'desc'     => __( 'Your guest list and seating chart share the same data, so changes in one place update the other automatically.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Filter by RSVP', 'sdweddingdirectory' ),  'desc' => __( 'Only show confirmed guests so you can seat real attendees and spot open chairs quickly.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Meal preferences', 'sdweddingdirectory' ), 'desc' => __( 'See dietary needs and meal choices right on the seating view so your caterer has clear counts.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Guest List', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'seating-chart/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Share with your venue', 'sdweddingdirectory' ),
                'desc'     => __( 'Export or share your finalized layout with your venue coordinator so setup goes smoothly.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Print-ready layout', 'sdweddingdirectory' ), 'desc' => __( 'Generate a clean printable version of your seating chart for your venue team and day-of coordinator.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Last-minute changes', 'sdweddingdirectory' ), 'desc' => __( 'Swap seats or move tables right up until the day and everyone sees the latest version.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Open Seating Chart', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'seating-chart/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'When should I start my seating chart?', 'sdweddingdirectory' ), 'text' => __( 'Most couples finalize seating about two to four weeks before the wedding, after RSVPs are in. Start the layout earlier so you have a head start.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'How many guests per table?', 'sdweddingdirectory' ), 'text' => __( 'Round tables typically seat 8 to 10 guests, while rectangular tables can seat 6 to 12 depending on size. Our tool lets you set capacity per table.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Can I share the chart with my planner?', 'sdweddingdirectory' ), 'text' => __( 'Yes. Export the layout or share a link so your coordinator, caterer, and venue team can all see the same plan.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Does it work with my guest list?', 'sdweddingdirectory' ), 'text' => __( 'Your seating chart pulls directly from your guest list. Add a guest in one place and they appear in both, with RSVP status and meal choices included.', 'sdweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sdweddingdirectory' ),
            'desc'    => __( 'Common questions about the Seating Chart tool.', 'sdweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Seating Chart tool free?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I add different table shapes?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Choose from round, rectangular, or custom shapes to match your venue layout.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does it sync with my guest list?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Absolutely. Your guest list and seating chart share the same data, so changes update everywhere.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I print my seating chart?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Export a print-ready version to share with your venue team and day-of coordinator.', 'sdweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'vendor-manager' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Vendor Manager', 'sdweddingdirectory' ),
        'title'       => __( 'Your vendor manager', 'sdweddingdirectory' ),
        'desc'        => __( 'Search, organize, and communicate with wedding vendors all in one place.', 'sdweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'vendor-manager/1_contact-bubble.svg', 'title' => __( 'Reach out with ease', 'sdweddingdirectory' ),  'desc' => __( 'Browse professionals and send messages directly through your SDWeddingDirectory account.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'vendor-manager/2_vendor.png',         'title' => __( 'Keep detailed notes', 'sdweddingdirectory' ),  'desc' => __( 'Store important information and reminders for each vendor so nothing gets forgotten.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'vendor-manager/3_clipboard.png',      'title' => __( 'Save and compare', 'sdweddingdirectory' ),     'desc' => __( 'Bookmark top choices and review pricing and feedback side by side.', 'sdweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'All your vendors, one place', 'sdweddingdirectory' ),
                'desc'     => __( 'Keep contact info, contracts, and conversation history for every vendor organized in a single dashboard.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Quick search', 'sdweddingdirectory' ),     'desc' => __( 'Browse the SD Wedding Directory to find top-rated vendors and add them to your list with one click.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Status tracking', 'sdweddingdirectory' ),  'desc' => __( 'Mark vendors as contacted, booked, or declined so you always know where you stand.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Vendors', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'vendor-manager/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Compare and decide', 'sdweddingdirectory' ),
                'desc'     => __( 'Review pricing, availability, and reviews for shortlisted vendors to make confident hiring decisions.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Side-by-side view', 'sdweddingdirectory' ),  'desc' => __( 'Compare multiple vendors at a glance with key details laid out for easy review.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Notes and ratings', 'sdweddingdirectory' ),  'desc' => __( 'Add private notes and your own ratings to remember first impressions after consultations.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Browse Vendors', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'vendor-manager/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Connected to your budget', 'sdweddingdirectory' ),
                'desc'     => __( 'Link vendor costs to your budget so payments and estimates stay synchronized across tools.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Budget line items', 'sdweddingdirectory' ),  'desc' => __( 'Each booked vendor can be tied to a budget category with estimates and actual costs tracked side by side.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Payment reminders', 'sdweddingdirectory' ),  'desc' => __( 'Set deposit and final payment dates so you never miss a deadline.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'View Budget', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'vendor-manager/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'How many vendors do I need?', 'sdweddingdirectory' ), 'text' => __( 'Most San Diego weddings involve 8 to 15 vendors, from the venue and caterer to a DJ, photographer, and florist. The Vendor Manager helps you keep track of every one.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'When should I start booking?', 'sdweddingdirectory' ), 'text' => __( 'Popular San Diego vendors book 12 to 18 months ahead, especially for peak season. Start researching early and use the Manager to track who you have contacted.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Can I message vendors here?', 'sdweddingdirectory' ), 'text' => __( 'Yes. Send inquiries directly through SD Wedding Directory and all communication stays in your dashboard for easy reference.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Is the Vendor Manager free?', 'sdweddingdirectory' ), 'text' => __( 'Absolutely. It is included with your free SD Wedding Directory account along with all other planning tools.', 'sdweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sdweddingdirectory' ),
            'desc'    => __( 'Common questions about the Vendor Manager.', 'sdweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Vendor Manager free?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I contact vendors through the tool?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Send messages directly from the directory and all conversations are saved in your dashboard.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'How do I compare vendors?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Bookmark your top choices and view them side by side with pricing, reviews, and your own notes.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does it connect to the budget tool?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Link vendor costs to budget categories so payments and estimates stay synchronized.', 'sdweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'wedding-guest-list' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Guest List', 'sdweddingdirectory' ),
        'title'       => __( 'Your wedding guest list', 'sdweddingdirectory' ),
        'desc'        => __( 'Track invitations, RSVPs, meal choices, and event details all in one organized place.', 'sdweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'guest-management/1_add-guest.svg', 'title' => __( 'Build your list', 'sdweddingdirectory' ),    'desc' => __( 'Add guests individually or import a spreadsheet to get started quickly.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'guest-management/2_sort.svg',      'title' => __( 'Track RSVPs', 'sdweddingdirectory' ),        'desc' => __( 'See who has responded, who is attending, and who still needs a follow-up.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'guest-management/3_envelope.svg',  'title' => __( 'Manage details', 'sdweddingdirectory' ),     'desc' => __( 'Record meal preferences, plus-ones, table assignments, and address info for each guest.', 'sdweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Everyone in one place', 'sdweddingdirectory' ),
                'desc'     => __( 'Your complete guest list with RSVP status, contact details, and event assignments all visible at a glance.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Smart filters', 'sdweddingdirectory' ),     'desc' => __( 'Filter by RSVP status, event, meal choice, or group to find exactly who you need.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Guest count totals', 'sdweddingdirectory' ), 'desc' => __( 'See real-time totals for invited, attending, declined, and pending guests.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Guest List', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'guest-management/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Connected to your seating chart', 'sdweddingdirectory' ),
                'desc'     => __( 'Assign guests to tables directly from your guest list and see the seating chart update in real time.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Drag to assign', 'sdweddingdirectory' ),  'desc' => __( 'Move guests between tables with a simple drag-and-drop interaction.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Spot open seats', 'sdweddingdirectory' ), 'desc' => __( 'See which tables still have room and how many unassigned guests remain.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Open Seating Chart', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'guest-management/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Online RSVPs made simple', 'sdweddingdirectory' ),
                'desc'     => __( 'Let guests respond through your wedding website and watch your list update automatically.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Automatic updates', 'sdweddingdirectory' ),  'desc' => __( 'When a guest RSVPs online, their status and meal choice update everywhere instantly.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Email reminders', 'sdweddingdirectory' ),    'desc' => __( 'Send friendly RSVP reminders to guests who have not yet responded.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Guests', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'guest-management/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'How many guests should I invite?', 'sdweddingdirectory' ), 'text' => __( 'San Diego weddings average 100 to 150 guests, but the right number depends on your venue capacity and budget. Use the Guest List tool to experiment with different counts.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'When should I send invitations?', 'sdweddingdirectory' ), 'text' => __( 'Mail invitations six to eight weeks before the wedding, and send save-the-dates six to twelve months ahead. The Guest List tool helps you track who received what.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Can I import my existing list?', 'sdweddingdirectory' ), 'text' => __( 'Yes. Upload a spreadsheet with guest names, emails, and addresses and the tool will populate your list automatically.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Does it work with the wedding website?', 'sdweddingdirectory' ), 'text' => __( 'Yes. Online RSVPs from your wedding website flow directly into your guest list, keeping everything in sync.', 'sdweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sdweddingdirectory' ),
            'desc'    => __( 'Common questions about the Guest List tool.', 'sdweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Guest List tool free?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I track RSVPs online?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Guest responses from your wedding website update your list automatically.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does it connect to the seating chart?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Assign guests to tables directly and both tools stay synchronized.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I export my guest list?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Download your list as a spreadsheet anytime you need a local copy.', 'sdweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'wedding-budget' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Budget Calculator', 'sdweddingdirectory' ),
        'title'       => __( 'Your wedding budget', 'sdweddingdirectory' ),
        'desc'        => __( 'Keep your finances organized and stay in control of every wedding expense.', 'sdweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'budget-calculator/1_calculator.svg', 'title' => __( 'Set your budget', 'sdweddingdirectory' ),     'desc' => __( 'Enter your total budget and break it into categories. We suggest allocations based on San Diego averages.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'budget-calculator/2_deposit.svg',    'title' => __( 'Track payments', 'sdweddingdirectory' ),      'desc' => __( 'Record deposits, final payments, and tips as they happen so your balance is always current.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'budget-calculator/3_cost-graph.svg', 'title' => __( 'See the big picture', 'sdweddingdirectory' ), 'desc' => __( 'Visual charts show where your money is going and how much room you have left in each category.', 'sdweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Budget at a glance', 'sdweddingdirectory' ),
                'desc'     => __( 'A clear dashboard that shows your total budget, amount spent, and remaining balance across every category.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Category breakdown', 'sdweddingdirectory' ),  'desc' => __( 'See how much you have allocated and spent in each area, from venue to flowers to entertainment.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Over-budget alerts', 'sdweddingdirectory' ),  'desc' => __( 'Get notified when a category exceeds its limit so you can adjust before it affects the total.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Your Budget', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'budget-calculator/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Linked to your vendors', 'sdweddingdirectory' ),
                'desc'     => __( 'Connect vendor quotes and contracts to budget line items so estimates and actuals stay in sync.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Estimate vs. actual', 'sdweddingdirectory' ), 'desc' => __( 'Compare quoted prices with what you actually paid to see where you saved or overspent.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Payment schedule', 'sdweddingdirectory' ),    'desc' => __( 'Track deposit dates, final payment deadlines, and outstanding balances for each vendor.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Vendors', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'budget-calculator/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Customize your categories', 'sdweddingdirectory' ),
                'desc'     => __( 'Every wedding is different. Add, rename, or remove budget categories to match your priorities.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Flexible categories', 'sdweddingdirectory' ), 'desc' => __( 'Start with our suggested categories or create your own from scratch.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Adjust on the fly', 'sdweddingdirectory' ),   'desc' => __( 'Move money between categories as plans change and your budget recalculates instantly.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Open Budget Tool', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'budget-calculator/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'What is the average San Diego wedding cost?', 'sdweddingdirectory' ), 'text' => __( 'San Diego weddings typically range from $25,000 to $60,000 depending on guest count, venue, and vendor choices. Our budget tool helps you plan within your means.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'How should I split my budget?', 'sdweddingdirectory' ), 'text' => __( 'A common guideline is 40-50% on venue and catering, 10-15% on entertainment and photography, and the rest split across flowers, attire, stationery, and extras.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Can I track payments here?', 'sdweddingdirectory' ), 'text' => __( 'Yes. Record deposits, installments, and final payments for each vendor. Your remaining balance updates automatically.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Is the Budget Calculator free?', 'sdweddingdirectory' ), 'text' => __( 'Yes. It is included with your free SD Wedding Directory account alongside all other planning tools.', 'sdweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sdweddingdirectory' ),
            'desc'    => __( 'Common questions about the Budget Calculator.', 'sdweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Budget Calculator free?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I customize budget categories?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Add, rename, or remove categories to match your specific wedding plans.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does it connect to the vendor manager?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Link vendor costs to budget line items so estimates and actuals stay synchronized.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I track deposits and payments?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Record deposits, installments, and final payments with due dates for each vendor.', 'sdweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'wedding-website' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Wedding Website', 'sdweddingdirectory' ),
        'title'       => __( 'Your wedding website', 'sdweddingdirectory' ),
        'desc'        => __( 'Build a personalized website to share event details, collect RSVPs, and keep guests informed.', 'sdweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'wedding-website/1_gift.svg',     'title' => __( 'Share your details', 'sdweddingdirectory' ),  'desc' => __( 'Give guests one place to find everything — schedule, location, registry, accommodations, and more.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'wedding-website/2_envelope.svg', 'title' => __( 'Collect RSVPs', 'sdweddingdirectory' ),       'desc' => __( 'Let guests respond online and watch your guest list update automatically in real time.', 'sdweddingdirectory' ) ],
            [ 'icon' => 'wedding-website/3_hotel.svg',    'title' => __( 'Travel info', 'sdweddingdirectory' ),         'desc' => __( 'Share hotel blocks, directions, and local recommendations so out-of-town guests feel welcome.', 'sdweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Launch in minutes', 'sdweddingdirectory' ),
                'desc'     => __( 'Select a design, enter your event details, and publish a beautiful wedding website in just a few steps.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Choose a design', 'sdweddingdirectory' ),  'desc' => __( 'Browse curated templates and pick one that matches your wedding style and color palette.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Add your content', 'sdweddingdirectory' ), 'desc' => __( 'Fill in your story, event schedule, registry links, and photo gallery with a simple editor.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Your Website', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'wedding-website/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'RSVPs and guest list in sync', 'sdweddingdirectory' ),
                'desc'     => __( 'When guests RSVP on your website, their responses automatically update your guest list and seating chart.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Real-time updates', 'sdweddingdirectory' ),  'desc' => __( 'No manual entry needed. Guest responses flow directly into your planning dashboard.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Meal preferences', 'sdweddingdirectory' ),   'desc' => __( 'Collect dietary restrictions and meal choices at the same time so your caterer has accurate counts.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Guest List', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'wedding-website/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Everything guests need', 'sdweddingdirectory' ),
                'desc'     => __( 'Share travel info, hotel blocks, local recommendations, and a photo gallery so guests feel prepared and excited.', 'sdweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Registry links', 'sdweddingdirectory' ),       'desc' => __( 'Connect your registry so guests can find your wish list right from your wedding website.', 'sdweddingdirectory' ) ],
                    [ 'title' => __( 'Maps and directions', 'sdweddingdirectory' ),  'desc' => __( 'Embed a map of your venue and add driving or transit directions for out-of-town guests.', 'sdweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Build Your Website', 'sdweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'wedding-website/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'Do I need a wedding website?', 'sdweddingdirectory' ), 'text' => __( 'A wedding website gives guests a single place to find your schedule, RSVP, and learn about your venue. It saves you from answering the same questions over and over.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Can I use my own domain?', 'sdweddingdirectory' ), 'text' => __( 'Your website comes with a free subdomain on SD Wedding Directory. Custom domains may be supported in a future update.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'Is it mobile-friendly?', 'sdweddingdirectory' ), 'text' => __( 'Yes. Every template is fully responsive and looks great on phones, tablets, and desktops so guests can access it anywhere.', 'sdweddingdirectory' ) ],
            [ 'title' => __( 'How long does my site stay live?', 'sdweddingdirectory' ), 'text' => __( 'Your wedding website stays online as long as your SD Wedding Directory account is active, so guests can revisit photos and memories after the big day.', 'sdweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sdweddingdirectory' ),
            'desc'    => __( 'Common questions about the Wedding Website builder.', 'sdweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Wedding Website free?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can guests RSVP on my website?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Online RSVPs update your guest list automatically.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I add a photo gallery?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Upload engagement photos and other images to a beautiful gallery page on your site.', 'sdweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Is it mobile-friendly?', 'sdweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. All templates are fully responsive and look great on any device.', 'sdweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],
];

// ---------------------------------------------------------------------------
// Resolve data for current page
// ---------------------------------------------------------------------------
$data = $child_data[ $current_slug ] ?? null;

if ( ! $data ) {
    // Fallback: render plain content if slug not recognised.
    ?>
    <div class="container section">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </div>
    <?php
    return;
}

// All 6 planning tools — used for the cross-link card row (5 cards, excluding current).
$all_tools = [
    'wedding-checklist'      => [ 'icon' => 'checklist.png',       'title' => __( 'Checklist', 'sdweddingdirectory' ),       'desc' => __( 'A complete planning timeline that keeps every task on track.', 'sdweddingdirectory' ),                    'cta' => __( 'VIEW CHECKLIST', 'sdweddingdirectory' ),       'url' => home_url( '/wedding-planning/wedding-checklist/' ) ],
    'wedding-seating-chart'  => [ 'icon' => 'seating-chart.png',   'title' => __( 'Seating Chart', 'sdweddingdirectory' ),   'desc' => __( 'Arrange tables and assign guests with drag-and-drop ease.', 'sdweddingdirectory' ),                    'cta' => __( 'MANAGE SEATING', 'sdweddingdirectory' ),       'url' => home_url( '/wedding-planning/wedding-seating-chart/' ) ],
    'vendor-manager'         => [ 'icon' => 'vendor-manager.png',  'title' => __( 'Vendor Manager', 'sdweddingdirectory' ),  'desc' => __( 'Search, organize, and communicate with vendors in one place.', 'sdweddingdirectory' ),                  'cta' => __( 'MANAGE VENDORS', 'sdweddingdirectory' ),       'url' => home_url( '/wedding-planning/vendor-manager/' ) ],
    'wedding-guest-list'     => [ 'icon' => 'guest-list.png',      'title' => __( 'Guest List', 'sdweddingdirectory' ),      'desc' => __( 'Track invitations, RSVPs, and event details in one organized place.', 'sdweddingdirectory' ),            'cta' => __( 'EDIT GUEST LIST', 'sdweddingdirectory' ),      'url' => home_url( '/wedding-planning/wedding-guest-list/' ) ],
    'wedding-budget'         => [ 'icon' => 'budget.png',          'title' => __( 'Budget', 'sdweddingdirectory' ),          'desc' => __( 'Keep finances organized and stay in control of every wedding expense.', 'sdweddingdirectory' ),           'cta' => __( 'VIEW BUDGET', 'sdweddingdirectory' ),          'url' => home_url( '/wedding-planning/wedding-budget/' ) ],
    'wedding-website'        => [ 'icon' => 'wedding-website.png', 'title' => __( 'Wedding Website', 'sdweddingdirectory' ), 'desc' => __( 'Build a personalized website to share details and collect RSVPs.', 'sdweddingdirectory' ),               'cta' => __( 'BUILD WEBSITE', 'sdweddingdirectory' ),        'url' => home_url( '/wedding-planning/wedding-website/' ) ],
];

// Remove current tool from the card row.
$other_tools = $all_tools;
unset( $other_tools[ $current_slug ] );
?>

<!-- 1. Scroll-triggered sticky CTA bar (hidden until scroll) -->
<div class="planning-scroll-cta" id="planning-scroll-cta" aria-hidden="true">
    <div class="container planning-scroll-cta__inner">
        <span class="planning-scroll-cta__label"><?php echo esc_html( get_the_title() ); ?></span>
        <a class="planning-scroll-cta__btn" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>">
            <?php esc_html_e( "Sign Up. It's free!", 'sdweddingdirectory' ); ?>
        </a>
    </div>
</div>

<!-- 2. Two-step Signup Form -->
<?php get_template_part( 'template-parts/planning/planning-hero' ); ?>

<!-- 3. Section Title -->
<section class="planning-child-intro section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/section-title', null, [
            'heading' => $data['title'],
            'desc'    => $data['desc'],
            'align'   => 'center',
        ] );
        ?>
    </div>
</section>

<!-- 4. Icon Card Row (3 colored SVG icons) -->
<section class="planning-child-icons section">
    <div class="container">
        <div class="planning-child-icons__grid grid grid--3col">
            <?php foreach ( $data['icon_cards'] as $card ) : ?>
                <div class="planning-child-tool-card">
                    <img class="planning-child-tool-card__icon" src="<?php echo esc_url( $theme_uri . '/assets/images/planning/' . $card['icon'] ); ?>" alt="" loading="lazy">
                    <h3 class="planning-child-tool-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                    <p class="planning-child-tool-card__desc"><?php echo esc_html( $card['desc'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. Feature Blocks (3, alternating: right, left, right) -->
<?php
$feature_sizes = [
    [ 'width' => 400, 'height' => 400 ],
    [ 'width' => 440, 'height' => 330 ],
    [ 'width' => 400, 'height' => 210 ],
];
foreach ( $data['features'] as $i => $feature ) :
    $size = $feature_sizes[ $i ] ?? $feature_sizes[0];
    ?>
    <section class="planning-feature-section section">
        <div class="container">
            <?php
            get_template_part( 'template-parts/components/feature-block', null, [
                'heading'      => $feature['heading'],
                'desc'         => $feature['desc'],
                'sections'     => $feature['sections'],
                'cta_text'     => $feature['cta_text'],
                'cta_url'      => $feature['cta_url'],
                'image_url'    => $theme_uri . '/assets/images/planning/' . $feature['image'],
                'image_alt'    => $feature['heading'],
                'image_width'  => $size['width'],
                'image_height' => $size['height'],
                'reversed'     => $feature['reversed'],
            ] );
            ?>
        </div>
    </section>
<?php endforeach; ?>

<!-- 6. Tool Cards — section title + 5 cards (3 top row, 2 bottom row) -->
<section class="planning-secondary-intro section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/section-title', null, [
            'heading' => __( 'Start free and keep every planning detail connected.', 'sdweddingdirectory' ),
            'desc'    => __( 'Sign up for a free SDWeddingDirectory account to organize your plans in one place. As you build your Wedding Website, it is the perfect time to map out your Guest List.', 'sdweddingdirectory' ),
            'align'   => 'center',
        ] );
        ?>
    </div>
</section>

<section class="planning-tool-cards section">
    <div class="container">
        <?php
        $tool_cards = [];
        foreach ( $other_tools as $tool ) {
            $tool_cards[] = [
                'icon_url'  => $theme_uri . '/assets/images/icons/planning/' . $tool['icon'],
                'title'     => $tool['title'],
                'desc'      => $tool['desc'],
                'cta_label' => $tool['cta'],
                'cta_url'   => $tool['url'],
            ];
        }
        get_template_part( 'template-parts/components/tool-card-row', null, [
            'columns' => 3,
            'cards'   => $tool_cards,
        ] );
        ?>
    </div>
</section>

<!-- 7. Two-column Text Row -->
<section class="planning-detailed-copy section" aria-label="<?php echo esc_attr( $data['title'] ); ?>">
    <div class="container">
        <div class="planning-detailed-copy__grid">
            <?php
            $copy_chunks = array_chunk( $data['detailed_copy'], 2 );
            foreach ( $copy_chunks as $column ) :
                ?>
                <div class="planning-detailed-copy__column">
                    <?php foreach ( $column as $block ) : ?>
                        <div class="planning-detailed-copy__block">
                            <h3 class="planning-detailed-copy__title"><?php echo esc_html( $block['title'] ); ?></h3>
                            <p class="planning-detailed-copy__text"><?php echo esc_html( $block['text'] ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 8. FAQ -->
<?php
get_template_part( 'template-parts/components/faq-section', null, [
    'heading'   => $data['faq']['heading'],
    'desc'      => $data['faq']['desc'],
    'align'     => 'left',
    'id_prefix' => $current_slug . '-faq',
    'open'      => 1,
    'items'     => $data['faq']['items'],
] );
?>

<script>
(function () {
    var bar = document.getElementById('planning-scroll-cta');
    if (!bar) return;

    var threshold = 300;
    var visible = false;

    window.addEventListener('scroll', function () {
        var shouldShow = window.scrollY > threshold;
        if (shouldShow === visible) return;
        visible = shouldShow;
        bar.classList.toggle('is-visible', visible);
        bar.setAttribute('aria-hidden', visible ? 'false' : 'true');
    }, { passive: true });
})();
</script>
<?php
