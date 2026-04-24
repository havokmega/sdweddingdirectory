/**
 * Claim Business — shared handler for single-vendor.php + single-venue.php.
 * Posts to sdwd_submit_claim (handler in sdwd-core/includes/claim.php).
 * Localized config lives on window.sdwd_claim = { url, nonce }.
 */
( function () {
    var btn = document.getElementById( 'sdwd-claim-btn' );
    if ( ! btn ) return;

    var form   = document.getElementById( 'sdwd-claim-form' );
    var submit = document.getElementById( 'sdwd-claim-submit' );
    var msg    = document.getElementById( 'sdwd-claim-msg' );
    var status = document.getElementById( 'sdwd-claim-status' );
    var config = window.sdwd_claim || {};

    if ( ! form || ! submit || ! msg || ! status || ! config.url || ! config.nonce ) return;

    btn.addEventListener( 'click', function () {
        btn.hidden = true;
        form.hidden = false;
    } );

    submit.addEventListener( 'click', function () {
        var postId = parseInt( btn.getAttribute( 'data-post-id' ), 10 ) || 0;
        if ( ! postId ) {
            status.textContent = '';
            return;
        }

        status.textContent = '';
        submit.disabled = true;

        var body = new FormData();
        body.append( 'action',  'sdwd_submit_claim' );
        body.append( 'nonce',   config.nonce );
        body.append( 'post_id', postId );
        body.append( 'message', msg.value );

        fetch( config.url, { method: 'POST', credentials: 'same-origin', body: body } )
            .then( function ( r ) { return r.json(); } )
            .then( function ( res ) {
                var text = ( res && res.data && res.data.message ) ? res.data.message : '';
                status.textContent = text;
                if ( ! ( res && res.success ) ) {
                    submit.disabled = false;
                }
            } )
            .catch( function () {
                status.textContent = '';
                submit.disabled = false;
            } );
    } );
} )();
