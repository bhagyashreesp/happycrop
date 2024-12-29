<ul class="nav nav-tabs pb-0" id="myTab">
    <li class="nav-item <?php echo ($this->uri->segment(3) == 'accounts') ? 'active' : ''; ?>">
        <a class="nav-link  <?php echo ($this->uri->segment(3) == 'accounts') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/accounts/') ?>">Accounts</a>
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
    <!-- <li class="nav-item  <?php echo ($this->uri->segment(3) == 'payment_reports') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'payment_reports') ? 'btn btn-primary text-white' : ''; ?>" href="<?php echo base_url('admin/orders/payment_reports/') ?>">Payments Report</a>
    </li> -->
</ul>