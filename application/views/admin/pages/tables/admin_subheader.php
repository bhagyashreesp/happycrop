<style>

.float-right.pagination,.page-list{
    display: block !important;
}

.extsys li a.active{
  background-color: #007bff;
  color: #fff!important;
}
.content-area{
    padding-top: 0px!important;
}
</style>
<ul class="nav nav-tabs pb-0" id="myTab">
    <li class="nav-item <?php echo ($this->uri->segment(3) == 'accounts' || $this->uri->segment(3) == 'salesinvoice' || $this->uri->segment(3) == 'adminitems' || $this->uri->segment(3) == 'parties' || $this->uri->segment(3) == 'paymentin' || $this->uri->segment(3) == 'saleorder' || $this->uri->segment(3) == 'deliverychallan' || $this->uri->segment(3) == 'salereturn' || $this->uri->segment(3) == 'purchasebill' || $this->uri->segment(3) == 'purchaseout' || $this->uri->segment(3) == 'purchaseorder' || $this->uri->segment(3) == 'purchasereturn') ? 'active' : ''; ?>">
        <a class="nav-link  <?php echo ($this->uri->segment(3) == 'accounts' || $this->uri->segment(3) == 'salesinvoice' || $this->uri->segment(3) == 'adminitems' || $this->uri->segment(3) == 'parties' || $this->uri->segment(3) == 'paymentin' || $this->uri->segment(3) == 'saleorder' || $this->uri->segment(3) == 'deliverychallan' || $this->uri->segment(3) == 'salereturn' || $this->uri->segment(3) == 'purchasebill' || $this->uri->segment(3) == 'purchaseout' || $this->uri->segment(3) == 'purchaseorder' || $this->uri->segment(3) == 'purchasereturn') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/accounts/') ?>">Accounts</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'statements') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'statements') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/statements/') ?>">Statements</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'rtl_incoice') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'rtl_incoice') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/rtl_incoice/') ?>">Retailer Invoices</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'mfc_incoice') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'mfc_incoice') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/mfc_incoice/') ?>">Manufacturer Invoices</a>
    </li>
    <li class="nav-item  <?php echo ($this->uri->segment(3) == 'payment_reports') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'payment_reports') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/payment_reports/') ?>">Payments Report</a>
    </li>
</ul>