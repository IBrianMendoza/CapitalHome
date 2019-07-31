jQuery(document).ready(function($) {
    jQuery('.theme-install [type="submit"]').on('click', function(){
        var self = jQuery(this);
        jQuery('.theme-install .loading').removeClass('hidden');
        setTimeout(function(){
            self.delay(2000).attr("disabled", true)
        },0)
    })
    
})