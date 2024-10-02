<div class="content-wrapper">

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <h2 class="font-weight-bold py-2">Terms & Conditions</h2>
                        <div>
                            <a href="#" class='button-- button-danger-outline-- btn btn-primary btn-sm d-inline-block p-3 mt-2' data-toggle="modal" data-target="#terms_conditions" onclick='document.getElementById("terms_conditionform").reset();$("#term_id").val("");'>Add Terms & Conditions</a>
                        </div>
                    </div>
                    <table class='table-striped' data-toggle="table" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                        <thead>
                            <tr>
                                <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">ID</th>
                                <th data-field="color_state" data-sortable='false'>Term</th>
                                <th data-field="operate">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($getterms as $term) : ?>
                                
                                <tr>
                                    <td><?php echo $term['id']; ?></td>
                                    <td><?php echo $term['terms_conditions']; ?></td>
                                    <td>
                                        <?php $terms_id = $term["id"]; ?>
                                        <a  class="btn btn-danger btn-sm text-white" onclick="gettermsdata('<?php echo $terms_id ?>')">Edit</a>
                                        <a href="<?php echo base_url('seller/orders/termdelete/' . $term['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this term?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="terms_conditions" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Terms & Conditions </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-3 justify-content-center">
                    <form class="form-horizontal w-100" id="terms_conditionform" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="">
                                    <label>Terms & Conditions</label>
                                    <input type="text" class="form-control" name="terms_cond" id="terms_cond" required />
                                </div>
                            </div>

                            <div class="form-group col-lg-2">
                                <input type="hidden" name="term_id" id="term_id">
                                <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit_btn">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
<script type="text/javascript">
    function gettermsdata(id){
        $.ajax({
            url: base_url + 'seller/orders/gettermsdetails/' + id,
            type: "GET",
            dataType: "JSON",
            success: function(response) {
               if(response.status){
                
                $('#terms_cond').val(response.data?.[0]?.terms_conditions);
                $('#term_id').val(response.data?.[0]?.id);
                $("#terms_conditions").modal('show');
               }
            }
        })
    }
    $(document).ready(function() {
        $(document).on("submit", "#terms_conditions", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form_data = new FormData($('#terms_conditionform')[0]);
            $('#submit_btn').attr('disabled', true).html("Loading...");

            $.ajax({
                url: base_url + 'seller/orders/termsconditionsave',
                type: "POST",
                data: form_data,
                async: true,
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        iziToast.success({
                            message:response.message
                        });
                        swal.close();
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        iziToast.error({
                            message:response.message
                        });
                        swal.close();
                    }
                    $('#submit_btn').attr('disabled', false).html("Save");
                }
            })
        });
    });
</script>