<?php
    /* Template Name: Contact */
    $template_uri = get_template_directory_uri();
    // Please copy this template to add modal content
    // get_template_part( 'template-parts/modal', 'your-content' );

?>
<!-- Button trigger modal -->
<button  data-bs-toggle="modal" class="btn-design" data-bs-target="#feature-modal">
    <img class="w-100 header-logo" src="<?php echo $template_uri; ?>/img/company-logo.jpg">
</button>
    

<!-- Modal" -->
<div class="modal no-scroll fade" id="feature-modal"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-design">
            <div class="modal-body modal-body-design">
                <button type="button" class="round_btn close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                <img class="w-100" src="<?php echo  $template_uri; ?>/img/company-logo.jpg" alt="">
                <div class="container modal-container">
                    <div class="row justify-content-center mt-3 mb-3 mincho text-center">
                        Webを熟知する確かな診断と技術で<br>
                        お客様のビジネスの成功を共に考えます
                    </div>
                    <div class="row justify-content-center mt-5 fs-4 letter-space">
                        当社の特徴
                    </div>
                    <div class="feature mt-5">
                        <p class="title">丁寧なカウンセリング</p>
                        <p>引続き将来を記憶観は幾分その講演でなかもにありてありますでは努力なれでんて、そうにもやっないでたいた。在来に踏みですのはけっして今でまあならましだ。</p>
                    </div>
                    
                    <div class="feature mt-5">
                        <p class="title">透明化</p>
                        <p>引続き将来を記憶観は幾分その講演でなかもにありてありますでは努力なれでんて、そうにもやっないでたいた。在来に踏みですのはけっして今でまあならましだ。</p>
                    </div>
                    
                    <div class="feature mt-5">
                        <p class="title">技術力</p>
                        <p>引続き将来を記憶観は幾分その講演でなかもにありてありますでは努力なれでんて、そうにもやっないでたいた。在来に踏みですのはけっして今でまあならましだ。</p>
                    </div>
                    
                    <div class="row justify-content-center fs-5 msg my-5">
                        スタッフ一同お待ちしております。
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>