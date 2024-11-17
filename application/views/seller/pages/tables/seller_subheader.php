<ul class="nav nav-tabs pb-0" id="myTab">

    <li class="nav-item <?php echo ($this->uri->segment(3) == 'accounts') ? 'active' : ''; ?>">
        <a class="nav-link <?php echo ($this->uri->segment(3) == 'accounts') ? 'btn btn-primary' : ''; ?>" href="<?php echo base_url('seller/orders/accounts/') ?>">Purchase</a>
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