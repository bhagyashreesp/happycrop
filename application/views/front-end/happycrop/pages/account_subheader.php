<ul class="nav prof-nav">
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/statements/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'statements') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Parties</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/items/') ?>" class="nav-link2 btn mr-2 <?php echo ($this->uri->segment(2) == 'items') ? 'btn btn-primary text-white' : ''; ?>">Items</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/purchasebill') ?>" class="nav-link2 btn mr-2 <?php echo ($this->uri->segment(2) == 'purchasebill') ? 'btn btn-primary text-white' : ''; ?>">Purchase</a>
    </li>
    <!-- <li class="nav-item ">
        <a href="<?php echo base_url('my-account/saleinvoice/') ?>" class="nav-link2 btn mr-2 <?php echo ($this->uri->segment(2) == 'saleinvoice') ? 'btn btn-primary text-white' : ''; ?>">Sale</a>
    </li> -->
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/expenses/') ?>" class="nav-link2 btn mr-2 <?php echo ($this->uri->segment(2) == 'expenses') ? 'btn btn-primary text-white' : ''; ?>">Expenses</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/quickbill/') ?>" class="nav-link2 btn mr-2 <?php echo ($this->uri->segment(2) == 'quickbill') ? 'btn btn-primary text-white' : ''; ?>">Quick Bill</a>
    </li>
</ul>

<ul class="nav prof-nav pt-4">
    <?php if($this->uri->segment(2) == 'accounts' || $this->uri->segment(2) == 'purchasebill' || $this->uri->segment(2) == 'purchaseout' || $this->uri->segment(2) == 'purchaseorder' || $this->uri->segment(2) == 'purchasereturn') { ?>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/purchasebill/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'purchasebill') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Bill</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/purchaseout/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'purchaseout') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Out</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/purchaseorder/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'purchaseorder') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Order</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/purchasereturn/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'purchasereturn') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Purchase Return</a>
    </li>
    <?php }elseif($this->uri->segment(2) == 'saleinvoice') { ?>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/saleinvoice/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'saleinvoice') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Sale Invoice</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/quotation/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'quotation') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Quotation</a>
    </li>
<?php } ?>    
</ul>
