<style>
    .signatureimg{
        width: 228px;
        height: 143px;
        /* margin-left: 100px; */
        position: absolute;
        right: 0;
    }
</style>
<div class="col-lg-12  mt-5">
    <div class="row justify-content-end">
        <div class="col-lg-2 position-relative">
        <p class="pb-2 text-right pr-5"><?= $this->config->item('happycrop_name'); ?></p>
        <img src="<?= base_url('assets/signature-img.jpeg') ?>" class="signatureimg py-2">
        <p class="py-2 text-right pr-5">Authorized Signatory</p>
        </div>
    </div>
    
</div>