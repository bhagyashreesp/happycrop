<ul class="nav nav-tabs pb-0 pt-2" id="myTab">
    <li class="nav-item <?php echo ($this->uri->segment(3) == 'parties') ? 'active' : ''; ?>">
        <a class="nav-link  <?php echo ($this->uri->segment(3) == 'parties') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/parties/') ?>">Parties</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'adminitems') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'adminitems') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/adminitems/') ?>">Items</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'salesinvoice') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'salesinvoice') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/rtl_incoice/') ?>">Sales</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'mfc_incoice') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'mfc_incoice') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/mfc_incoice/') ?>">Purchase</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'payment_reports') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'payment_reports') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/payment_reports/') ?>">Report</a>
    </li>
</ul>

<?php if ($this->uri->segment(2) == 'purchasebill' || $this->uri->segment(2) == 'purchaseout' || $this->uri->segment(2) == 'purchaseorder' || $this->uri->segment(2) == 'purchasereturn' || $this->uri->segment(2) == 'external-purchase-out' || $this->uri->segment(2) == 'external_purchase' || $this->uri->segment(2) == 'external-purchase-order' || $this->uri->segment(2) == 'external-purchase-return') { ?>
    <ul class="nav prof-nav  border-bottom pt-1">
        <li class="nav-item ">
            <a href="<?php echo base_url('my-account/purchasebill/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'purchasebill' || $this->uri->segment(2) == 'external_purchase')  ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Bill</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('my-account/purchaseout/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'purchaseout' || $this->uri->segment(2) == 'external-purchase-out')  ? 'btn btn-primary text-white' : ''; ?>  mr-2">Payment Out</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('my-account/purchaseorder/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'purchaseorder' || $this->uri->segment(2) == 'external-purchase-order') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Order</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('my-account/purchasereturn/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'purchasereturn' || $this->uri->segment(2) == 'external-purchase-return') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Return</a>
        </li>
    </ul>
<?php } elseif ($this->uri->segment(2) == 'saleinvoice') { ?>

    <ul class="nav prof-nav pt-2 pb-2">
        <?php if ($this->uri->segment(3) == 'accounts' || $this->uri->segment(3) == 'purchasebill' || $this->uri->segment(3) == 'purchasein' || $this->uri->segment(3) == 'purchaseorder' || $this->uri->segment(3) == 'deliverychallan' || $this->uri->segment(3) == 'external-purchase-bill' || $this->uri->segment(3) == 'external-purchase-in' || $this->uri->segment(3) == 'external-sale-order') { ?>
            <li class="nav-item ">
                <a href="<?php echo base_url('seller/orders/purchasebill/') ?>" class="nav-link2 nav-link  btn  <?php echo ($this->uri->segment(3) == 'purchasebill' || $this->uri->segment(3) == 'external-purchase-bill') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Invoice</a>
            </li>
            <li class="nav-item ">
                <a href="<?php echo base_url('seller/orders/purchasein/') ?>" class="nav-link2 nav-link  btn  <?php echo ($this->uri->segment(3) == 'purchasein' || $this->uri->segment(3) == 'external-purchase-in') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Payment In</a>
            </li>
            <li class="nav-item ">
                <a href="<?php echo base_url('seller/orders/purchaseorder/') ?>" class="nav-link2 nav-link btn  <?php echo ($this->uri->segment(3) == 'purchaseorder' || $this->uri->segment(3) == 'external-sale-order') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Order</a>
            </li>
            <li class="nav-item ">
                <a href="<?php echo base_url('seller/orders/deliverychallan') ?>" class="nav-link2 nav-link btn  <?php echo ($this->uri->segment(3) == 'deliverychallan') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Delivery Challan</a>
            </li>
        <?php } elseif ($this->uri->segment(3) == 'saleinvoice') { ?>
            <li class="nav-item ">
                <a href="<?php echo base_url('seller/orders/saleinvoice/') ?>" class="nav-link2 nav-link btn  <?php echo ($this->uri->segment(3) == 'saleinvoice') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Invoice</a>
            </li>
            <li class="nav-item ">
                <a href="<?php echo base_url('seller/orders/quotation/') ?>" class="nav-link2 nav-link btn  <?php echo ($this->uri->segment(3) == 'quotation') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Quotation</a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>