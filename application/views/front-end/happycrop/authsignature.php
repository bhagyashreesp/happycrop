<style>
    .signatureimg{
        width: 100%;
        height: 50px;
    }
</style>
<div class="col-lg-12  mt-5">
    <div class="row justify-content-end">
        <div class="col-lg-4 ">
        <p class="pb-2 text-right"><?= $this->config->item('happycrop_name'); ?></p>
        <img src="<?= base_url('assets/front_end/happycrop/images/user.png') ?>" class="signatureimg">
        <p class="py-2 text-right">Authorized Signatory</p>
        </div>
        
    </div>
</div>