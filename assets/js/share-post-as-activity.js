/**
 * Javascript file to making ajax request.
 */
jQuery( document ).ready( function ($) {
    $( document ).on( 'click', 'button.share-post-as-activity', function(e){
        e.preventDefault();

        var $this = $( this );

        $.post( ajaxurl, {
            action:      'page_activity_share',
            _ajax_nonce: SHARE_POST_AS_ACTIVITY._nonce,
            share_url:   $this.data( 'shareUrl' ),
            item_id:     $this.data( 'itemId' )
        }, function( resp ){
             $this.text( resp.data.message );

             if (resp.success ) {

                 if( $('a#share-post-as-activity-link').length ) {
                     $('a#share-post-as-activity-link').attr( 'href', resp.data.activity_url ).text( resp.data.check_label );
                 } else {
                     $this.after( '<a id="share-post-as-activity-link" href="' + resp.data.activity_url +'">' + resp.data.check_label +'</a>' );
                 }
             }
        }, 'json');

        return false;
    } );
});