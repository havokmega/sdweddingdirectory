/**
 * Couple Dashboard — interactive behaviors
 *
 * Live countdown, generic AJAX form submit, repeater rows, checklist toggle,
 * budget/guest CRUD modals, password change, real-wedding save, share button,
 * tag pills, wedding-website per-section save, seating add table.
 *
 * All DOM construction goes through el() — no innerHTML, no XSS surface.
 */
( function () {
    'use strict';

    /* DOM helpers ---------------------------------------------------------- */
    function el( tag, attrs, children ) {
        var n = document.createElement( tag );
        if ( attrs ) {
            Object.keys( attrs ).forEach( function ( k ) {
                var v = attrs[ k ];
                if ( v == null || v === false ) { return; }
                if ( k === 'class' )       { n.className   = v; }
                else if ( k === 'text' )   { n.textContent = v; }
                else if ( k === 'html' )   { /* never accept html in helper */ }
                else if ( k === 'value' )  { n.value = v; }
                else if ( k === 'data' )   {
                    Object.keys( v ).forEach( function ( dk ) { n.setAttribute( 'data-' + dk, v[ dk ] ); } );
                }
                else { n.setAttribute( k, v === true ? '' : v ); }
            } );
        }
        ( children || [] ).forEach( function ( c ) {
            if ( c == null ) { return; }
            if ( typeof c === 'string' || typeof c === 'number' ) {
                n.appendChild( document.createTextNode( String( c ) ) );
            } else { n.appendChild( c ); }
        } );
        return n;
    }

    function clearChildren( node ) { while ( node.firstChild ) { node.removeChild( node.firstChild ); } }

    /* AJAX wrapper --------------------------------------------------------- */
    var FORM_MAP = {
        'profile':              { action: 'sdwd_save_dashboard',    nonceKey: 'nonce' },
        'wedding-info':         { action: 'sdwd_save_dashboard',    nonceKey: 'nonce' },
        'social':               { action: 'sdwd_save_dashboard',    nonceKey: 'nonce' },
        'password':             { action: 'sdwd_change_password',   nonceKey: 'password_nonce' },
        'review':               { action: 'sdwd_save_review',       nonceKey: 'review_nonce' },
        'real-wedding-about':   { action: 'sdwd_save_real_wedding', nonceKey: 'real_wedding_nonce' },
        'hours':                { action: 'sdwd_save_hours',        nonceKey: 'hours_nonce' },
        'packages':             { action: 'sdwd_save_packages',     nonceKey: 'packages_nonce' },
        'filters':              { action: 'sdwd_save_filters',      nonceKey: 'filters_nonce' },
        'quote-reply':          { action: 'sdwd_save_quote_reply',  nonceKey: 'quote_reply_nonce' }
    };

    function appendNested( fd, key, value ) {
        if ( Array.isArray( value ) ) {
            value.forEach( function ( v, i ) { appendNested( fd, key + '[' + i + ']', v ); } );
        } else if ( value && typeof value === 'object' ) {
            Object.keys( value ).forEach( function ( k ) { appendNested( fd, key + '[' + k + ']', value[ k ] ); } );
        } else {
            fd.append( key, value == null ? '' : value );
        }
    }

    function ajax( action, nonceKey, data ) {
        if ( typeof sdwd_dash === 'undefined' ) { return Promise.reject( 'no globals' ); }
        var fd;
        if ( data instanceof FormData ) { fd = data; } else {
            fd = new FormData();
            Object.keys( data || {} ).forEach( function ( k ) { appendNested( fd, k, data[ k ] ); } );
        }
        fd.append( 'action', action );
        fd.append( 'nonce', sdwd_dash[ nonceKey ] );
        return fetch( sdwd_dash.url, { method: 'POST', body: fd, credentials: 'include' } )
            .then( function ( r ) { return r.json(); } );
    }

    function setStatus( form, msg, ok ) {
        var s = form.querySelector( '.cd-form__status' );
        if ( ! s ) { return; }
        s.textContent = msg || '';
        s.className = 'cd-form__status' + ( msg ? ( ok ? ' cd-form__status--ok' : ' cd-form__status--err' ) : '' );
    }

    function flashToast( msg, ok ) {
        var t = el( 'div', { class: 'cd-toast' + ( ok ? ' cd-toast--ok' : ' cd-toast--err' ), text: msg } );
        document.body.appendChild( t );
        setTimeout( function () { t.classList.add( 'cd-toast--show' ); }, 10 );
        setTimeout( function () {
            t.classList.remove( 'cd-toast--show' );
            setTimeout( function () { t.remove(); }, 300 );
        }, 2400 );
    }

    /* Countdown ------------------------------------------------------------ */
    function tick( root, target ) {
        var diff = Math.max( 0, target.getTime() - Date.now() );
        var s = Math.floor( diff / 1000 );
        var d = Math.floor( s / 86400 );
        var h = Math.floor( ( s % 86400 ) / 3600 );
        var m = Math.floor( ( s % 3600 ) / 60 );
        var sec = s % 60;
        root.querySelectorAll( '[data-unit]' ).forEach( function ( n ) {
            var k = n.getAttribute( 'data-unit' );
            var v = k === 'days' ? d : k === 'hours' ? h : k === 'minutes' ? m : k === 'seconds' ? sec : 0;
            n.textContent = String( v ).padStart( 2, '0' );
        } );
    }
    function initCountdowns() {
        document.querySelectorAll( '[data-cd-countdown]' ).forEach( function ( root ) {
            var iso = root.getAttribute( 'data-cd-countdown' );
            if ( ! iso ) { return; }
            var t = new Date( iso );
            if ( isNaN( t.getTime() ) ) { return; }
            tick( root, t );
            setInterval( function () { tick( root, t ); }, 1000 );
        } );
    }

    /* Generic form submit ------------------------------------------------- */
    function handleFormSubmit( e ) {
        var form = e.target.closest( 'form[data-cd-form]' );
        if ( ! form ) { return; }
        e.preventDefault();
        var formType = form.getAttribute( 'data-cd-form' );
        var cfg = FORM_MAP[ formType ] || FORM_MAP[ 'profile' ];
        var btn = form.querySelector( 'button[type="submit"]' );
        var orig = btn ? btn.textContent : '';
        if ( btn ) { btn.disabled = true; btn.textContent = 'Saving…'; }
        setStatus( form, '', true );
        ajax( cfg.action, cfg.nonceKey, new FormData( form ) )
            .then( function ( res ) {
                if ( res && res.success ) { setStatus( form, ( res.data && res.data.message ) || 'Saved.', true ); }
                else { setStatus( form, ( res && res.data && res.data.message ) || 'Could not save.', false ); }
            } )
            .catch( function () { setStatus( form, 'Network error.', false ); } )
            .finally( function () { if ( btn ) { btn.disabled = false; btn.textContent = orig; } } );
    }

    /* Repeater rows ------------------------------------------------------- */
    function reindexRepeater( c ) {
        c.querySelectorAll( '.cd-repeater__row' ).forEach( function ( row, i ) {
            row.querySelectorAll( '[name]' ).forEach( function ( elx ) {
                elx.setAttribute( 'name', elx.getAttribute( 'name' ).replace( /\[\d+\]/, '[' + i + ']' ) );
            } );
        } );
    }
    function handleRepeaterClick( e ) {
        var addBtn = e.target.closest( '[data-cd-add]' );
        if ( addBtn ) {
            var key = addBtn.getAttribute( 'data-cd-add' );
            var c = document.querySelector( '[data-cd-repeater="' + key + '"]' );
            if ( ! c ) { return; }
            var rows = c.querySelectorAll( '.cd-repeater__row' );
            var template = rows[ rows.length - 1 ];
            if ( ! template ) { return; }
            var clone = template.cloneNode( true );
            clone.querySelectorAll( 'input, select, textarea' ).forEach( function ( elx ) { elx.value = ''; } );
            c.appendChild( clone );
            reindexRepeater( c );
            return;
        }
        var rm = e.target.closest( '.cd-repeater__remove' );
        if ( rm ) {
            var row = rm.closest( '.cd-repeater__row' );
            var c2 = row && row.parentElement;
            if ( ! c2 ) { return; }
            if ( c2.querySelectorAll( '.cd-repeater__row' ).length > 1 ) { row.remove(); reindexRepeater( c2 ); }
            else { row.querySelectorAll( 'input, select, textarea' ).forEach( function ( elx ) { elx.value = ''; } ); }
        }
    }

    /* Modal helper -------------------------------------------------------- */
    /**
     * fields: array of { name, label, type:'text'|'number'|'select', value, options:[{value,label}], required, min, max, step, placeholder }
     */
    function openModal( title, fields, onSubmit ) {
        var overlay = el( 'div', { class: 'cd-modal__overlay' } );
        var dialog  = el( 'div', { class: 'cd-modal', role: 'dialog', 'aria-modal': 'true' } );
        var head    = el( 'header', { class: 'cd-modal__head' }, [ el( 'h2', { text: title } ) ] );
        var closeBtn = el( 'button', { type: 'button', class: 'cd-modal__close', 'aria-label': 'Close' } );
        closeBtn.appendChild( document.createTextNode( '×' ) );
        head.appendChild( closeBtn );
        var body = el( 'form', { class: 'cd-modal__body' } );

        fields.forEach( function ( f ) {
            var wrap = el( 'label' );
            wrap.appendChild( el( 'span', { class: 'cd-modal__label', text: f.label } ) );
            var input;
            if ( f.type === 'select' ) {
                input = el( 'select', { name: f.name } );
                ( f.options || [] ).forEach( function ( opt ) {
                    var o = el( 'option', { value: opt.value, text: opt.label } );
                    if ( ( f.value || '' ) === opt.value ) { o.selected = true; }
                    input.appendChild( o );
                } );
            } else if ( f.type === 'textarea' ) {
                input = el( 'textarea', { name: f.name, rows: f.rows || 4, placeholder: f.placeholder || '' } );
                if ( f.value ) { input.value = f.value; }
            } else {
                var attrs = { type: f.type || 'text', name: f.name, placeholder: f.placeholder || '' };
                if ( f.required ) { attrs.required = true; }
                if ( f.min      != null ) { attrs.min = f.min; }
                if ( f.max      != null ) { attrs.max = f.max; }
                if ( f.step     != null ) { attrs.step = f.step; }
                input = el( 'input', attrs );
                if ( f.value != null ) { input.value = f.value; }
            }
            wrap.appendChild( input );
            body.appendChild( wrap );
        } );

        var foot = el( 'footer', { class: 'cd-modal__foot' } );
        var cancel = el( 'button', { type: 'button', class: 'btn btn--ghost', text: 'Cancel' } );
        var submit = el( 'button', { type: 'button', class: 'btn btn--primary', text: 'Save' } );
        foot.appendChild( cancel );
        foot.appendChild( submit );

        dialog.appendChild( head );
        dialog.appendChild( body );
        dialog.appendChild( foot );
        overlay.appendChild( dialog );
        document.body.appendChild( overlay );
        setTimeout( function () { overlay.classList.add( 'cd-modal__overlay--show' ); }, 10 );

        function close() {
            overlay.classList.remove( 'cd-modal__overlay--show' );
            setTimeout( function () { overlay.remove(); }, 200 );
        }
        closeBtn.addEventListener( 'click', close );
        cancel.addEventListener( 'click', close );
        overlay.addEventListener( 'click', function ( e ) { if ( e.target === overlay ) { close(); } } );

        function commit() {
            var data = {};
            body.querySelectorAll( '[name]' ).forEach( function ( elx ) { data[ elx.name ] = elx.value; } );
            Promise.resolve( onSubmit( data ) ).then( function ( ok ) { if ( ok !== false ) { close(); } } );
        }
        submit.addEventListener( 'click', commit );
        body.addEventListener( 'submit', function ( e ) { e.preventDefault(); commit(); } );

        var first = body.querySelector( 'input, select, textarea' );
        if ( first ) { first.focus(); }
    }

    /* Checklist ----------------------------------------------------------- */
    function readChecklistFromDOM() {
        var items = [];
        document.querySelectorAll( '.cd-checklist__item' ).forEach( function ( li ) {
            var cb = li.querySelector( '[data-cd-task]' );
            var txt = li.querySelector( '.cd-checklist__text' );
            if ( ! cb || ! txt ) { return; }
            var due = li.querySelector( '.cd-checklist__due' );
            items.push( { id: cb.getAttribute( 'data-cd-task' ), text: txt.textContent.trim(), completed: cb.checked, due_date: due ? due.textContent : '' } );
        } );
        return items;
    }
    function refreshChecklistProgress() {
        var total = document.querySelectorAll( '.cd-checklist__item' ).length;
        var done  = document.querySelectorAll( '.cd-checklist__item--done' ).length;
        var pct   = total > 0 ? Math.round( ( done / total ) * 100 ) : 0;
        var fill  = document.querySelector( '.cd-checklist__fill' );
        if ( fill ) { fill.style.width = pct + '%'; }
        var bar   = document.querySelector( '.cd-checklist__bar' );
        if ( bar ) { bar.setAttribute( 'aria-valuenow', pct ); }
        var p     = document.querySelector( '.cd-checklist__percent' );
        if ( p )   { p.textContent = pct + '%'; }
        var c     = document.querySelector( '.cd-checklist__count' );
        if ( c )   { c.textContent = done + ' of ' + total + ' completed'; }
    }
    function saveChecklist( showToast ) {
        return ajax( 'sdwd_save_checklist', 'checklist_nonce', { items: readChecklistFromDOM() } )
            .then( function ( res ) { if ( showToast ) { flashToast( res && res.success ? 'Saved' : 'Save failed', !! ( res && res.success ) ); } refreshChecklistProgress(); } );
    }
    function handleChecklistChange( e ) {
        var cb = e.target.closest( '[data-cd-task]' );
        if ( ! cb ) { return; }
        var li = cb.closest( '.cd-checklist__item' );
        if ( li ) { li.classList.toggle( 'cd-checklist__item--done', cb.checked ); }
        saveChecklist( false );
    }
    function handleAddTask( e ) {
        if ( ! e.target.closest( '[data-cd-add-task]' ) ) { return; }
        openModal( 'Add Task', [
            { name: 'text', label: 'Task', required: true }
        ], function ( data ) {
            if ( ! data.text || ! data.text.trim() ) { return false; }
            var groups = document.querySelector( '.cd-checklist__groups' );
            if ( ! groups ) { return; }
            var firstList = groups.querySelector( '.cd-checklist__list' );
            if ( ! firstList ) { return; }
            var li = el( 'li', { class: 'cd-checklist__item' } );
            var label = el( 'label', { class: 'cd-checklist__row' } );
            label.appendChild( el( 'input', { type: 'checkbox', 'data-cd-task': 't' + Date.now() } ) );
            label.appendChild( el( 'span', { class: 'cd-checklist__text', text: data.text.trim() } ) );
            li.appendChild( label );
            firstList.appendChild( li );
            saveChecklist( true );
        } );
    }

    /* Budget -------------------------------------------------------------- */
    function readBudgetFromDOM() {
        var items = [];
        document.querySelectorAll( '[data-cd-budget-row]' ).forEach( function ( tr ) {
            var tds = tr.querySelectorAll( 'td' );
            if ( tds.length < 3 ) { return; }
            var amt = String( tds[ 2 ].textContent || '' ).replace( /[^0-9.]/g, '' );
            items.push( {
                category: tds[ 0 ].textContent.trim(),
                vendor:   tds[ 1 ].textContent.trim() === '—' ? '' : tds[ 1 ].textContent.trim(),
                amount:   parseFloat( amt ) || 0
            } );
        } );
        return items;
    }
    function readBudgetTotal() {
        var t = document.querySelector( '.cd-budget__stats .cd-budget__stat:first-child .cd-budget__stat-num' );
        return t ? ( parseFloat( t.textContent.replace( /[^0-9.]/g, '' ) ) || 0 ) : 0;
    }
    function saveBudget() {
        return ajax( 'sdwd_save_budget', 'budget_nonce', { total_budget: readBudgetTotal(), items: readBudgetFromDOM() } );
    }
    function setRowFromData( tr, d ) {
        var tds = tr.querySelectorAll( 'td' );
        tds[ 0 ].textContent = d.category;
        tds[ 1 ].textContent = d.vendor || '—';
        tds[ 2 ].textContent = '$' + ( parseFloat( d.amount ) || 0 ).toLocaleString();
    }
    function handleBudgetClicks( e ) {
        if ( e.target.closest( '[data-cd-add-budget-item]' ) ) {
            openModal( 'Add Budget Item', [
                { name: 'category', label: 'Category', required: true },
                { name: 'vendor',   label: 'Vendor (optional)' },
                { name: 'amount',   label: 'Amount', type: 'number', min: 0, step: 1, required: true }
            ], function ( data ) {
                if ( ! data.category ) { return false; }
                saveBudget().then( function () { flashToast( 'Item added', true ); location.reload(); } );
                // Optimistic add (replaced on reload, but keeps user feedback responsive)
                var tbody = document.querySelector( '.cd-budget__table tbody' );
                if ( ! tbody ) { return; }
                var emptyTr = tbody.querySelector( '.cd-budget__empty' );
                if ( emptyTr ) { emptyTr.closest( 'tr' ).remove(); }
                var idx = tbody.querySelectorAll( 'tr' ).length;
                var tr = el( 'tr', { 'data-cd-budget-row': idx } );
                tr.appendChild( el( 'td', { text: data.category } ) );
                tr.appendChild( el( 'td', { text: data.vendor || '—' } ) );
                tr.appendChild( el( 'td', { class: 'cd-budget__col-amt', text: '$' + ( parseFloat( data.amount ) || 0 ).toLocaleString() } ) );
                var actions = el( 'td', { class: 'cd-budget__col-actions' } );
                actions.appendChild( el( 'button', { type: 'button', class: 'cd-budget__edit',   text: '✎' } ) );
                actions.appendChild( el( 'button', { type: 'button', class: 'cd-budget__delete', text: '×' } ) );
                tr.appendChild( actions );
                tbody.appendChild( tr );
            } );
            return;
        }
        var del = e.target.closest( '.cd-budget__delete' );
        if ( del ) {
            if ( ! confirm( 'Delete this item?' ) ) { return; }
            del.closest( 'tr' ).remove();
            saveBudget().then( function () { flashToast( 'Deleted', true ); location.reload(); } );
            return;
        }
        var ed = e.target.closest( '.cd-budget__edit' );
        if ( ed ) {
            var tr = ed.closest( 'tr' );
            var tds = tr.querySelectorAll( 'td' );
            var current = {
                category: tds[ 0 ].textContent.trim(),
                vendor:   tds[ 1 ].textContent.trim() === '—' ? '' : tds[ 1 ].textContent.trim(),
                amount:   String( tds[ 2 ].textContent || '' ).replace( /[^0-9.]/g, '' )
            };
            openModal( 'Edit Budget Item', [
                { name: 'category', label: 'Category', value: current.category, required: true },
                { name: 'vendor',   label: 'Vendor', value: current.vendor },
                { name: 'amount',   label: 'Amount', type: 'number', value: current.amount, min: 0, step: 1, required: true }
            ], function ( data ) {
                if ( ! data.category ) { return false; }
                setRowFromData( tr, data );
                saveBudget().then( function () { flashToast( 'Updated', true ); location.reload(); } );
            } );
        }
    }

    /* Guests -------------------------------------------------------------- */
    function readGuestsFromDOM() {
        var guests = [];
        document.querySelectorAll( '.cd-guest-row' ).forEach( function ( tr ) {
            var name = tr.cells[ 0 ] ? tr.cells[ 0 ].textContent.trim() : '';
            var event = tr.getAttribute( 'data-cd-guest-event' ) || '';
            if ( ! event && tr.cells.length >= 3 ) {
                var ev = tr.cells[ 1 ].textContent.trim().toLowerCase();
                event = ev.indexOf( 'rehearsal' ) === 0 ? 'rehearsal'
                      : ev.indexOf( 'shower' )    === 0 ? 'shower'
                      : ev.indexOf( 'dance' )     === 0 ? 'dance'
                      : 'wedding';
            }
            if ( ! event ) {
                event = ( new URLSearchParams( location.search ) ).get( 'event' ) || 'wedding';
            }
            guests.push( { name: name, event: event, status: tr.getAttribute( 'data-cd-guest-status' ) || 'invited' } );
        } );
        return guests;
    }
    function saveGuests() {
        return ajax( 'sdwd_save_guests', 'guests_nonce', { guests: readGuestsFromDOM() } );
    }
    var EVENT_OPTIONS = [
        { value: 'wedding',   label: 'Wedding' },
        { value: 'rehearsal', label: 'Rehearsal Dinner' },
        { value: 'shower',    label: 'Shower' },
        { value: 'dance',     label: 'Dance Party' }
    ];
    var STATUS_OPTIONS = [
        { value: 'invited',  label: 'Invited' },
        { value: 'attended', label: 'Attending' },
        { value: 'declined', label: 'Declined' }
    ];
    function handleGuestClicks( e ) {
        if ( e.target.closest( '[data-cd-add-guest]' ) ) {
            openModal( 'Add Guest', [
                { name: 'name',   label: 'Name', required: true },
                { name: 'event',  label: 'Event',  type: 'select', options: EVENT_OPTIONS,  value: 'wedding' },
                { name: 'status', label: 'Status', type: 'select', options: STATUS_OPTIONS, value: 'invited' }
            ], function ( data ) {
                if ( ! data.name ) { return false; }
                var existing = readGuestsFromDOM();
                existing.push( { name: data.name, event: data.event, status: data.status } );
                return ajax( 'sdwd_save_guests', 'guests_nonce', { guests: existing } )
                    .then( function () { flashToast( 'Guest added', true ); location.reload(); } );
            } );
            return;
        }
        var del = e.target.closest( '.cd-guest-row__delete' );
        if ( del ) {
            if ( ! confirm( 'Delete this guest?' ) ) { return; }
            del.closest( 'tr' ).remove();
            saveGuests().then( function () { flashToast( 'Deleted', true ); } );
            return;
        }
        var ed = e.target.closest( '.cd-guest-row__edit' );
        if ( ed ) {
            var tr = ed.closest( 'tr' );
            var current = { name: tr.cells[ 0 ].textContent.trim(), status: tr.getAttribute( 'data-cd-guest-status' ) || 'invited' };
            openModal( 'Edit Guest', [
                { name: 'name',   label: 'Name', value: current.name, required: true },
                { name: 'status', label: 'Status', type: 'select', options: STATUS_OPTIONS, value: current.status }
            ], function ( data ) {
                if ( ! data.name ) { return false; }
                tr.cells[ 0 ].textContent = data.name;
                tr.setAttribute( 'data-cd-guest-status', data.status );
                var badge = tr.querySelector( '.cd-status-badge' );
                if ( badge ) {
                    badge.className = 'cd-status-badge cd-status-badge--' + data.status;
                    badge.textContent = data.status.charAt( 0 ).toUpperCase() + data.status.slice( 1 );
                }
                saveGuests().then( function () { flashToast( 'Saved', true ); } );
            } );
        }
    }

    /* Share button -------------------------------------------------------- */
    function handleShareClick( e ) {
        if ( ! e.target.closest( '.cd-hero__share' ) ) { return; }
        var url = location.origin + location.pathname;
        var title = ( document.querySelector( '.cd-hero__names' ) || {} ).textContent || 'Our Wedding';
        if ( navigator.share ) { navigator.share( { title: title, url: url } ).catch( function () {} ); }
        else if ( navigator.clipboard ) { navigator.clipboard.writeText( url ).then( function () { flashToast( 'Link copied', true ); } ); }
    }

    /* Tag pills ----------------------------------------------------------- */
    function handleTagPills( e ) {
        var input = e.target.closest( '.cd-tag-pills__input' );
        if ( input && e.type === 'keydown' && e.key === 'Enter' ) {
            e.preventDefault();
            var v = input.value.trim();
            if ( ! v ) { return; }
            var pill = el( 'span', { class: 'cd-tag-pill' } );
            pill.appendChild( document.createTextNode( v + ' ' ) );
            pill.appendChild( el( 'button', { type: 'button', 'aria-label': 'remove', text: '×' } ) );
            pill.appendChild( el( 'input', { type: 'hidden', name: 'rw_tags[]', value: v } ) );
            input.parentElement.insertBefore( pill, input );
            input.value = '';
            return;
        }
        var rm = e.target.closest( '.cd-tag-pill button' );
        if ( rm ) { rm.closest( '.cd-tag-pill' ).remove(); }
    }

    /* Wedding website save ----------------------------------------------- */
    function handleWebsiteSave( e ) {
        if ( ! e.target.closest( '[data-cd-website-save]' ) ) { return; }
        var section = ( new URLSearchParams( location.search ) ).get( 'section' ) || 'header';
        var editor = document.querySelector( '.cd-website__editor' );
        if ( ! editor ) { return; }
        var data = {};
        editor.querySelectorAll( '[name]' ).forEach( function ( elx ) { data[ elx.name ] = elx.value; } );
        ajax( 'sdwd_save_website', 'website_nonce', { section: section, data: data } )
            .then( function ( res ) { flashToast( res && res.success ? 'Section saved' : 'Save failed', !! ( res && res.success ) ); } );
    }

    /* Seating ------------------------------------------------------------- */
    function readTablesFromDOM() {
        var tables = [];
        document.querySelectorAll( '[data-cd-table]' ).forEach( function ( elx ) {
            tables.push( {
                name:  elx.getAttribute( 'data-name' ),
                seats: parseInt( elx.getAttribute( 'data-seats' ), 10 ) || 8,
                shape: elx.getAttribute( 'data-shape' ) || 'round',
                x: parseFloat( elx.style.left ) || 0,
                y: parseFloat( elx.style.top )  || 0
            } );
        } );
        return tables;
    }
    function saveSeating() {
        return ajax( 'sdwd_save_seating', 'seating_nonce', { tables: readTablesFromDOM() } )
            .then( function ( res ) { flashToast( res && res.success ? 'Layout saved' : 'Save failed', !! ( res && res.success ) ); } );
    }
    function handleSeatingClicks( e ) {
        if ( e.target.closest( '[data-cd-seating-add-table]' ) ) {
            openModal( 'Add Table', [
                { name: 'name',  label: 'Name', placeholder: 'Table 1', required: true },
                { name: 'seats', label: 'Seats', type: 'number', min: 1, max: 30, value: 8 },
                { name: 'shape', label: 'Shape', type: 'select', options: [ { value: 'round', label: 'Round' }, { value: 'rectangular', label: 'Rectangular' } ], value: 'round' }
            ], function ( data ) {
                if ( ! data.name ) { return false; }
                var canvas = document.querySelector( '[data-cd-seating-canvas]' );
                if ( ! canvas ) { return; }
                var empty = canvas.querySelector( '.cd-seating__canvas-empty' );
                if ( empty ) { empty.remove(); }
                var t = el( 'div', {
                    class: 'cd-seating__table-vis cd-seating__table-vis--' + data.shape,
                    'data-cd-table': '',
                    'data-name': data.name,
                    'data-seats': data.seats,
                    'data-shape': data.shape
                }, [ el( 'span', { class: 'cd-seating__table-vis-name', text: data.name } ) ] );
                canvas.appendChild( t );
                saveSeating();
            } );
        }
        if ( e.target.closest( '[data-cd-seating-save]' ) ) { saveSeating(); }
    }

    /* Init ---------------------------------------------------------------- */
    function init() {
        initCountdowns();
        document.addEventListener( 'submit',  handleFormSubmit );
        document.addEventListener( 'click',   handleRepeaterClick );
        document.addEventListener( 'change',  handleChecklistChange );
        document.addEventListener( 'click',   handleAddTask );
        document.addEventListener( 'click',   handleBudgetClicks );
        document.addEventListener( 'click',   handleGuestClicks );
        document.addEventListener( 'click',   handleShareClick );
        document.addEventListener( 'keydown', handleTagPills );
        document.addEventListener( 'click',   handleTagPills );
        document.addEventListener( 'click',   handleWebsiteSave );
        document.addEventListener( 'click',   handleSeatingClicks );
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }
} )();
