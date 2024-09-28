<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice</title>
    <?php $this->load->view('admin/include-head.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center border border-black p-2" id="generatePDf">
            <div class="col-lg-12 py-4">
                <h2 class="text-center font-weight-bold">Tax Invoice</h2>
            </div>
            <div class="col-lg-8 pb-2">
                <div class="bg-gray-light">
                    <table class="table border-none">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <p class="font-weight-bold p-2 m-0">Manufacturer/ Service Provider Name</p>

                                </td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"> <?php echo $manufacture['plot_no'] . ' ' . $manufacture['street_locality'] . ' ' . $manufacture['landmark'] . ' ' . $manufacture['city'] . ' ' . $manufacture['state'] . ' ' . $manufacture['pin'];?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['email'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">GSTIN : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['gst_no'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">State : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4 pb-2">
            </div>
            <div class="col-lg-4">
                <div class="bg-gray-light">
                    <table class="table border-none">
                        <p class="font-weight-bold p-2 m-0">Bill To:</p>
                        <p class="font-weight-bold p-2 m-0">Shop Owner Name</p>

                        <tbody>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]["billing_address"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]["mobile"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]["email"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">GSTIN : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]['retailer_gst_no'] ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bg-gray-light">
                    <table class="table border-none">
                        <p class="font-weight-bold p-2 m-0">Ship To:</p>
                        <p class="font-weight-bold p-2 m-0">Shop Owner Name</p>

                        <tbody>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]["billing_address"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]["mobile"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]["email"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">State : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]['retailer_gst_no'] ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bg-gray-light">
                    <table class="table h-100 border-none">
                        <p class="font-weight-bold p-2 m-0">Place to supply</p>

                        <tbody>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Invoice No : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?php echo $order[0]["id"]; ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Date : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?=  (!empty($order_item_stages) && $order_item_stages[0]["status"] === "send_invoice" ? date('d M Y h:i a', strtotime($order_item_stages[0]["created_date"])) : date('d M Y h:i a', strtotime(date('d-m-y')))); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-12 py-3">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Price/Unit</th>
                                <th>GST</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $total_amt = 0;
                            $total_qty = 0;
                            $total_gst = 0;
                            foreach ($order[0]['order_items'] as $key => $item) {
                                $total_amt += $item["price"] * (($item['quantity']) ? $item['quantity'] : $item['quantity']);
                                $total_qty += (($item['quantity']) ? $item['quantity'] : $item['quantity']);
                                $total_gst += ($item["price"] * $item['tax_percentage'] / 100);

                                if ($manufacture['seller_id'] == $item['seller_id']) {
                            ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?php echo $item['product_name']; ?></td>
                                        <td><?php echo (($item['quantity']) ? $item['quantity'] : $item['quantity']) ?></td>
                                        <td><?php echo (($item['unit'] != '') ? $item['unit'] : $item['pv_unit']) ?></td>
                                        <td><?php echo $item["price"] ?></td>
                                        <td><?= isset($item['tax_percentage']) && !empty($item['tax_percentage']) ? ($item["price"] * $item['tax_percentage'] / 100) . '(' . $item['tax_percentage'] . ' %)' : '-' ?></td>
                                        <td><?= ($item["price"] * (($item['quantity']) ? $item['quantity'] : $item['quantity'])) ?></td>
                                    </tr>
                            <?php
                                }
                                $i++;
                            }
                            ?>
                            <tr>
                                <th class="text-left" colspan="2">Total Amount</th>
                                <th class="text-left"><?= $total_qty; ?></th>
                                <th class="text-left" colspan="2"></th>
                                <th class="text-left"><?= $settings['currency'] . '' . number_format($total_gst, 2); ?></th>
                                <th class="text-left"><?php echo $settings['currency'] . '' . number_format(($total_amt), 2); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-between">
                    <div class="col-lg-6 py-2">
                        <div class="form-group ">
                            <label for="receipt"> <strong>Invoice Amount in Words</strong> </label>
                            <div class="bg-gray-light p-2 w-100"><?php echo convertNumberToWords($total_amt) ?></div>
                        </div>
                        <div class="border border-dark p-2">
                            <h5 class="font-weight-bold">Terms and conditions</h5>
                            <ul class="list-none">
                                <?php
                                foreach ($getterms as $key => $value) { ?>
                                    <li><?php echo ($key + 1) . ". " . $value['terms_conditions']; ?></li>

                                <?php
                                }
                                ?>
                                <li>Thanks for doing business with us!</li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-lg-4 pt-2 mt-4">
                        <div class="bg-gray-light">
                            <table class="table h-100 border-none">
                                <tbody>
                                    <tr class="p-2 pt-2 ">
                                        <td class="border-top-0 w-50 font-weight-bold">Total : -</td>
                                        <td class="border-top-0 w-50 pl-2"><?php echo $settings['currency'] . '' . number_format(($total_amt), 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php include(APPPATH . 'views/front-end/happycrop/exportfooter.php'); ?>


                </div>
            </div>
        </div>
        <?php if ($view == "view") { ?>

            <div class="row justify-content-center">
                <button class="btn btn-primary my-3" onclick="generatePDF();">Download</button>
            </div>
        <?php } ?>
    </div>
    <?php $this->load->view('admin/include-script.php'); ?>
    <script>
        $(document).ready(function() {
            <?php if ($view != "view") { ?>
                generatePDF();
            <?php } ?>
        });

        function generatePDF() {
            const element = document.getElementById('generatePDf');
            html2pdf().from(element).set({
                margin: [5, 5],
                filename: 'Invoice.pdf',
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'legal',
                    orientation: 'portrait'
                }
            }).save().then(function() {
                history.back();

            });

        }
    </script>
</body>

</html>