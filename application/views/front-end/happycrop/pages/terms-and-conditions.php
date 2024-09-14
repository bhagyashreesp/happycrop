<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?php // !empty($this->lang->line('terms_and_condition')) ? $this->lang->line('terms_and_condition') : 'Terms of Use'; ?>Terms of Use</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item active"><?php //!empty($this->lang->line('terms_and_condition')) ? $this->lang->line('terms_and_condition') : 'Terms of Use'; ?>Terms of Use</li>
            </ol>
        </nav>
    </div>
</section>
<section class="main-content py-5 my-4 bg-white">
    <!--<div class="text-center">
        <h1 class="h4"><?= !empty($this->lang->line('terms_and_condition')) ? $this->lang->line('terms_and_condition') : 'Terms of Use' ?></h1>
    </div>-->

    <div class="text-justify">
        <?= $terms_and_conditions ?>
    </div>
</section>