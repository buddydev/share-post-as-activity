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
                 $this.after( '<a href="' + resp.data.activity_url +'">' + resp.data.check_label +'</a>' );
             }
        }, 'json');

        return false;
    } );
});