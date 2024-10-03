<?php $treatment = get_field('treatment', 'option'); 
global $lang;
if( $treatment ):   
?>
    <div class="modal fade popup-social popup-treatment" tabindex="-1" role="dialog" id="popup_treatment">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="animate_line">						
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                    <span class="line line4"></span>						
                    <div class="modal-body">
                        <a href="#" class="close">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.6777 1L1 18.6777M18.6777 18.6777L1 1" stroke="black" stroke-width="2"/>
                            </svg>
                        </a>
                        <div class="inner-content">
                            <div class="header-body desktop">
                                <h5 class="modal-title"><?= $treatment['header'][$lang]; ?></h5>
                            </div>
                            <div class="images">
                                <img src="<?php echo esc_url( $treatment['qr_code']['url'] ); ?>" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>