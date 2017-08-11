<?php

/**
 * 信息提示方式：1
 * 带多行导航链接,不带自动跳转.
 *
 * @param string $message
 * @param array $gotos
 */
function show_message1($message)
{
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    $args = func_get_args();
    array_shift($args);
    $CI = &get_instance();
    $data['gotos']   = $args;
    $data['message'] = (string)$message;
    $CI->load->view('_show_message1', $data);
    $CI->output->_display($CI->output->get_output());
    exit;
}

/**
 * 信息提示方式：2
 * 带自动跳转
 *
 * @param string $message
 * @param string $goto
 */
function show_message2($message, $goto)
{
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    $CI = &get_instance();
    $data['goto']    = (string)$goto;
    $data['message'] = (string)$message;
    $CI->load->view('_show_message2', $data);
    $CI->output->_display($CI->output->get_output());
    exit;
}

/**
 *json化返回值
 * @param $message
 * @param $res
 */
function show_message3($succes_msg="",$fail_msg="",$res=null){
    if(empty($succes_msg)&&!empty($fail_msg)){
        $res=false;
    }elseif(!empty($succes_msg)&&empty($fail_msg)){
        $res=true;
    }
    $msg = doReturn($succes_msg,$fail_msg,$res);
    ajaxReturn($msg);
    exit;
};

/**
 *json化返回值
 * @param $message
 * @param $res
 */
function show_message4($msg){
    ajaxReturn($msg);
    exit;
};

/**
 * Ajax方式返回数据到客户端
 * @access protected
 * @param mixed $data 要返回的数据
 * @param String $type AJAX返回数据格式
 * @param int $json_option 传递给json_encode的option参数
 * @return void
 */
function ajaxReturn($data,$type='JSON',$json_option=0) {
    switch (strtoupper($type)){
        case 'JSON' :
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode($data,$json_option));
        case 'XML'  :
            // 返回xml格式数据
            header('Content-Type:text/xml; charset=utf-8');
            exit(xml_encode($data));
        case 'JSONP':
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            $handler  =  'jsonpReturn';
            exit($handler.'('.json_encode($data,$json_option).');');
        case 'EVAL' :
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            exit($data);
    }
}
/**
 * [doReturn 返回前端ajax处理数据结果]
 * @param  string $success [成功信息]
 * @param  string $error   [失败信息]
 * @param  [type] $status  [处理数据结果]
 * @return [type]          [description]
 */
function doReturn($success = '成功',$error = '失败',$status=true){
    if($status !== false){
        $msg = array(
            "status" => true,
            "info" => $success
        );
    } else {
        $msg = array(
            "status" => false,
            "info" => $error
        );
    }
    return $msg;
}


/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id   数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root='think', $item='item', $attr='', $id='id', $encoding='utf-8') {
    if(is_array($attr)){
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr   = trim($attr);
    $attr   = empty($attr) ? '' : " {$attr}";
    $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml   .= "<{$root}{$attr}>";
    $xml   .= data_to_xml($data, $item, $id);
    $xml   .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed  $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id   数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item='item', $id='id') {
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if(is_numeric($key)){
            $id && $attr = " {$id}=\"{$key}\"";
            $key  = $item;
        }
        $xml    .=  "<{$key}{$attr}>";
        $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
        $xml    .=  "</{$key}>";
    }
    return $xml;
}



/**
 * 返回当前 query string.
 *
 * @return string
 */
function query_string()
{
    return $_SERVER['QUERY_STRING'];
}

/**
 * 把当前 QUERY_STRING分解成数组
 *
 * @return array
 */
function query_string_to_array()
{
    $params = array();
    $query_string = explode('&', query_string());
    foreach ($query_string as $string){
        if (strpos($string, '=')){
            list($key, $value) = explode('=', $string);
            $params[$key] = $value;
        }
    }
    return $params;
}


/**
 * 序列化文本域内容
 * 
 * 
 */
function serial_save($text) 
{
    $texts = explode("\n",$text);
	$arr = array();
	foreach($texts as $value):
		if(!empty($value))
		$arr[] = $value;
	endforeach;
	$str = implode(',',$arr);
	return $str;	
}

function sreial_show($text)
{
    $texts = explode(",",$text);
	$arr = array();
	foreach($texts as $value):
		if(!empty($value))
		$arr[] = $value;
	endforeach;
	$str = implode("\n",$arr);
	return $str;	
}

/**
 * 限制字符串长度,中文字节理解成一个字符
 * 
 */

function char_limit1($str,$val)
{
	$CI = & get_instance();
	if (function_exists('mb_internal_encoding'))
	{
		mb_internal_encoding($CI->config->item('charset'));
	}
    return (mb_strlen($str)>$val) ? mb_substr($str,0,$val) : $str ;    
}

/**
 * 限制字符串长度
 * 
 */
function char_limit2($str,$val)
{
    return (strlen($str)>$val) ? substr($str,0,$val) : $str ;    
}

/**
 * 
 * 
 */

function char_limit3($str,$val)
{
	$CI = & get_instance();
	if (function_exists('mb_internal_encoding'))
	{
		mb_internal_encoding($CI->config->item('charset'));
	}
    return (mb_strlen($str)>$val) ? mb_substr($str,0,$val).'...' : $str ;    
}

/**
 * 过滤html标签并截取
 * @param $str
 * @param $val
 * @return string
 */
function char_limit4($str,$val)
{
    $CI = & get_instance();
    if (function_exists('mb_internal_encoding'))
    {
        mb_internal_encoding($CI->config->item('charset'));
    }
    $str = strip_tags($str);
    return (mb_strlen($str)>$val) ? mb_substr($str,0,$val).'...' : $str ;
}

/**
 * 限制数值的最高值
 * 
 * 
 */

function num_limit($num,$val)
{
	if(!is_numeric($num)){
		return 0;
	}
    return ((float)$num >(float)$val) ? $val : $num;
}


/**
 * 功能：递归创建文件夹
 * 参数：$param 文件路径
 */
function mkdirsByPath($param){
	if(! file_exists($param)) {
		mkdirsByPath(dirname($param));
		@mkdir($param);
	}
	return realpath($param);
}

//获得当前微秒级时间戳
function getMicrosecond(){
    list($usec, $sec) = explode(" ", microtime());
    $str = sprintf("%.6f",$usec);
    $msec=str_replace('0.','', $str);
    return date('YmdHis', time()).$msec;
}

/**
 * 功能：删除非空目录 
 */
function deldir($dir) 
{
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
    closedir($dh);
    if(rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}


/**
 * 功能：两个数组并集 
 */
function array_and($array1=array(), $array2=array()) 
{
   $res = array();   //结果数组
   $res = $array1;   //直接将数组1赋值给结果数组
      
   $arr2 = array_diff($array2,$array1);
   
   $res = array_merge($res , $arr2);

   return is_array($res) ? $res : array();
}


/**
 * 获得当前格林威治时间的时间戳
 *
 * @return  integer
 */
function gmtime()
{
    return (time() - date('Z'));
}

/**
 * 转换field信息为想要的格式
 * @param $data
 */
function convert_field($data){
    $new_data = $info = array();
    if($data){
        $primary_key = '';
        foreach ($data as $k=>$v){
            $info[$v['field']] = json_decode($v['comment'],TRUE);
            if(!$primary_key && $v['key']=="PRI"){
                $primary_key = $v['field'];
            }
        }

        $new_data['info'] = $info;
        $new_data['primary_key'] = $primary_key;
    }

    return $new_data;
}


/**
 * 动态创建工具栏
 */
function build_tools_html($fields,$combtn = array(true,true,true,true)){
            $search = $toolbar = "";
            list($add , $edit , $delete , $refresh) = $combtn;
            foreach ($fields as $k=>$v){
                if(isset($v['list']['search']) && $v['list']['search']===true){
                    $search .= '<option value="'.$k.'">'.$v['obj']['title'].'</option>';
                }

                if(!empty($v['list']['toolbar'])){
                    if(is_string($v['list']['toolbar'])){
                        $style = explode('|',$v['list']['toolbar'])[0];
                        $name = explode('|',$v['list']['toolbar'])[1];
                        $option = implode('|',array_keys($v['obj']['option']));
                        $toolbar .= '<a style="float: left;" class="l-btn l-btn-plain btnOthers" data-id="'.$k.'" data-name="'.$name.'" data-option="'.$option.'"><span class="l-btn-left"><span class="l-btn-text fa '.$style.'" style="font-size:14px"></span><span style="font-size:12px">'.$name.'</span></span></a><div class="datagrid-btn-separator"></div>';
                    }elseif(is_array($v['list']['toolbar'])) {
                        foreach ($v['list']['toolbar'] as $skey=>$sub) {
                            $style = explode('|', $sub)[0];
                            $name = explode('|', $sub)[1];
                            $toolbar .= '<a style="float: left;" class="l-btn l-btn-plain btnOthers" data-id="'.$k.'" data-name="'.$name.'" data-value="'.$skey.'" data-option=""><span class="l-btn-left"><span class="l-btn-text fa ' . $style . '" style="font-size:14px"></span><span style="font-size:12px">' . $name . '</span></span></a><div class="datagrid-btn-separator"></div>';
                        }
                    }
                }
            }

            $tools =  '<div class="mvctool">';
            if($search){
                $tools .=  '<select id="field" name="field" style="float: left;" class="easyui-combobox">';
                $tools .= $search;
                $tools .=  '</select><input id="txtQuery" type="text" class="searchText"/>
                            <a id="btnQuery" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-search" style="font-size:14px"></span><span style="font-size:12px">查询</span></span></a><div class="datagrid-btn-separator"></div>';
            }
            if($add){
                $tools .=  '<a id="btnCreate" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-plus" style="font-size:14px"></span><span style="font-size:12px">新建</span></span></a><div class="datagrid-btn-separator"></div>';
            }
            if($edit){
                $tools .=  '<a id="btnEdit" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-edit" style="font-size:14px"></span><span style="font-size:12px">编辑</span></span></a><div class="datagrid-btn-separator"></div>';
            }
            if($toolbar){
                $tools .= $toolbar;
            }
            if($delete){
                $tools .= '<a id="btnDelete" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-trash" style="font-size:14px"></span><span style="font-size:12px">删除</span></span></a><div class="datagrid-btn-separator"></div>';

            }
            if($refresh){
                $tools .= '<a id="btnReload" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-refresh" style="font-size:14px"></span><span style="font-size:12px">刷新</span></span></a><div class="datagrid-btn-separator"></div>';
            }
            $tools .= '</div>';

            return $tools;
}

/**
 * 动态创建列表
 */
function build_list_html($fields){

        $info = $option = array();
        foreach ($fields as $k=>$v){
            $temp = array();
            if(empty($v['list']['display']) || $v['list']['display']===false){
                continue;
            }else{
                if($v['list']['display']==='hidden'){
                    $temp['hidden'] = true;
                }
            }
            $temp['field'] = $k;
            $temp['title'] = $v['obj']['title'];
            $temp['align'] = 'center';
            $temp['width'] = empty($v['list']['width'])?55:$v['list']['width'];

            if(isset($v['list']['sortable']) && $v['list']['sortable']===true){
                $temp['sortable'] = true;
            }

            if($v['obj']['type']=='image'){
                $temp['formatter'] = "function(value){return show_image(value)}";
            }
            elseif($v['obj']['type']=='radio' || $v['obj']['type']=='select'){
                $option[$k] = $v['obj']['option'];
                $temp['formatter'] = "function(value){return show_field_txt(value,option,'$k')}";
            }
            elseif($v['obj']['type']=='checkbox'){

            }

            $info[] = $temp;
        }
        $info = json_encode($info,TRUE);
        $info = str_replace('"function','function',$info);
        $info = str_replace('}"','}',$info);
        return array($info,$option);
}

/**
 * 动态创建添加页元素
 *
 * @return  string
 */
function build_add_html($editing,$fields)
 {
	 $html = '';

	 foreach ($fields as $k=>$v){
         if(isset($v['add']['display']) && $v['add']['display']!==false){
             $option = isset($v['obj']['option'])?$v['obj']['option']:(isset($v['obj']['with'])?$v['obj']['with']:array());
             $rules = isset($v['add']['rule'])?explode('|',$v['add']['rule']):array();
            if($v['add']['display']==='hidden'){
                    $html .= '<input type="hidden" name="'.$k.'" value="'.$editing[$k].'" >';
            }else{
                    $colspan = $v['obj']['type']=='ueditor'?'colspan="2"':'';
                    $html .= '<tr><th>';
                    $html .= $v['obj']['title'].':';
                    $html .="</th><td $colspan>";
                    $html .= build_attr_button($v['obj']['type'],$k,$editing[$k],$option,$rules);
                    $html .= '</td></tr>';
            }
         }
     }

	 return $html;
 }

function build_attr_button($arrt_type,$attr_id,$default_value="",$option_values=array(),$rules =array()){
     $html = $rule = "";

     foreach ($rules as $r){
         if($r == 'required'){
             $rule .= "required:true,";
         }elseif($r == 'valid_url'){
             $rule .= "validType:'url',";
         }elseif($r == 'valid_email'){
             $rule .= "validType:'email',";
         }
     }


     switch($arrt_type){

         case  'text': $html = '<input style="width:200px;height:28px;" class="easyui-validatebox textbox" name="'.$attr_id.'" data-options="'.$rule.'" value="'.$default_value.'" >';
             break;

         case  'password': $html = '<input style="width:200px;height:28px;padding:10px" iconWidth="28" class="easyui-passwordbox" name="'.$attr_id.'" data-options="'.$rule.'" value="'.$default_value.'" >';
             break;

         case  'number': $html = '<input style="width:200px;height:28px;" class="easyui-validatebox easyui-numberspinner" name="'.$attr_id.'" data-options="min:0,'.$rule.'" value="'.$default_value.'" >';
             break;

         case  'decimal': $html = '<input style="width:200px;height:28px;" class="easyui-validatebox easyui-numberbox" name="'.$attr_id.'" data-options="min:0,precision:2,'.$rule.'" value="'.$default_value.'" >';
             break;

         case  'combotree':
             $html = '<input style="width:200px;height:28px;" class="easyui-combotree" name="'.$attr_id.'" data-options="url:\''.current($option_values).'\','.$rule.'" value="'.$default_value.'" >';
             break;

         case 'textarea': $html = '<textarea rows="4" cols="80" class="easyui-validatebox" name="'.$attr_id.'" data-options="'.$rule.'">'.$default_value.'</textarea>';
             break;

         case 'ueditor':
             $html = '<script type="text/javascript" charset="utf-8" src="'.ASSETS.'Scripts/ueditor1431/ueditor.config.js"></script>
                    <script type="text/javascript" charset="utf-8" src="'.ASSETS.'Scripts/ueditor1431/_examples/editor_api.js"></script>
                    <script type="text/javascript" charset="utf-8" src="'.ASSETS.'Scripts/ueditor1431/lang/zh-cn/zh-cn.js"></script>
                    <script type="text/plain" id="ueditor" name="content" style="width:900px;height:330px;z-index:-1;" >'.$default_value.'</script>
                    <script type="text/javascript">
                        UE.getEditor(\'ueditor\', {
                            setContent:"1",
                            lang:\'zh-cn\'
                        });
                    </script>';
             break;

         case   'radio': foreach($option_values as $key=>$value):
             $html .= ($default_value != $key) ?
                 '<input  class="easyui-validatebox" name="'.$attr_id.'" data-options="'.$rule.'" type="radio" value="'.$key.'"  >'.$value :
                 '<input  class="easyui-validatebox" name="'.$attr_id.'" data-options="'.$rule.'" type="radio" value="'.$key.'"  checked="checked">'.$value.'' ;
                        endforeach;
             break;

         case 'checkbox': foreach($option_values as $key=>$value):
             $default_value = explode(',',$default_value);
             $html .= (!in_array($key,$default_value)) ?
                 '<input class="easyui-validatebox"  name="'.$attr_id.'" data-options="'.$rule.'" type="checkbox" value="'.$key.'"  >'.$value  :
                 '<input class="easyui-validatebox"  name="'.$attr_id.'" data-options="'.$rule.'" type="checkbox" value="'.$key.'" checked="checked" >'.$value;
                        endforeach;
             break;
         case 'singlecheck':
             $html .= '<input class="easyui-validatebox"  name="'.$attr_id.'" data-options="'.$rule.'" type="checkbox" value="1" >';
             break;

         case   'select': $html .= '<select name="'.$attr_id.'"        
		                  class="easyui-validatebox" data-options="'.$rule.'"><option value="">请选择...</option>';
             foreach($option_values as $key=>$value) :
                 $html .= ($default_value != $key) ?
                     '<option value="'.$key.'">'.$value.'</option>':
                     '<option value="'.$key.'" selected="selected" >'.$value.'</option>';
             endforeach;
             $html .='</select>'; break;
         case  'image':
             $default_value = $default_value?UPLOADS.$default_value:ASSETS.'Content/Images/nopic.gif';
             $html = '<div id="localImag">
                        <img class="icon" id="preview" src="'.$default_value.'" style="display: block; width: 140px; height: 140px;" />
                       </div><br />
                       <a href="javascript:$(\'#FileUpload\').trigger(\'click\');void(0);" class="files"></a>
                       <input type="file" class="displaynone" id="FileUpload" name="'.$attr_id.'" onchange="setImagePreview();" />
                       <span class="uploading">请稍候...</span>';
             break;
         case 'mult_image':
             $default_value = explode(',',$default_value);
             $html = '<div id="box" >
                        <div id="test" class="images"></div>
                        <div><img id="showimg" src="" /></div>

                        <div id="preview_img">';
                            if(!empty($default_value)){
                                $imgs=explode(",",$default_value);
                                foreach ($imgs as $img){
                                    $html.='<img  src="'.UPLOADS.$img.'" style="width:150px;height:150px" />';
                                }
                            }
             $html.='</div></div>';
             break;
         case  'date': $html = '<input name="'.$attr_id.'" class="easyui-datebox" data-options="'.$rule.'"  value = "'.$default_value.'" style = "width:200px;height:28px;"/>';
             break;

         case  'datetime': $html = '<input name="'.$attr_id.'" class="easyui-datetimebox" data-options="'.$rule.'"  value = "'.$default_value.'" style = "width:200px;height:28px;"/>';
             break;

     }

     return $html;
 }



 //生成随机码
 function randomCode($nums=4,$str=0)
 {
     $nums = (int)$nums;
     $code = '';
     $str = $str?'0123456789':'23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ';
 
     for ($i = 0; $i < $nums; $i++) {
         $code .= $str{rand(0, strlen($str) - 1)};
     }
     return $code;
 }
 
 //生成文件
 function makefile($dir,$filename,$content='')
 {
    if(!$dir || !$filename){
        return '目录或文件名参数不正确！';
    }

     mkdirsByPath($dir);
    
    if (!file_put_contents($dir.$filename, $content)) {
        return '文件保存失败，请检查文件权限！';
    }
    
    return '';
 }

//获取菜单
function getMenu($items, $id = 'auth_id', $pid = 'parent_id', $son = 'children')
{
    $tree = array();
    $tmpMap = array();

    foreach ($items as $item) {
        $tmpMap[$item[$id]] = $item;
    }

    foreach ($items as $item) {
        if (isset($tmpMap[$item[$pid]])) {
            $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
        } else {
            $tree[] = &$tmpMap[$item[$id]];
        }
    }
    return $tree;
}

function getCurrentMenu(&$items, $url='')
{
    static $data = array(
        'auth_id'=>null,
        'parent_id'=>null,
        'ppid'=>null,
    );

    foreach ($items as $item) {
        if($item['url']==$url){
            $data = $item;
        }elseif(isset($item['children'])){
            getCurrentMenu($item['children'],$url);
        }
    }

    return $data;
}

//处理根据parentid来生成子菜单
function _make_left_menu(&$menus=array(),$parentid=0)
{

    static $left = array();
    foreach ($menus as $v){
        if($v['parent_id']==$parentid){
            $left[] =  $v;
            continue;
        }

        if(isset($v['children'])){
            _make_left_menu($v['children'],$parentid);
        }
    }

    return  $left;
}
 
 //获得当前毫秒级时间戳
 function getMillisecond() {
     list($t1, $t2) = explode(' ', microtime());
     return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
 }
function get_datetime(){
    return  date ( 'Y-m-d H:i:s' ) ;
}

 function sendRequest($url, $paramArray, $method = 'POST')
 {
     $ch = curl_init();
 
     if ($method == 'POST')
     {
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArray);
     }
     else
     {
         $url .= '?' . http_build_query($paramArray);
     }
 
     curl_setopt($ch, CURLOPT_URL, $url);
 
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
     if (false !== strpos($url, "https")) {
         // 证书
         // curl_setopt($ch,CURLOPT_CAINFO,"ca.crt");
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  false);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
     }
     $resultStr = curl_exec($ch);
 
     $result = json_decode($resultStr, true);
//      $result = $resultStr;
     if (!$result)
     {
        return $resultStr;
     }
     
     curl_close ($ch);
     return $result;
 }
 
 /**
  * 获取前台地址
  *
  *
  */
 function front_url()
 {
     $CI =& get_instance();
     return $CI->config->slash_item('front_url');
 }
 
 
/**
 * 获取数组 主键  转换成以主键为索引数组
 * 1=>row(id=>1,.....)
 */
function array_add_index($arr,$key){
    $out=array();
    foreach ($arr as $v){
        $out[($v[$key])]=$v;
    }
    return $out;
}

function is_post(){
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array(
        'javascript',
        'vbscript',
        'expression',
        'applet',
        'meta',
        'xml',
        'blink',
        'link',
        'style',
        'script',
        'embed',
        'object',
        'iframe',
        'frame',
        'frameset',
        'ilayer',
        'layer',
        'bgsound',
        'title',
        'base');
    $ra2 = array(
        'onabort',
        'onactivate',
        'onafterprint',
        'onafterupdate',
        'onbeforeactivate',
        'onbeforecopy',
        'onbeforecut',
        'onbeforedeactivate',
        'onbeforeeditfocus',
        'onbeforepaste',
        'onbeforeprint',
        'onbeforeunload',
        'onbeforeupdate',
        'onblur',
        'onbounce',
        'oncellchange',
        'onchange',
        'onclick',
        'oncontextmenu',
        'oncontrolselect',
        'oncopy',
        'oncut',
        'ondataavailable',
        'ondatasetchanged',
        'ondatasetcomplete',
        'ondblclick',
        'ondeactivate',
        'ondrag',
        'ondragend',
        'ondragenter',
        'ondragleave',
        'ondragover',
        'ondragstart',
        'ondrop',
        'onerror',
        'onerrorupdate',
        'onfilterchange',
        'onfinish',
        'onfocus',
        'onfocusin',
        'onfocusout',
        'onhelp',
        'onkeydown',
        'onkeypress',
        'onkeyup',
        'onlayoutcomplete',
        'onload',
        'onlosecapture',
        'onmousedown',
        'onmouseenter',
        'onmouseleave',
        'onmousemove',
        'onmouseout',
        'onmouseover',
        'onmouseup',
        'onmousewheel',
        'onmove',
        'onmoveend',
        'onmovestart',
        'onpaste',
        'onpropertychange',
        'onreadystatechange',
        'onreset',
        'onresize',
        'onresizeend',
        'onresizestart',
        'onrowenter',
        'onrowexit',
        'onrowsdelete',
        'onrowsinserted',
        'onscroll',
        'onselect',
        'onselectionchange',
        'onselectstart',
        'onstart',
        'onstop',
        'onsubmit',
        'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

function make_batch_insert_sql($table,$colum,$data){

    $sql_val = '';
    foreach ($data as $k=>$v)
    {
        $values = "'" . implode("','", $v) . "'";
        $sql_val .= "($values),";
    }

    $keys = "`" . implode("`,`", $colum) . "`";
    $sql_insert = "insert into `{$table}` ({$keys}) values ".$sql_val;

    $sql_insert = substr($sql_insert,0,strlen($sql_insert)-1);

    return $sql_insert;
}

