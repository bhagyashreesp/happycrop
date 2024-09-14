<form id="adminPopForm" name="adminPopForm" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Shop Name</label>
                <input type="text" class="form-control" name="shop_name" id="shop_name" placeholder="Shop Name" value="<?php echo $address_info->shop_name;?>"/>
            </div>    
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Plot No. / Floor / Building  </label>
                <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Plot No. / Floor / Building" value="<?php echo $address_info->plot_no;?>"/>
            </div>    
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Street / Locality / Village</label>
                <input type="text" class="form-control" name="street_locality" id="street_locality" placeholder="Street / Locality" value="<?php echo $address_info->street_locality;?>"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Land Mark</label>
                <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Land Mark" value="<?php echo $address_info->landmark;?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>State</label>
                <select class="form-control" name="state_id" id="state_id" placeholder="State" onchange="getCities(this.value, 'city_id');">
                    <option value="">Select</option>
                    <?php
                    $states = $this->db->query("SELECT * FROM `states` ORDER BY name ASC")->result_array();
                    if($states)
                    {
                        foreach($states as $state)
                        {
                            $selected = "";
                            if($address_info->state_id == $state['id'])
                            {
                                $selected = "selected='selected'";
                            }
                            ?>
                            <option value="<?php echo $state['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($state['name'])); ?></option>
                            <?php       
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>District</label>
                <select class="form-control" name="city_id" id="city_id" placeholder="District">
                    <option value="">Select</option>
                    <?php
                    if($address_info->state_id)
                    {
                        $cities = $this->db->query("SELECT * FROM `cities` WHERE `state_id` = '".$address_info->state_id."' ORDER BY name ASC")->result_array();
                    }
                    else
                    {
                        $cities = $this->db->query("SELECT * FROM `cities` ORDER BY name ASC")->result_array();   
                    }
                    if($cities)
                    {
                        foreach($cities as $city)
                        {
                            $selected = "";
                            if($address_info->city_id == $city['id'])
                            {
                                $selected = "selected='selected'";
                            }
                            ?>
                            <option value="<?php echo $city['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($city['name'])); ?></option>
                            <?php       
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group ">
                <label>Pin  </label>
                <input type="text" class="form-control" name="pin" id="pin" placeholder="Pin" value="<?php echo $address_info->pincode;?>"/>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $address_info->id;?>" />
    <input type="hidden" name="type" value="storage" />
</form>
<script type="text/javascript">
function getCities(state_id, city_div)
    {
        $.ajax({
            url: "<?=base_url()?>my_account/getCities",
            data:{state_id:state_id},
            method:"Post",
            cache: false
        }).done(function( html ) {
            $( "#"+city_div ).html( html );
        });
    }
</script>                                 
<?php
$content = ob_get_contents();
ob_end_clean();

$btn="Save";
$url = base_url().'my-account/modal_save_address';
if($address_info->id)
{
    $btn = "Update";
}

$buttons = '<button type="button" data-dismiss="modal" class="btn btn-default btn-danger">Close</button>
            <button id="btn_sv" type="button" class="btn btn-primary" onclick="save_storage_address(\''.$url.'\');">'.$btn.'</button>';
            
$response = array("html"=>$content,"buttons"=>$buttons);

echo json_encode($response);
        
?>