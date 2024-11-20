<style>
    
.list-none{
  list-style: none!important;
}


</style>
<!-- <div class="col-lg-12 bg-gray-light mt-5" id="pdffooter">
    <div class="row">
        <div class="col-lg-6 py-3">
            <ul class="list-none pl-1">
                <li class="font-weight-bold">Issued By :</li>
                <li><?= $this->config->item('happycrop_name'); ?></li>
                <li>Address: <?= $this->config->item('address'); ?></li>
                <li>GSTN : <?= $this->config->item('gstin'); ?></li>
            </ul>
        </div>
        <div class="col-lg-6 py-3">
            <ul class="list-none pl-1">
                <li class="font-weight-bold">Contact :</li>
                <li>Mobile : <?= $this->config->item('mobile'); ?></li>
                <li>Support : <?= $this->config->item('support'); ?></li>
                <li>Sales : <?= $this->config->item('sales'); ?></li>
            </ul>
        </div>
    </div>
</div> -->
<div class="col-lg-12 bg-primary mt-5" id="pdffooter">
    <div class="row justify-content-center">
        <div class="col-lg-5 py-3">
            <ul class="list-none mb-0 text-white">
                <li class=""><b>Issued By : </b> <?= $this->config->item('happycrop_name'); ?> | GSTN : <?= $this->config->item('gstin'); ?></li>
                <li class=""><b>Contact : </b> <?= $this->config->item('mobile'); ?> | <?= $this->config->item('support'); ?> | <?= $this->config->item('sales'); ?></li>
              
            </ul>
        </div>
    </div>
</div>