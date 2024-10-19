<style>
    .box-shadow {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .show-time {
        position: absolute;
        right: 14px;
        bottom: 5px;
    }
</style>
<div class="container">
    <div class="card card-body my-3">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h3>Queries</h3>
                <ul>
                    <?php foreach ($orderQuery as $key => $value) { ?>
                        <?php if ($user_id == $value["from_user_id"]) { ?>
                            <li class="py-2">
                                <div class="row jus box-shadow rounded-backtop">
                                    <div class="col-lg-11 p-2">
                                        <?php echo $value["message"]; ?>
                                    </div>
                                    <div class="col-lg-1 p-2">
                                        <img src="<?php echo  base_url('assets/avatar-icon.png'); ?>" class="">
                                        
                                    </div>
                                    <span class="text-end"><?php echo  date('d M Y h:i a',strtotime($value["created_at"])); ?></span>
                                </div>
                            </li>
                        <?php } else { ?>
                            <li class="py-2">
                                <div class="row box-shadow rounded-backtop">
                                    <div class="col-lg-1">
                                        <img src="<?php echo  base_url('assets/avatar-icon.png'); ?>" class=" ">
                                    </div>

                                    <div class="col-lg-11 px-2 p-2">
                                        <?php echo $value["message"]; ?>
                                        <span class="show-time"><?php echo date('d M Y h:i a',strtotime($value["created_at"])); ?></span>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>
                   
                </ul>
            </div>
        </div>

    </div>
    <div class="card card-body my-3 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <form id="addquery">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="">
                                <label>Message</label>
                                <textarea name="message" rows="4" class="form-control w-100"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-md-2">

                            <input type="hidden" name="order_id" value="<?= $order_id; ?>">
                            <input type="hidden" name="from_user_id" value="<?= $user_id; ?>">
                            <input type="hidden" name="to_user_id" value="<?= $to_user_id; ?>">
                            <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_btn">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on("submit", "#addquery", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form_data = new FormData($('#addquery')[0]);
            $('#submit_btn').attr('disabled', true).html("Loading...");

            $.ajax({
                url: base_url + 'My_account/add_order_query',
                type: "POST",
                data: form_data,
                async: true,
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        swal.close();
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {

                        Toast.fire({
                            icon: 'errors',
                            title: response.message
                        });
                        swal.close();
                    }
                    $('#submit_btn').attr('disabled', false).html("Save");
                }
            })
        });
    });
</script>