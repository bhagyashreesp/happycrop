<ul class="nav prof-nav">
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/statements/') ?>" class="nav-link2 btn  <?php echo ($this->uri->segment(2) == 'statements') ? 'btn btn-primary text-white' : ''; ?>  mr-2">Parties</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/items/') ?>" class="nav-link2 btn mr-2 <?php echo ($this->uri->segment(2) == 'items') ? 'btn btn-primary text-white' : ''; ?>">Items</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/accounts/') ?>" class="nav-link2 btn mr-2 <?php echo ($this->uri->segment(2) == 'accounts') ? 'btn btn-primary text-white' : ''; ?>">Purchase</a>
    </li>
    <li class="nav-item ">
        <a href="<?php echo base_url('my-account/expenses/') ?>" class="nav-link2 btn mr-2 <?php echo ($this->uri->segment(2) == 'expenses') ? 'btn btn-primary text-white' : ''; ?>">Expenses</a>
    </li>
</ul>
