<style>
    .signatureimg{
        width: 300px;
        height: 200px;
    }
</style>
<div class="col-lg-12  mt-5">
    <div class="row justify-content-end">
        <div class="col-lg-4 ">
        <p class="pb-2 text-right pr-5"><?= $this->config->item('happycrop_name'); ?></p>
        <img src="<?= base_url('assets/signature-img.jpeg') ?>" class="signatureimg">
        <p class="py-2 text-right pr-5">Authorized Signatory</p>
        </div>
        
    </div>
</div>