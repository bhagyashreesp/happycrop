<style>
    .table td,
    .table th {
        font-size: 13px;
    }

    .fixed-table-toolbar {
        top: 17px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo $page_title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content ">
                    <div class="card- card-info mb-0">
                        <?php $this->load->view("seller/pages/tables/seller_subheader"); ?>


                    </div>
                    <div class="card content-area pt-4">
                        <div class="col-md-12 mt-2">
                            <div class="row col-md-12">
                                <div class="col-md-2">
                                    <a href="#" class='button-- button-danger-outline-- btn btn-primary btn-sm d-inline-block ' data-toggle="modal" data-target="#add_expenses">Add Expenses</a>
                                </div>
                                <div class="col-md-2">
                                    <label>From Date</label>
                                    <input type="date" id="start_date" class="form-control" />
                                </div>
                                <div class="col-md-2">
                                    <label>To Date</label>
                                    <input type="date" id="end_date" class="form-control" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Search by Name/ ID / Category</label>
                                    <input type="text" id="search_field" name="search_field" class="form-control" />
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-center pt-4">
                                    <input type="hidden" id="condition" name="condition" value="<?php echo $condition; ?>" />
                                    <button type="button" class="btn btn-primary btn-md" onclick="status_date_wise_search()">Search</button>
                                </div>
                            </div>

                            <table class='table-striped table-resp' data-toggle="table" data-url="<?= base_url('my-account/get_expense_list/') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="false" data-show-refresh="false" data-trim-on-search="false" data-sort-name="o.last_updated" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["pdf","excel","csv"]' data-export-options='{"fileName": "reports","ignoreColumn": ["image","document","product_view_url"] }' data-query-params="orders_query_params">
                                <thead>
                                    <tr class="dark-blue-bg">
                                        <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter"> ID</th>
                                        <th data-field="expense_category" data-sortable='false'>Expense Category</th>
                                        <th data-field="expense_number" data-sortable='false'>Expense Number </th>
                                        <th data-field="date" data-sortable='true'>Date</th>
                                        <th data-field="payment_type" data-sortable='false'>Payment Type</th>
                                        <th data-field="total" data-sortable='false'>Total</th>
                                        <th data-field="paid_amount" data-sortable='false'>Paid Amount</th>
                                        <th data-field="descritpion" data-sortable='false'>Descritpion</th>
                                        <th data-field="image" data-sortable='false'>Image</th>
                                        <th data-field="document" data-sortable='false'>Document</th>
                                        <th data-field="product_view_url" data-sortable='false'>View</th>
                                    </tr>
                                </thead>
                            </table>



                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="add_expenses" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-primary">
                <h5 class="modal-title text-white" id="exampleModalLongTitle">Add Expenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-3">
                    <form class="form-horizontal " action="<?= base_url('my-account/addexpense'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Expense Category</label>
                                    <input type="text" class="form-control" name="expense_category" value="" required placeholder="Expense Category" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Expense Number</label>
                                    <input type="text" class="form-control" name="expense_number" value="" required placeholder="Expense Number" />
                                </div>
                            </div>
                            <div class="col-lg-6"></div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="date" value="" required placeholder="Date" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Reference Number</th>
                                            <th>Quantity</th>
                                            <th>Price/Unit</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="item_data">
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" class="form-control" name="name_1" value="" placeholder="Name" required /></td>
                                            <td><input type="text" class="form-control ref_no" name="ref_no_1" placeholder="Reference Number" required /></td>
                                            <td><input type="number" step="0.01" class="form-control quantity" name="quantity_1" placeholder="Quantity" required onkeyup="calculateAmount(1)" /></td>
                                            <td><input type="number" step="0.01" class="form-control price" name="price_1" placeholder="Price/Unit" required onkeyup="calculateAmount(1)" /></td>
                                            <td><input type="number" step="0.01" class="form-control amount" name="amount_1" placeholder="Amount" required /></td>
                                        </tr>
                                    </tbody>

                                </table>
                                <a href="#" class="py-3 btn btn-primary" onclick="addrow();">Add Row</a>
                            </div>

                            <div class="form-group col-md-6 mt-2">
                                <div class="">
                                    <label>Payment Type</label>
                                    <input type="text" class="form-control" name="payment_type" value="" required placeholder="Payment Type" />
                                </div>
                            </div>
                            <div class="form-group col-md-6 mt-2">
                                <div class="">
                                    <label>Total</label>
                                    <input type="number" step="0.01" class="form-control" name="total" id="total" value="" required placeholder="Total" />
                                </div>
                            </div>
                            <div class="col-lg-6"></div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Paid Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="paid_amount" value="" required placeholder="Paid Amount" />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label>Description</label>
                                    <textarea name="descritpion" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="">
                                    <label>Add Image</label>
                                    <input type="file" class="form-control" name="add_image" />
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="">
                                    <label>Add Document</label>
                                    <input type="file" class="form-control" name="add_document" />
                                </div>
                            </div>

                            <div class="form-group col-md-4 align-content-lg-start pt-3">
                                <input type="hidden" name="item_count" id="item_count" value="1">
                                <button type="submit" class="btn btn-primary  btn-block" id="submit_btn">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSendPayment">Send</button>
            </div> -->

        </div>
    </div>
</div>
<script>
    function calculateAmount(Index) {
        quantity = $('input[name="quantity_' + Index + '"]').val();
        price = $('input[name="price_' + Index + '"]').val();
        amtTotal = quantity * price;
        $('input[name="amount_' + Index + '"]').val(amtTotal);
        calculateSum();
    }

    function calculateSum() {
        inputs = document.querySelectorAll(`.amount`);
        let sum = 0;
        inputs.forEach(input => {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                sum += value;
            }
        });
        $("#total").val(sum);

    }
    var index = 1;

    function addrow() {
        index++;
        html = '<tr>\
                <td>' + index + '</td>\
                <td><input type="text" class="form-control" name="name_' + index + '" value="" placeholder="Name" required/></td>\
                <td><input type="number" step="0.01" class="form-control quantity" name="quantity_' + index + '" placeholder="Quantity" required onkeyup="calculateAmount(' + index + ')"/></td>\
                <td><input type="number" step="0.01" class="form-control price" name="price_' + index + '" placeholder="Price/Unit" required onkeyup="calculateAmount(' + index + ')"/></td>\
                <td><input type="number" step="0.01" class="form-control amount" name="amount_' + index + '" placeholder="Amount" required/></td>\
                </tr>';
        $("#item_data").append(html);
        $("#item_count").val(index);

    }
</script>