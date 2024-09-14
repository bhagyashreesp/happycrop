<div class="login-page">
    <div class="page-content">
        <div class="container">
            <div class="login-popup bg-white">
                <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                    <ul class="nav nav-tabs text-uppercase" role="tablist">
                        <li class="nav-item">
                            <a href="#sign-in" class="nav-link border-none active">Sign In</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="sign-in">
                            <form action="<?= base_url('home/login') ?>" class='form-submit-event' method="post">
                                <div class="form-group">
                                    <label>Mobile No. *</label>
                                    <input type="text" class="form-control" name="identity" id="identity" placeholder="Mobile number" required>
                                </div>
                                <div class="form-group mb-0">
                                    <label>Password *</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                </div>
                                <br />
                                <button type="submit" class="btn btn-primary btn-block"><?php echo ($this->lang->line('login')!='') ? $this->lang->line('login') : 'Login' ?></button>
                                <br>
                                <div class="d-flex justify-content-center">
                                    <div class="form-group" id="error_box"></div>
                                </div>
                            </form>
                            <div class="mt-5 text-center">
                                <a href="#" class=" mt-5">New to Happy Crop? Register Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>