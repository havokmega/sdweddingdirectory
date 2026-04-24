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
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Checklist', 'sandiegoweddingdirectory' ),
        'title'       => __( 'Your wedding checklist', 'sandiegoweddingdirectory' ),
        'desc'        => __( 'A personalized planning timeline that keeps every task on track from engagement to the big day.', 'sandiegoweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'checklist/1_clipboard.svg', 'title' => __( 'Discover how to start', 'sandiegoweddingdirectory' ),   'desc' => __( 'Start with our recommended checklist, pre-filled with essential tasks organized by your wedding date.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'checklist/2_timeline.svg',  'title' => __( 'Track your progress', 'sandiegoweddingdirectory' ),     'desc' => __( 'See what is done and what is next at a glance, with progress that updates as you check things off.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'checklist/3_vendor.svg',    'title' => __( 'Let\'s keep planning', 'sandiegoweddingdirectory' ),    'desc' => __( 'Add custom tasks, adjust due dates, and tailor the list to match your unique celebration.', 'sandiegoweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Your wedding at a glance', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'The Checklist dashboard gives you a complete overview of every task, deadline, and milestone so you always know where you stand.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Organized by timeline', 'sandiegoweddingdirectory' ), 'desc' => __( 'Tasks are arranged months, weeks, and days before your wedding so you tackle the right things at the right time.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Progress at a glance', 'sandiegoweddingdirectory' ),  'desc' => __( 'A visual progress bar shows how far along you are and what percentage of tasks are complete.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Your Checklist', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'checklist/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Budget meets checklist', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Your checklist and budget work together so every task has a cost estimate and nothing surprises you financially.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Linked spending', 'sandiegoweddingdirectory' ),  'desc' => __( 'Each checklist task can connect to a budget line item, keeping finances and planning aligned.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Stay on budget', 'sandiegoweddingdirectory' ),   'desc' => __( 'Flag tasks that push you over budget before you commit, so there are no last-minute surprises.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'View Budget Tool', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'checklist/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Your wedding, your list', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'No two weddings are the same. Customize every detail of your checklist to match your vision.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Add custom tasks', 'sandiegoweddingdirectory' ),   'desc' => __( 'Create tasks for anything unique to your day, from venue walk-throughs to dress fittings.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Adjust dates freely', 'sandiegoweddingdirectory' ), 'desc' => __( 'Move deadlines as plans change, and your timeline updates automatically.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Personalize Checklist', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'checklist/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'When should I start?', 'sandiegoweddingdirectory' ),   'text' => __( 'Most couples begin their checklist 12 to 18 months before the wedding. The earlier you start, the more time you have to compare vendors, secure dates, and avoid last-minute stress.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'What tasks are included?', 'sandiegoweddingdirectory' ), 'text' => __( 'We include everything from booking your venue and choosing a caterer to mailing invitations and confirming your timeline with vendors the week before.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Can I share the list?', 'sandiegoweddingdirectory' ),   'text' => __( 'Yes. Your partner, wedding planner, or family members can view and update the checklist from their own devices so everyone stays in sync.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'How does it connect to other tools?', 'sandiegoweddingdirectory' ), 'text' => __( 'Checklist tasks link to your budget, vendor manager, and guest list. Completing a task in one place updates the others automatically.', 'sandiegoweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sandiegoweddingdirectory' ),
            'desc'    => __( 'Have questions about the Checklist tool? We\'ve got you.', 'sandiegoweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is your Wedding Checklist free?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. The Wedding Checklist is included with your free SD Wedding Directory account, so you can start organizing tasks as soon as you sign up.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'What do I need to put on my Wedding Checklist?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'We provide helpful default items to begin with. If you\'re skipping certain traditions, you can easily edit or add tasks to match your personal plans.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I plan my entire wedding with your Wedding Checklist?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'The Checklist is built to guide your full planning process, and it works even better alongside the budget, guest list, seating chart, and vendor organizer in your dashboard.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does your Wedding Checklist include a timeline?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Default checklist items are organized around your wedding date, with tasks scheduled months, weeks, and days before the celebration.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I print my Wedding Checklist?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'The Checklist is meant to stay editable online, but if you want a paper copy you can use your browser\'s print option whenever you need one.', 'sandiegoweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'wedding-seating-chart' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Seating Chart', 'sandiegoweddingdirectory' ),
        'title'       => __( 'Your wedding seating chart', 'sandiegoweddingdirectory' ),
        'desc'        => __( 'Arrange tables and assign guests with a simple drag-and-drop layout that keeps your reception organized.', 'sandiegoweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'seating-chart/1_seating-chart.svg', 'title' => __( 'Design your layout', 'sandiegoweddingdirectory' ),  'desc' => __( 'Add round, rectangular, or custom tables and drag them into position to match your venue floor plan.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'seating-chart/2_chair.svg',         'title' => __( 'Assign with ease', 'sandiegoweddingdirectory' ),    'desc' => __( 'Drag guest names onto seats and rearrange instantly until every table feels right.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'seating-chart/3_guest-list.svg',    'title' => __( 'Synced with guests', 'sandiegoweddingdirectory' ),  'desc' => __( 'Your seating chart and guest list stay connected, so RSVPs and meal choices update everywhere.', 'sandiegoweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Visualize your reception', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'See your entire venue layout in one view and make sure every guest has a seat before the big day.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Drag-and-drop tables', 'sandiegoweddingdirectory' ), 'desc' => __( 'Position tables anywhere on the floor plan and resize them to match your venue\'s dimensions.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Label everything', 'sandiegoweddingdirectory' ),     'desc' => __( 'Name each table, mark the head table, and add notes for your venue coordinator.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Seating Chart', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'seating-chart/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Guest list integration', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Your guest list and seating chart share the same data, so changes in one place update the other automatically.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Filter by RSVP', 'sandiegoweddingdirectory' ),  'desc' => __( 'Only show confirmed guests so you can seat real attendees and spot open chairs quickly.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Meal preferences', 'sandiegoweddingdirectory' ), 'desc' => __( 'See dietary needs and meal choices right on the seating view so your caterer has clear counts.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Guest List', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'seating-chart/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Share with your venue', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Export or share your finalized layout with your venue coordinator so setup goes smoothly.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Print-ready layout', 'sandiegoweddingdirectory' ), 'desc' => __( 'Generate a clean printable version of your seating chart for your venue team and day-of coordinator.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Last-minute changes', 'sandiegoweddingdirectory' ), 'desc' => __( 'Swap seats or move tables right up until the day and everyone sees the latest version.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Open Seating Chart', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'seating-chart/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'When should I start my seating chart?', 'sandiegoweddingdirectory' ), 'text' => __( 'Most couples finalize seating about two to four weeks before the wedding, after RSVPs are in. Start the layout earlier so you have a head start.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'How many guests per table?', 'sandiegoweddingdirectory' ), 'text' => __( 'Round tables typically seat 8 to 10 guests, while rectangular tables can seat 6 to 12 depending on size. Our tool lets you set capacity per table.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Can I share the chart with my planner?', 'sandiegoweddingdirectory' ), 'text' => __( 'Yes. Export the layout or share a link so your coordinator, caterer, and venue team can all see the same plan.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Does it work with my guest list?', 'sandiegoweddingdirectory' ), 'text' => __( 'Your seating chart pulls directly from your guest list. Add a guest in one place and they appear in both, with RSVP status and meal choices included.', 'sandiegoweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sandiegoweddingdirectory' ),
            'desc'    => __( 'Common questions about the Seating Chart tool.', 'sandiegoweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Seating Chart tool free?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I add different table shapes?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Choose from round, rectangular, or custom shapes to match your venue layout.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does it sync with my guest list?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Absolutely. Your guest list and seating chart share the same data, so changes update everywhere.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I print my seating chart?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Export a print-ready version to share with your venue team and day-of coordinator.', 'sandiegoweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'vendor-manager' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Vendor Manager', 'sandiegoweddingdirectory' ),
        'title'       => __( 'Your vendor manager', 'sandiegoweddingdirectory' ),
        'desc'        => __( 'Search, organize, and communicate with wedding vendors all in one place.', 'sandiegoweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'vendor-manager/1_contact-bubble.svg', 'title' => __( 'Reach out with ease', 'sandiegoweddingdirectory' ),  'desc' => __( 'Browse professionals and send messages directly through your SDWeddingDirectory account.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'vendor-manager/2_vendor.png',         'title' => __( 'Keep detailed notes', 'sandiegoweddingdirectory' ),  'desc' => __( 'Store important information and reminders for each vendor so nothing gets forgotten.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'vendor-manager/3_clipboard.png',      'title' => __( 'Save and compare', 'sandiegoweddingdirectory' ),     'desc' => __( 'Bookmark top choices and review pricing and feedback side by side.', 'sandiegoweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'All your vendors, one place', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Keep contact info, contracts, and conversation history for every vendor organized in a single dashboard.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Quick search', 'sandiegoweddingdirectory' ),     'desc' => __( 'Browse the SD Wedding Directory to find top-rated vendors and add them to your list with one click.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Status tracking', 'sandiegoweddingdirectory' ),  'desc' => __( 'Mark vendors as contacted, booked, or declined so you always know where you stand.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Vendors', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'vendor-manager/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Compare and decide', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Review pricing, availability, and reviews for shortlisted vendors to make confident hiring decisions.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Side-by-side view', 'sandiegoweddingdirectory' ),  'desc' => __( 'Compare multiple vendors at a glance with key details laid out for easy review.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Notes and ratings', 'sandiegoweddingdirectory' ),  'desc' => __( 'Add private notes and your own ratings to remember first impressions after consultations.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Browse Vendors', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'vendor-manager/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Connected to your budget', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Link vendor costs to your budget so payments and estimates stay synchronized across tools.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Budget line items', 'sandiegoweddingdirectory' ),  'desc' => __( 'Each booked vendor can be tied to a budget category with estimates and actual costs tracked side by side.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Payment reminders', 'sandiegoweddingdirectory' ),  'desc' => __( 'Set deposit and final payment dates so you never miss a deadline.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'View Budget', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'vendor-manager/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'How many vendors do I need?', 'sandiegoweddingdirectory' ), 'text' => __( 'Most San Diego weddings involve 8 to 15 vendors, from the venue and caterer to a DJ, photographer, and florist. The Vendor Manager helps you keep track of every one.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'When should I start booking?', 'sandiegoweddingdirectory' ), 'text' => __( 'Popular San Diego vendors book 12 to 18 months ahead, especially for peak season. Start researching early and use the Manager to track who you have contacted.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Can I message vendors here?', 'sandiegoweddingdirectory' ), 'text' => __( 'Yes. Send inquiries directly through SD Wedding Directory and all communication stays in your dashboard for easy reference.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Is the Vendor Manager free?', 'sandiegoweddingdirectory' ), 'text' => __( 'Absolutely. It is included with your free SD Wedding Directory account along with all other planning tools.', 'sandiegoweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sandiegoweddingdirectory' ),
            'desc'    => __( 'Common questions about the Vendor Manager.', 'sandiegoweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Vendor Manager free?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I contact vendors through the tool?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Send messages directly from the directory and all conversations are saved in your dashboard.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'How do I compare vendors?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Bookmark your top choices and view them side by side with pricing, reviews, and your own notes.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does it connect to the budget tool?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Link vendor costs to budget categories so payments and estimates stay synchronized.', 'sandiegoweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'wedding-guest-list' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Guest List', 'sandiegoweddingdirectory' ),
        'title'       => __( 'Your wedding guest list', 'sandiegoweddingdirectory' ),
        'desc'        => __( 'Track invitations, RSVPs, meal choices, and event details all in one organized place.', 'sandiegoweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'guest-management/1_add-guest.svg', 'title' => __( 'Build your list', 'sandiegoweddingdirectory' ),    'desc' => __( 'Add guests individually or import a spreadsheet to get started quickly.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'guest-management/2_sort.svg',      'title' => __( 'Track RSVPs', 'sandiegoweddingdirectory' ),        'desc' => __( 'See who has responded, who is attending, and who still needs a follow-up.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'guest-management/3_envelope.svg',  'title' => __( 'Manage details', 'sandiegoweddingdirectory' ),     'desc' => __( 'Record meal preferences, plus-ones, table assignments, and address info for each guest.', 'sandiegoweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Everyone in one place', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Your complete guest list with RSVP status, contact details, and event assignments all visible at a glance.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Smart filters', 'sandiegoweddingdirectory' ),     'desc' => __( 'Filter by RSVP status, event, meal choice, or group to find exactly who you need.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Guest count totals', 'sandiegoweddingdirectory' ), 'desc' => __( 'See real-time totals for invited, attending, declined, and pending guests.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Guest List', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'guest-management/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Connected to your seating chart', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Assign guests to tables directly from your guest list and see the seating chart update in real time.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Drag to assign', 'sandiegoweddingdirectory' ),  'desc' => __( 'Move guests between tables with a simple drag-and-drop interaction.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Spot open seats', 'sandiegoweddingdirectory' ), 'desc' => __( 'See which tables still have room and how many unassigned guests remain.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Open Seating Chart', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'guest-management/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Online RSVPs made simple', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Let guests respond through your wedding website and watch your list update automatically.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Automatic updates', 'sandiegoweddingdirectory' ),  'desc' => __( 'When a guest RSVPs online, their status and meal choice update everywhere instantly.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Email reminders', 'sandiegoweddingdirectory' ),    'desc' => __( 'Send friendly RSVP reminders to guests who have not yet responded.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Guests', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'guest-management/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'How many guests should I invite?', 'sandiegoweddingdirectory' ), 'text' => __( 'San Diego weddings average 100 to 150 guests, but the right number depends on your venue capacity and budget. Use the Guest List tool to experiment with different counts.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'When should I send invitations?', 'sandiegoweddingdirectory' ), 'text' => __( 'Mail invitations six to eight weeks before the wedding, and send save-the-dates six to twelve months ahead. The Guest List tool helps you track who received what.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Can I import my existing list?', 'sandiegoweddingdirectory' ), 'text' => __( 'Yes. Upload a spreadsheet with guest names, emails, and addresses and the tool will populate your list automatically.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Does it work with the wedding website?', 'sandiegoweddingdirectory' ), 'text' => __( 'Yes. Online RSVPs from your wedding website flow directly into your guest list, keeping everything in sync.', 'sandiegoweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sandiegoweddingdirectory' ),
            'desc'    => __( 'Common questions about the Guest List tool.', 'sandiegoweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Guest List tool free?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I track RSVPs online?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Guest responses from your wedding website update your list automatically.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does it connect to the seating chart?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Assign guests to tables directly and both tools stay synchronized.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I export my guest list?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Download your list as a spreadsheet anytime you need a local copy.', 'sandiegoweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'wedding-budget' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Budget Calculator', 'sandiegoweddingdirectory' ),
        'title'       => __( 'Your wedding budget', 'sandiegoweddingdirectory' ),
        'desc'        => __( 'Keep your finances organized and stay in control of every wedding expense.', 'sandiegoweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'budget-calculator/1_calculator.svg', 'title' => __( 'Set your budget', 'sandiegoweddingdirectory' ),     'desc' => __( 'Enter your total budget and break it into categories. We suggest allocations based on San Diego averages.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'budget-calculator/2_deposit.svg',    'title' => __( 'Track payments', 'sandiegoweddingdirectory' ),      'desc' => __( 'Record deposits, final payments, and tips as they happen so your balance is always current.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'budget-calculator/3_cost-graph.svg', 'title' => __( 'See the big picture', 'sandiegoweddingdirectory' ), 'desc' => __( 'Visual charts show where your money is going and how much room you have left in each category.', 'sandiegoweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Budget at a glance', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'A clear dashboard that shows your total budget, amount spent, and remaining balance across every category.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Category breakdown', 'sandiegoweddingdirectory' ),  'desc' => __( 'See how much you have allocated and spent in each area, from venue to flowers to entertainment.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Over-budget alerts', 'sandiegoweddingdirectory' ),  'desc' => __( 'Get notified when a category exceeds its limit so you can adjust before it affects the total.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Your Budget', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'budget-calculator/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'Linked to your vendors', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Connect vendor quotes and contracts to budget line items so estimates and actuals stay in sync.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Estimate vs. actual', 'sandiegoweddingdirectory' ), 'desc' => __( 'Compare quoted prices with what you actually paid to see where you saved or overspent.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Payment schedule', 'sandiegoweddingdirectory' ),    'desc' => __( 'Track deposit dates, final payment deadlines, and outstanding balances for each vendor.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Vendors', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'budget-calculator/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Customize your categories', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Every wedding is different. Add, rename, or remove budget categories to match your priorities.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Flexible categories', 'sandiegoweddingdirectory' ), 'desc' => __( 'Start with our suggested categories or create your own from scratch.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Adjust on the fly', 'sandiegoweddingdirectory' ),   'desc' => __( 'Move money between categories as plans change and your budget recalculates instantly.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Open Budget Tool', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'budget-calculator/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'What is the average San Diego wedding cost?', 'sandiegoweddingdirectory' ), 'text' => __( 'San Diego weddings typically range from $25,000 to $60,000 depending on guest count, venue, and vendor choices. Our budget tool helps you plan within your means.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'How should I split my budget?', 'sandiegoweddingdirectory' ), 'text' => __( 'A common guideline is 40-50% on venue and catering, 10-15% on entertainment and photography, and the rest split across flowers, attire, stationery, and extras.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Can I track payments here?', 'sandiegoweddingdirectory' ), 'text' => __( 'Yes. Record deposits, installments, and final payments for each vendor. Your remaining balance updates automatically.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Is the Budget Calculator free?', 'sandiegoweddingdirectory' ), 'text' => __( 'Yes. It is included with your free SD Wedding Directory account alongside all other planning tools.', 'sandiegoweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sandiegoweddingdirectory' ),
            'desc'    => __( 'Common questions about the Budget Calculator.', 'sandiegoweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Budget Calculator free?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I customize budget categories?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Add, rename, or remove categories to match your specific wedding plans.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Does it connect to the vendor manager?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Link vendor costs to budget line items so estimates and actuals stay synchronized.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I track deposits and payments?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Record deposits, installments, and final payments with due dates for each vendor.', 'sandiegoweddingdirectory' ) . '</p>' ],
            ],
        ],
    ],

    'wedding-website' => [
        'banner_text' => __( 'Stay on top of every detail with the SDWeddingDirectory Wedding Website', 'sandiegoweddingdirectory' ),
        'title'       => __( 'Your wedding website', 'sandiegoweddingdirectory' ),
        'desc'        => __( 'Build a personalized website to share event details, collect RSVPs, and keep guests informed.', 'sandiegoweddingdirectory' ),
        'icon_cards'  => [
            [ 'icon' => 'wedding-website/1_gift.svg',     'title' => __( 'Share your details', 'sandiegoweddingdirectory' ),  'desc' => __( 'Give guests one place to find everything — schedule, location, registry, accommodations, and more.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'wedding-website/2_envelope.svg', 'title' => __( 'Collect RSVPs', 'sandiegoweddingdirectory' ),       'desc' => __( 'Let guests respond online and watch your guest list update automatically in real time.', 'sandiegoweddingdirectory' ) ],
            [ 'icon' => 'wedding-website/3_hotel.svg',    'title' => __( 'Travel info', 'sandiegoweddingdirectory' ),         'desc' => __( 'Share hotel blocks, directions, and local recommendations so out-of-town guests feel welcome.', 'sandiegoweddingdirectory' ) ],
        ],
        'features' => [
            [
                'heading'  => __( 'Launch in minutes', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Select a design, enter your event details, and publish a beautiful wedding website in just a few steps.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Choose a design', 'sandiegoweddingdirectory' ),  'desc' => __( 'Browse curated templates and pick one that matches your wedding style and color palette.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Add your content', 'sandiegoweddingdirectory' ), 'desc' => __( 'Fill in your story, event schedule, registry links, and photo gallery with a simple editor.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Start Your Website', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'wedding-website/cta-1_400x400.jpg',
                'reversed' => false,
            ],
            [
                'heading'  => __( 'RSVPs and guest list in sync', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'When guests RSVP on your website, their responses automatically update your guest list and seating chart.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Real-time updates', 'sandiegoweddingdirectory' ),  'desc' => __( 'No manual entry needed. Guest responses flow directly into your planning dashboard.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Meal preferences', 'sandiegoweddingdirectory' ),   'desc' => __( 'Collect dietary restrictions and meal choices at the same time so your caterer has accurate counts.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Manage Guest List', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'wedding-website/cta-2_440x330.jpg',
                'reversed' => true,
            ],
            [
                'heading'  => __( 'Everything guests need', 'sandiegoweddingdirectory' ),
                'desc'     => __( 'Share travel info, hotel blocks, local recommendations, and a photo gallery so guests feel prepared and excited.', 'sandiegoweddingdirectory' ),
                'sections' => [
                    [ 'title' => __( 'Registry links', 'sandiegoweddingdirectory' ),       'desc' => __( 'Connect your registry so guests can find your wish list right from your wedding website.', 'sandiegoweddingdirectory' ) ],
                    [ 'title' => __( 'Maps and directions', 'sandiegoweddingdirectory' ),  'desc' => __( 'Embed a map of your venue and add driving or transit directions for out-of-town guests.', 'sandiegoweddingdirectory' ) ],
                ],
                'cta_text' => __( 'Build Your Website', 'sandiegoweddingdirectory' ),
                'cta_url'  => '#sdwd-planning-register-form',
                'image'    => 'wedding-website/cta-3_400x210.jpg',
                'reversed' => false,
            ],
        ],
        'detailed_copy' => [
            [ 'title' => __( 'Do I need a wedding website?', 'sandiegoweddingdirectory' ), 'text' => __( 'A wedding website gives guests a single place to find your schedule, RSVP, and learn about your venue. It saves you from answering the same questions over and over.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Can I use my own domain?', 'sandiegoweddingdirectory' ), 'text' => __( 'Your website comes with a free subdomain on SD Wedding Directory. Custom domains may be supported in a future update.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'Is it mobile-friendly?', 'sandiegoweddingdirectory' ), 'text' => __( 'Yes. Every template is fully responsive and looks great on phones, tablets, and desktops so guests can access it anywhere.', 'sandiegoweddingdirectory' ) ],
            [ 'title' => __( 'How long does my site stay live?', 'sandiegoweddingdirectory' ), 'text' => __( 'Your wedding website stays online as long as your SD Wedding Directory account is active, so guests can revisit photos and memories after the big day.', 'sandiegoweddingdirectory' ) ],
        ],
        'faq' => [
            'heading' => __( 'Frequently Asked Questions', 'sandiegoweddingdirectory' ),
            'desc'    => __( 'Common questions about the Wedding Website builder.', 'sandiegoweddingdirectory' ),
            'items'   => [
                [ 'question' => __( 'Is the Wedding Website free?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. It is included with your free SD Wedding Directory account.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can guests RSVP on my website?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Online RSVPs update your guest list automatically.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Can I add a photo gallery?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. Upload engagement photos and other images to a beautiful gallery page on your site.', 'sandiegoweddingdirectory' ) . '</p>' ],
                [ 'question' => __( 'Is it mobile-friendly?', 'sandiegoweddingdirectory' ), 'answer' => '<p>' . __( 'Yes. All templates are fully responsive and look great on any device.', 'sandiegoweddingdirectory' ) . '</p>' ],
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
    'wedding-checklist'      => [ 'icon' => 'checklist.png',       'title' => __( 'Checklist', 'sandiegoweddingdirectory' ),       'desc' => __( 'A complete planning timeline that keeps every task on track.', 'sandiegoweddingdirectory' ),                    'cta' => __( 'VIEW CHECKLIST', 'sandiegoweddingdirectory' ),       'url' => home_url( '/wedding-planning/wedding-checklist/' ) ],
    'wedding-seating-chart'  => [ 'icon' => 'seating-chart.png',   'title' => __( 'Seating Chart', 'sandiegoweddingdirectory' ),   'desc' => __( 'Arrange tables and assign guests with drag-and-drop ease.', 'sandiegoweddingdirectory' ),                    'cta' => __( 'MANAGE SEATING', 'sandiegoweddingdirectory' ),       'url' => home_url( '/wedding-planning/wedding-seating-chart/' ) ],
    'vendor-manager'         => [ 'icon' => 'vendor-manager.png',  'title' => __( 'Vendor Manager', 'sandiegoweddingdirectory' ),  'desc' => __( 'Search, organize, and communicate with vendors in one place.', 'sandiegoweddingdirectory' ),                  'cta' => __( 'MANAGE VENDORS', 'sandiegoweddingdirectory' ),       'url' => home_url( '/wedding-planning/vendor-manager/' ) ],
    'wedding-guest-list'     => [ 'icon' => 'guest-list.png',      'title' => __( 'Guest List', 'sandiegoweddingdirectory' ),      'desc' => __( 'Track invitations, RSVPs, and event details in one organized place.', 'sandiegoweddingdirectory' ),            'cta' => __( 'EDIT GUEST LIST', 'sandiegoweddingdirectory' ),      'url' => home_url( '/wedding-planning/wedding-guest-list/' ) ],
    'wedding-budget'         => [ 'icon' => 'budget.png',          'title' => __( 'Budget', 'sandiegoweddingdirectory' ),          'desc' => __( 'Keep finances organized and stay in control of every wedding expense.', 'sandiegoweddingdirectory' ),           'cta' => __( 'VIEW BUDGET', 'sandiegoweddingdirectory' ),          'url' => home_url( '/wedding-planning/wedding-budget/' ) ],
    'wedding-website'        => [ 'icon' => 'wedding-website.png', 'title' => __( 'Wedding Website', 'sandiegoweddingdirectory' ), 'desc' => __( 'Build a personalized website to share details and collect RSVPs.', 'sandiegoweddingdirectory' ),               'cta' => __( 'BUILD WEBSITE', 'sandiegoweddingdirectory' ),        'url' => home_url( '/wedding-planning/wedding-website/' ) ],
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
            <?php esc_html_e( "Sign Up. It's free!", 'sandiegoweddingdirectory' ); ?>
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
            'heading' => __( 'Start free and keep every planning detail connected.', 'sandiegoweddingdirectory' ),
            'desc'    => __( 'Sign up for a free SDWeddingDirectory account to organize your plans in one place. As you build your Wedding Website, it is the perfect time to map out your Guest List.', 'sandiegoweddingdirectory' ),
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
