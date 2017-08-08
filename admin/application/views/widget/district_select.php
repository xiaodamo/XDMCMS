<?php
$CI = &get_instance();
$CI->load->model('region_model', 'region');
$provinces   = $CI->region->provinces();
$citys = $CI->region->children_of($province_selected);
?>
<script  language="JavaScript">
    <!--

    <?php if(isset($province_selected)):?>
    var province_selected = <?php echo (int)$province_selected?>;
    <?php else:?>
    var province_selected = 0;
    <?php endif?>

    <?php if(isset($city_selected)):?>
    var city_selected = <?php echo (int)$city_selected?>;
    <?php else:?>
    var city_selected = 0;
    <?php endif?>

    <?php if(isset($district_selected)):?>
    var district_selected = <?php echo (int)$district_selected?>;
    <?php else:?>
    var district_selected = 0;
    <?php endif?>

    $(function(){

        var change_city = function(){
            $.ajax({
                url: '<?php echo site_url('region_change/select_children/parent_id')?>'+'/'+$('#province_id').val(),
                type: 'GET',
                dataType: 'html',
                success: function(data){
                    city_json = eval('('+data+')');
                    var city = document.getElementById('city_id');
                    city.options.length = 0;
                    city.options[0] = new Option('城市', '');
                    for(var i=0; i<city_json.length; i++){
                        var len = city.length;
                        city.options[len] = new Option(city_json[i].name, city_json[i].region_id);
                        if (city.options[len].value == city_selected){
                            city.options[len].selected = true;
                        }
                    }
                    change_district();//重置地区
                }
            });
        }

        change_city();//初始化城市

        $('#province_id').change(function(){
            change_city();
        });


        var change_district = function(){
            $.ajax({
                url: '<?php echo site_url('region_change/select_children/parent_id')?>'+'/'+$('#city_id').val(),
                type: 'GET',
                dataType: 'html',
                success: function(data){
                    district_json = eval('('+data+')');
                    var district = document.getElementById('district_id');
                    district.options.length = 0;
                    district.options[0] = new Option('县/区', '');
                    for(var i=0; i<district_json.length; i++){
                        var len = district.length;
                        district.options[len] = new Option(district_json[i].name, district_json[i].region_id);
                        if (district.options[len].value == district_selected){
                            district.options[len].selected = true;
                        }
                    }
                }
            });
        }

        $('#city_id').change(function(){
            change_district();
        });



    });

    //-->
</script>
<th>
    省：
</th>
<td>
<select name="province_id" id="province_id"  style="width:100px;">
    <option value="" >省份</option>
    <?php foreach($provinces as $key => $province): ?>
        <option value="<?php echo $province['region_id']; ?>" <?php if($province['region_id']==$province_selected){echo 'selected';}?> ><?php echo $province['name']; ?></option>
    <?php endforeach; ?>

</select>
</td>
<th>
    市：
</th>
<td>
<select name="city_id" id="city_id"  style="width:100px;">
    <?php foreach($citys as $key => $city): ?>
        <option value="<?php echo $city['region_id']; ?>" <?php if($city['region_id']==$city_selected){echo 'selected';}?> ><?php echo $city['name']; ?></option>
    <?php endforeach; ?>
</select>
</td>
<th>
    区：
</th>
<td>
<select name="district_id" id="district_id" style="width:100px;">
    <option value=""></option>
</select>
</td>