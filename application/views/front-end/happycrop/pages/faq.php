<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1>FAQ'S</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=!empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home'?></a></li>
                <li class="breadcrumb-item"><a href="#"><?=!empty($this->lang->line('faq')) ? $this->lang->line('faq') : 'FAQ'?></a></li>
            </ol>
        </nav>
    </div>

</section>
<section class="home_faq_sec mt-0" id="faq_sec">
    <div class="main-content">
        <div class="row">
            <div class="home_faq col-md-7">
                <!--<h2 class="h6"><span class="span-color"><?=!empty($this->lang->line('faq')) ? $this->lang->line('faq') : 'FAQ'?></span></h2>-->
                <h3>Welcome to the Happycrop FAQs Page!</h3>
                <p>Below are answers to the most commonly asked questions about our agricultural input B2B digital platform. If you don't find the answer to your question here, please contact us and we'll be happy to assist.</p>
                <div class="accordion-- accordion accordion-bg accordion-gutter-md accordion-border mt-5" id="accordionExample">
                    <?php 
                    $i = 0;
                    foreach($faq['data'] as $row)
                    { 
                        ?>
                        <div class="card">
                            <div class="card-header">
                                <a href="#collapse<?="h-".$row['id']?>" class=" <?php echo ($i==0) ? 'collapse' : 'expand';?>"><?=html_escape($row['question'])?></a>
                            </div>
                            <div id="collapse<?="h-".$row['id']?>" class="card-body <?php echo ($i==0) ? 'expanded' : 'collapsed';?>">
                                <p class="mb-0"><?=html_escape($row['answer'])?></p>
                            </div>
                        </div>
                        <?php /* ?>
                        <div class="card">
                            <div class="card-header" id="<?="h-".$row['id']?>">
                                <h2 class="clearfix mb-0">
                                    <a class="home_faq_btn btn btn-link collapsed" data-toggle="collapse" data-target="#<?="c-".$row['id']?>" aria-expanded="true" aria-controls="collapseone">
                                        <?=html_escape($row['question'])?>
                                        <i class="fa fa-angle-down rotate"></i></a>
                                </h2>
                            </div>
                            <div id="<?="c-".$row['id']?>" class="collapse" aria-labelledby="<?="h-".$row['id']?>" data-parent="#accordionExample">
                                <div class="card-body"><?=html_escape($row['answer'])?></div>
                            </div>
                        </div>
                        <?php */ ?>
                        <?php 
                        $i++;
                    } 
                    ?>
                </div>
            </div>
            <div class="col-md-5">
                <img class="faq_image" src="<?=THEME_ASSETS_URL.'images/faq1.png'?>" alt="faq">
            </div>
        </div>
    </div>
</section>