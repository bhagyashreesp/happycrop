<ul class="nav nav-tabs pb-0 pt-2" id="myTab">
    <li class="nav-item <?php echo ($this->uri->segment(3) == 'parties') ? 'active' : ''; ?>">
        <a class="nav-link  <?php echo ($this->uri->segment(3) == 'parties') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/parties/') ?>">Parties</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'adminitems') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'adminitems') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/adminitems/') ?>">Items</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'salesinvoice'  || $this->uri->segment(3) == 'paymentin' || $this->uri->segment(3) == 'saleorder' || $this->uri->segment(3) == 'deliverychallan' || $this->uri->segment(3) == 'salereturn' ) ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'salesinvoice'  || $this->uri->segment(3) == 'paymentin' || $this->uri->segment(3) == 'saleorder' || $this->uri->segment(3) == 'deliverychallan' || $this->uri->segment(3) == 'salereturn') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/salesinvoice/') ?>">Sales</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'purchasebill' || $this->uri->segment(3) == 'purchaseout' || $this->uri->segment(3) == 'purchaseorder' || $this->uri->segment(3) == 'purchasereturn' ) ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'purchasebill' || $this->uri->segment(3) == 'purchaseout' || $this->uri->segment(3) == 'purchaseorder' || $this->uri->segment(3) == 'purchasereturn' ) ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/purchasebill/') ?>">Purchase</a>
    </li>
    <!-- <li class="nav-item  <?php echo ($this->uri->segment(3) == 'payment_reports') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'payment_reports') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/payment_reports/') ?>">Report</a>
    </li> -->
</ul>

<?php if ($this->uri->segment(3) == 'purchasebill' || $this->uri->segment(3) == 'purchaseout' || $this->uri->segment(3) == 'purchaseorder' || $this->uri->segment(3) == 'purchasereturn' ) { ?>
    <ul class="nav prof-nav  border-bottom pt-1">
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/purchasebill/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'purchasebill')  ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Bill</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/purchaseout/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'purchaseout')  ? 'btn btn-primary text-white' : ''; ?>  mr-2">Payment Out</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/purchaseorder/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'purchaseorder' ) ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Order</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/purchasereturn/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'purchasereturn' ) ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Return</a>
        </li>
    </ul>
<?php } ?>
<?php if ($this->uri->segment(3) == 'salesinvoice' || $this->uri->segment(3) == 'paymentin' || $this->uri->segment(3) == 'saleorder' || $this->uri->segment(3) == 'deliverychallan' || $this->uri->segment(3) == 'salereturn') { ?>
    <ul class="nav prof-nav pt-2 pb-2">
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/salesinvoice/') ?>" class="nav-link2 nav-link  btn  <?php echo ($this->uri->segment(3) == 'salesinvoice' ) ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Invoice</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/paymentin/') ?>" class="nav-link2 nav-link  btn  <?php echo ($this->uri->segment(3) == 'paymentin') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Payment In</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/saleorder/') ?>" class="nav-link2 nav-link btn  <?php echo ($this->uri->segment(3) == 'saleorder' ) ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Order</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/deliverychallan') ?>" class="nav-link2 nav-link btn  <?php echo ($this->uri->segment(3) == 'deliverychallan') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Delivery Challan</a>
        </li>
        <li class="nav-item ">
            <a href="<?php echo base_url('admin/orders/salereturn/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'salereturn') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Return</a>
        </li>

    </ul>
<?php } ?>