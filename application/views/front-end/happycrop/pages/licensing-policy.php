<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h1><?= !empty($this->lang->line('licensing_policy')) ? $this->lang->line('licensing_policy') : 'Licensing Policy' ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item active"><?= !empty($this->lang->line('licensing_policy')) ? $this->lang->line('licensing_policy') : 'Licensing Policy' ?></li>
            </ol>
        </nav>
    </div>
</section>
<section class="main-content py-5 my-4 bg-white">
    <!--<div class="text-center">
        <h1 class="h4"><?= !empty($this->lang->line('licensing_policy')) ? $this->lang->line('licensing_policy') : 'Licensing Policy' ?></h1>
    </div>-->

    <div class="text-justify">
        <?= $licensing_policy ?>
    </div>
</section>