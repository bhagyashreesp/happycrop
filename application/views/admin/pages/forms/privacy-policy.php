<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Privacy Policy And Terms & Conditions</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
            <li class="breadcrumb-item active">Privacy Policy And Terms & Conditions</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-info">
            <!-- form start -->
            <form class="form-horizontal form-submit-event" action="<?= base_url('admin/Privacy_policy/update-privacy-policy-settings'); ?>" method="POST" enctype="multipart/form-data">
              
              <div class="card-body pad">
                <label for="other_images">Terms of Use </label>
                <a href="<?= base_url('admin/privacy-policy/terms-and-conditions-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Terms of Use'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="terms_n_conditions_input_description" class="textarea text_editor" placeholder="Place some text here">
                          <?= $terms_n_condition ?>
                        </textarea>
                </div>

              </div>
              <div class="card-body pad">
                <label for="other_images"> Privacy Policy </label>
                <a href="<?= base_url('admin/privacy-policy/privacy-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Privacy Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="privacy_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $privacy_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Shipping Policy </label>
                <a href="<?= base_url('admin/privacy-policy/shipping-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Shipping Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="shipping_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $shipping_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Return Policy </label>
                <a href="<?= base_url('admin/privacy-policy/return-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Return Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="return_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $return_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Quality Policy </label>
                <a href="<?= base_url('admin/privacy-policy/quality-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Quality Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="quality_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $quality_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Pricing Policy </label>
                <a href="<?= base_url('admin/privacy-policy/pricing-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Pricing Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="pricing_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $pricing_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Delivery Policy </label>
                <a href="<?= base_url('admin/privacy-policy/delivery-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Delivery Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="delivery_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $delivery_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Payment Policy </label>
                <a href="<?= base_url('admin/privacy-policy/payment-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Payment Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="payment_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $payment_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Security Policy </label>
                <a href="<?= base_url('admin/privacy-policy/security-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Security Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="security_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $security_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Partnering Policy </label>
                <a href="<?= base_url('admin/privacy-policy/partnering-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Partnering Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="partnering_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $partnering_policy ?>
                  </textarea>
                </div>
              </div>
              <div class="card-body pad">
                <label for="other_images"> Licensing Policy </label>
                <a href="<?= base_url('admin/privacy-policy/licensing-policy-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Licensing Policy'><i class='fa fa-eye'></i></a>
                <div class="mb-3">
                  <textarea name="licensing_policy_input_description" class="textarea text_editor" placeholder="Place some text here text">
                          <?= $licensing_policy ?>
                  </textarea>
                </div>
                
                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-success" id="submit_btn">Update Privacy Policy And Terms & Conditions</button>
                </div>
              </div>

              <div class="d-flex justify-content-center">
                <div class="form-group" id="error_box">
                </div>
              </div>
              <!-- /.card-body -->
            </form>
          </div>
          <!--/.card-->
        </div>
        <!--/.col-md-12-->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>