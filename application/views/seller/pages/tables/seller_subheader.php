<ul class="nav nav-tabs pb-0" id="myTab">

    <li class="nav-item <?php echo ($this->uri->segment(3) == 'accounts') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'accounts') ? 'btn btn-primary' : ''; ?>" href="<?php echo base_url('seller/orders/accounts/') ?>">Sale</a>
    </li>
    <li class="nav-item <?php echo ($this->uri->segment(3) == 'statements') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'statements') ? 'btn btn-primary' : ''; ?>" href="<?php echo base_url('seller/orders/statements/') ?>">Statements</a>
    </li>
    <li class="nav-item <?php echo ($this->uri->segment(3) == 'items') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'items') ? 'btn btn-primary' : ''; ?>" href="<?php echo base_url('seller/orders/items/') ?>">Items</a>
    </li>
    <li class="nav-item <?php echo ($this->uri->segment(3) == 'expenses') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'expenses') ? 'btn btn-primary' : ''; ?>" href="<?php echo base_url('seller/orders/expenses/') ?>">Expenses</a>
    </li>
</ul>


<ul class="nav prof-nav pt-4">
    <?php if($this->uri->segment(3) == 'accounts' || $this->uri->segment(3) == 'purchasebill' || $this->uri->segment(3) == 'purchasein' || $this->uri->segment(3) == 'purchaseorder' || $this->uri->segment(3) == 'deliverychallan') { ?>
    <li class="nav-item ">
        <a href="<?php echo base_url('seller/orders/purchasebill/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'purchasebill') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Invoice</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('seller/orders/purchasein/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'purchasein') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Payement In</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('seller/orders/purchaseorder/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'purchaseorder') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Order</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('seller/orders/deliverychallan') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'deliverychallan') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Delivery Challan</a>
    </li>
    <?php }elseif($this->uri->segment(3) == 'saleinvoice') { ?>
    <li class="nav-item ">
        <a href="<?php echo base_url('seller/orders/saleinvoice/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'saleinvoice') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Invoice</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('seller/orders/quotation/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(3) == 'quotation') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Quotation</a>
    </li>
<?php } ?>    
</ul>