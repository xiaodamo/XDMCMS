<?php
/**
 * 自动代码生成
 * Created by PhpStorm.
 * User: xiaodamo
 * Date: 2017/7/26
 * Time: 14:27
 */

class Make_code extends CI_Controller
{

    private $db_fields = array(
        'char',
        'varchar',
        'int',
        'mediumint',
        'smallint',
        'tinyint',
        'decimal',
        'double',
        'enum',
        'float',
        'text',
        'date',
        'datetime',
        'time',
        'timestamp',
    );

    private $html_objs = array(
        'text',
        'password',
        'number',
        'decimal',
        'combotree',
        'textarea',
        'ueditor',
        'radio',
        'checkbox',
        'singlecheck',
        'select',
        'image',
        'mult_image',
        'date',
        'datetime',
    );

    /**
     * 构造函数
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * 列表
     *
     *
     */
    function index()
    {
        $this->config->load('table');
        $content = $this->config->item('table');
        $data['items'] = array_keys($content);
        unset($content);
        $data['html_objs'] = $this->html_objs;
        $data['db_fields'] = $this->db_fields;
        $this->load->view('makecode/list',$data);
    }

    function check_module(){
        $cname = $this->input->post('cname');

        if ($cname){
            $data = array('status'=>true,'info'=>'');
            $res_table = $this->_exist_table($cname);
            switch($res_table){
                case 0:
                    $data['table'] = '';
                    break;
                case 1:
                    $data['table'] = '存在数据表，请确认是否覆盖?';
                    break;
                case 2:
                    $data['table'] = '存在数据表且表中存在数据，请确认是否覆盖?';
                    break;
            }

            $res_model = $this->_exist_model($cname);
            if($res_model){
                $data['model'] = '存在Model，请确认是否覆盖?';
            }else{
                $data['model'] = '';
            }

            $res_controller = $this->_exist_controller($cname);
            if($res_controller){
                $data['controller'] = '存在Controller，请确认是否覆盖?';
            }else{
                $data['controller'] = '';
            }

            $res_view = $this->_exist_view($cname);
            switch($res_view){
                case 0:
                    $data['view'] = '';
                    break;
                case 1:
                    $data['view'] = '存在编辑页View，请确认是否覆盖?';
                    break;
                case 2:
                    $data['view'] = '存在列表页View，请确认是否覆盖?';
                    break;
                case 3:
                    $data['view'] = '存在列表页和编辑页View，请确认是否覆盖?';
                    break;
            }

            $res_auth = $this->_exist_auth($cname);
            if($res_auth){
                $data['auth'] = '存在权限，请确认是否覆盖?';
            }else{
                $data['auth'] = '';
            }

            $res_config = $this->_exist_config($cname);
            if($res_config){
                $data['config'] = '存在配置，请确认是否覆盖?';
            }else{
                $data['config'] = '';
            }

            ajaxReturn($data);

        }

        show_message3('','请至少选择一项配置模块',false);
    }

    function save_fromconfig()
    {
        $cname = $this->input->post('cname');
        if(!$cname) show_message3('','请至少选择一项配置模块',false);
        $this->config->load('table');
        $content = $this->config->item('table');
        if(empty($content[$cname])) show_message3('','读取相关配置错误！',false);

        $data = $this->_make_module($cname,$content[$cname]);

        ajaxReturn($data);
    }

    function save_fromauto()
    {
        $cname = $this->input->post('enname');
        $name = $this->input->post('name');
        $field_enname = $this->input->post('field_enname');
        $field_name = $this->input->post('field_name');
        $field_type = $this->input->post('field_type');
        $field_option = $this->input->post('field_option');
        $field_size = $this->input->post('field_size');
        $field_default = $this->input->post('field_default');
        $is_pri = $this->input->post('is_pri');
        $is_unsign = $this->input->post('is_unsign');
        $html_type = $this->input->post('html_type');
        $list_display = $this->input->post('list_display');
        $is_search = $this->input->post('is_search');
        $field_tools = $this->input->post('field_tools');
        $is_order = $this->input->post('is_order');
        $edit_display = $this->input->post('edit_display');
        $field_rule = $this->input->post('field_rule');
        $count = count($field_enname);
        if($count !== count($field_name) || $count !== count($field_type) || $count !== count($html_type))
            show_message3('','参数有误',false);

        // 加载表单验证类
        $this->load->library('form_validation');
        // 设置表单数据规则
        $this->set_save_form_rules();

        // 如果提交数据符合所设置的规则，则继续运行
        if (TRUE == $this->form_validation->run()){
            $content = array(
                'fields'=>array(),
                'attributes'=>array('ENGINE' => 'InnoDB','DEFAULT CHARACTER SET' => 'utf8', 'COLLATE' => 'utf8_general_ci','COMMENT'=>'"'.$name.'"')
            );
            $fields = array();
            foreach ($field_enname as $k=>$v){
                $fields[$v] = array(
                    'type'=>$field_type[$k],
                    'constraint' => $field_size[$k],
                    'default' => $field_default[$k],
                );
                if(in_array($v,$is_unsign)){
                    $fields[$v]['unsigned'] = TRUE;
                }
                if(in_array($v,$is_pri)){
                    $fields[$v]['auto_increment'] = TRUE;
                    $fields[$v]['primary_key'] = TRUE;
                    unset($fields[$v]['default']);
                }
                $fields[$v]['comment'] = array(
                    'obj'=>array(
                        'type'=>$html_type[$k],
                        'title'=>$field_name[$k],
                        'default'=>$field_default[$k],
                    ),
                    'list'=>array(
                        'display'=>intval($list_display[$k])===1?TRUE:(intval($list_display[$k])===-1?'hidden':FALSE),
                        'search'=>in_array($v,$is_search),
                        'sortable'=>in_array($v,$is_order),
                        'toolbar'=>$field_tools[$k],
                    ),
                    'add'=>array(
                        'display'=>intval($edit_display[$k])===1?TRUE:(intval($edit_display[$k])===-1?'hidden':FALSE),
                        'rule'=>$field_rule[$k],
                    ));
                if($field_option[$k]){
                    $option = explode('|',$field_option[$k]);
                    foreach ($option as $ok=>$ov){
                        $temp_op = explode(':',$ov);
                        $fields[$v]['comment']['obj']['option'][$temp_op[0]] = $temp_op[1];
                    }
                }
            }
            $content['fields'] = $fields;
            $data = $this->_make_module($cname,$content);
            ajaxReturn($data);
        }else{
            //获取错误信息
            $errorinfo = $this->form_validation->error_string();
            show_message3('',$errorinfo,false);
        }
    }

    function make_table($cname,$content){
        $this->load->library('maketable');
        $table_obj = new Maketable($cname);
        return $table_obj->create($content);
    }

    function make_model($cname,$content){

        $module_name = str_replace("\"","",$content['attributes']['COMMENT']);
        $data  = "<?php" . PHP_EOL . PHP_EOL . "require_once APP_COMMON . 'core/Base_Model.php';" . PHP_EOL . PHP_EOL . "/**" . PHP_EOL . " * $module_name". PHP_EOL . " */" . PHP_EOL . "class ".ucfirst($cname)."_model extends Base_Model" . PHP_EOL . "{" . PHP_EOL;
        $data .="   protected \$_table = '$cname';". PHP_EOL;
        $fields = array_keys($content['fields']);
        $creat_str = $primary_key = "";
        if(in_array('created_at',$fields)){
            $creat_str .= "'created_at',";
        }
        if(in_array('updated_at',$fields)){
            $creat_str .= "'updated_at',";
            $data .="   public \$before_update = array( 'updated_at' );". PHP_EOL;
        }
        if($creat_str){
            $data .="   public \$before_create = array(".substr($creat_str,0,strlen($creat_str)-1).");". PHP_EOL;
        }

        $validate ="   public \$validate = array(".PHP_EOL;
        foreach ($content['fields'] as $k=>$v){
            if(!$primary_key && isset($v['primary_key']) && $v['primary_key']===true){
                $primary_key = $k;
            }
            if(!empty($v['comment']['add']['rule'])){
                $validate .= "      array('field'  => '" . $k . "',". PHP_EOL;
                $validate .= "            'label'  => '" . $v['comment']['obj']['title']. "',". PHP_EOL;
                $validate .= "            'rules'  => '" . $v['comment']['add']['rule'] . "'),". PHP_EOL;
            }
        }
        $validate .= PHP_EOL . "    );";
        if($primary_key && $primary_key!="id"){
            $data .= "   protected \$primary_key = '$primary_key';". PHP_EOL;
        }

        $data .= $validate;
        $data .= PHP_EOL . "}";

        $dir = APP_COMMON.'models'.DIRECTORY_SEPARATOR;
        $filename = ucfirst($cname).'_model.php';
        $res = makefile($dir, $filename,$data);
        return !$res;
    }

    function make_controller($cname,$content){
        $module_name = str_replace("\"","",$content['attributes']['COMMENT']);
        $field_arr = array();
        foreach ($content['fields'] as $k=>$v){
            $temp['field'] = $k;
            $temp['key'] = isset($v['primary_key']) && $v['primary_key']===TRUE?'PRI':'';
            $temp['comment'] = json_encode($v['comment'],TRUE);
            $field_arr[] = $temp;
        }

        $fields = array('fields'=>$field_arr,'table_comment'=>$module_name);

        $data  = "<?php" . PHP_EOL . PHP_EOL . "/**" . PHP_EOL . " * $module_name". PHP_EOL . " */" . PHP_EOL . "class ".ucfirst($cname)." extends Base_Controller" . PHP_EOL . "{" . PHP_EOL;
        $data .="    protected \$my_model = '".$cname."_model';". PHP_EOL;
        $data .="    protected \$fields = ".var_export($fields,true).";". PHP_EOL.PHP_EOL;
        $data .="    function __construct()". PHP_EOL . "    {" . PHP_EOL;
        $data .="       parent::__construct();". PHP_EOL . "    }" . PHP_EOL.PHP_EOL;
        $data .= PHP_EOL . "}";

        $dir = APPPATH.'controllers'.DIRECTORY_SEPARATOR;
        $filename = ucfirst($cname).'.php';
        $res = makefile($dir, $filename,$data);
        return !$res;
    }

    function make_view($cname){
        $views = array('list.php','add.php');
        $sucsess = true;
        foreach ($views as $v){
            $data = file_get_contents(APPPATH.'views/common'.DIRECTORY_SEPARATOR.$v);
            $dir = APPPATH.'views'.DIRECTORY_SEPARATOR.$cname.DIRECTORY_SEPARATOR;
            $res = makefile($dir, $v,$data);
            if($res) $sucsess = false;
        }
        return $sucsess;
    }

    function make_config($cname,$content){
        $this->config->load('table');
        $tables = $this->config->item('table');
        $tables[$cname] = $content;
        $data  = "<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');" . PHP_EOL . PHP_EOL . "/**" . PHP_EOL . " * 自动生成数据表" . PHP_EOL . " */" . PHP_EOL;
        foreach ($tables as $k=>$v){
            $data .= "\$config['table']['".$k."'] = ".var_export($v,true).";".PHP_EOL.PHP_EOL;
        }
        $dir = APPPATH.'config'.DIRECTORY_SEPARATOR;
        $res = makefile($dir, 'table.php',$data);
        return !$res;
    }

    function make_auth($cname,$content){
        $func = $this->_get_functions($cname);
        if($func === false) return false;

        $module_name = str_replace("\"","",$content['attributes']['COMMENT']);
        $this->load->model('make_code_model','make');
        $module_info = $this->make->get_by(array('name'=>$module_name.'管理','parent_id'=>0));
        if($module_info){
            $this->make->delete_by(array('parent_id'=>$module_info['auth_id']));
            $id = intval($module_info['auth_id']);
        }else{
            $id = $this->make->insert(array('name'=>$module_name.'管理','parent_id'=>0,'icon'=>'fa fa-fw fa-book','is_menu'=>1));
        }

        if(!$id) return false;

        $methods = array(
            'index'=>$module_name.'列表',
            'add'=>'添加'.$module_name,
            'edit'=>'修改'.$module_name,
            'delete'=>'删除'.$module_name,
            'save'=>'保存'.$module_name.'的表单修改',
            'tangle'=>'修改'.$module_name.'的某一字段值'
        );
        $methods = array_merge($methods,$func);

        $insert_data = array();
        foreach ($methods as $kk=>$vv){
            $temp = array();
            $temp[0] = $vv;
            $temp[1] = 'fa fa-caret-right';
            $temp[2] = intval($id);
            $temp[3] = $cname.'/'.$kk;
            $temp[4] = $kk=='index'?1:0;
            $temp[5] = $cname;
            $temp[6] = $kk;
            $insert_data[] = $temp;
        }
        $res = $this->make->do_sql(make_batch_insert_sql('__auth',array("name","icon","parent_id","url","is_menu","c","a"),$insert_data));

        return $res;
    }

    public function get_field_config(){
        $cname = $this->input->post('cname');
        $this->config->load('table');
        $tables = $this->config->item('table');
        $data = empty($tables[$cname])?array():$tables[$cname];
        $newdata = array(
            'name'=>str_replace("\"","",$data['attributes']['COMMENT']),
            'fields'=>$this->_build_html_objs($data['fields'])
        );
        ajaxReturn($newdata);
    }

    private function _make_module($cname,$content){
        $maketable = intval($this->input->post('maketable'));
        $makemodel = intval($this->input->post('makemodel'));
        $makecontroller = intval($this->input->post('makecontroller'));
        $makeview = intval($this->input->post('makeview'));
        $makeauth = intval($this->input->post('makeauth'));
        $makeconfig = intval($this->input->post('makeconfig'));
        $data = array('status'=>true,'info'=>'','error'=>array());
        if($maketable){
            $res_table = $this->make_table($cname,$content);
            if(!$res_table) $data['error'][] = '生成数据表失败';
        }
        if($makemodel){
            $res_model = $this->make_model($cname,$content);
            if(!$res_model) $data['error'][] = '生成Model失败';
        }
        if($makecontroller){
            $res_controller = $this->make_controller($cname,$content);
            if(!$res_controller) $data['error'][] = '生成Controller失败';
        }
        if($makeview){
            $res_view = $this->make_view($cname);
            if(!$res_view) $data['error'][] = '生成View失败';
        }
        if($makeauth){
            $res_auth = $this->make_auth($cname,$content);
            if(!$res_auth) $data['error'][] = '生成菜单权限失败';
        }
        if($makeconfig){
            $res_config = $this->make_config($cname,$content);
            if(!$res_config) $data['error'][] = '生成配置失败';
        }

        return $data;
    }

    private function _build_html_objs($data){
            $field_txt = '';
            if($data) {
                $l_arr = array('1'=>'显示','0'=>'不显示','-1'=>'隐藏');
                foreach ($data as $k => $v) {
                    $field_txt .= '<table class="formtable fieldform"><tbody>';
                    $field_txt .= '<tr><td><input name="field_enname[]" type="text" class="easyui-validatebox autofield" data-options="required:true" placeholder="字段名(英文)" value="'.$k.'" />';
                    $field_txt .= '</td><td><input name="field_name[]" type="text" class="easyui-validatebox autofield" data-options="required:true" placeholder="字段名(中文)" value="'.$v['comment']['obj']['title'].'" /></td><td>';
                    $field_txt .= '<select name="field_type[]" class="easyui-validatebox" data-options="required:true"><option value="">请选择字段类型</option>';
                    foreach ($this->db_fields as $dv){
                        $db_select  = strtolower($v['type']) == $dv?'selected':'';
                        $field_txt .= '<option value="'.$dv.'" '.$db_select.'>'.$dv.'</option>';
                    }

                    if(!empty($v['comment']['obj']['option']) && is_array($v['comment']['obj']['option'])){
                        $option = array();
                        foreach ($v['comment']['obj']['option'] as $ok=>$ov){
                            $option[] = "$ok:$ov";
                        }
                        $option = implode('|',$option);
                    }else{
                        $option = "";
                    }

                    $field_txt .= '</select></td><td><input name="field_option[]" type="text" class="autofield" placeholder="字段附加项" value="'.$option.'" /></td><td>';
                    $field_txt .= '字段长度:<input name="field_size[]" class="easyui-numberspinner autofield" data-options="min:1" value="'.$v['constraint'].'" /></td><td>';
                    $field_txt .= '<input name="field_default[]" type="text" class="autofield" placeholder="默认值" value="'.$v['default'].'" /></td><td>';
                    $is_pri = isset($v['primary_key']) && $v['primary_key']===true?'checked':'';
                    $field_txt .= '主键？<input name="is_pri[]" type="checkbox" value="1"  '.$is_pri.'/></td><td>';
                    $is_unsign = isset($v['unsigned']) && $v['unsigned']===true?'checked':'';
                    $field_txt .= 'unsigned？<input name="is_unsign[]" type="checkbox" value="1"  '.$is_unsign.'/></td></tr>';
                    $field_txt .= '<tr><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td></tr><tr><td>';
                    $field_txt .= '<select name="html_type[]" class="easyui-validatebox" data-options="required:true"><option value="">请选择页面控件类型</option>';
                    foreach ($this->html_objs as $ov){
                        $o_select  = strtolower($v['comment']['obj']['type']) == $ov?'selected':'';
                        $field_txt .= '<option value="'.$ov.'" '.$o_select.'>'.$ov.'</option>';
                    }
                    $field_txt .= '</select></td><td>';
                    $field_txt .= '<select name="list_display[]" >';
                    $ldis = $v['comment']['list']['display']==='hidden'?-1:intval($v['comment']['list']['display']);
                    foreach ($l_arr as $lk=>$lv){
                        $l_select  = $ldis == $lk?'selected':'';
                        $field_txt .= '<option value="'.$lk.'" '.$l_select.'>列表页'.$lv.'</option>';
                    }
                    $field_txt .= '</select></td><td>';
                    $field_txt .= '<input name="field_tools[]" type="text" class="autofield" placeholder="列表工具栏" value="'.$v['comment']['list']['toolbar'].'" /></td><td>';
                    $is_order  = isset($v['comment']['list']['sortable']) && $v['comment']['list']['sortable']===true?'checked':'';
                    $field_txt .= '列表排序？<input name="is_order[]" type="checkbox" value="1"  '.$is_order.'/></td><td>';
                    $is_search  = isset($v['comment']['list']['search']) && $v['comment']['list']['search']===true?'checked':'';
                    $field_txt .= '搜索项？<input name="is_search[]" type="checkbox" value="1" '.$is_search.'/></td><td>';
                    $field_txt .= '<select name="edit_display[]" >';

                    $edis = $v['comment']['add']['display']==='hidden'?-1:intval($v['comment']['add']['display']);
                    foreach ($l_arr as $ek=>$ev){
                        $e_select  = $edis == $ek?'selected':'';
                        $field_txt .= '<option value="'.$ek.'" '.$e_select.'>编辑页'.$ev.'</option>';
                    }
                    $field_txt .= '</select></td><td>';
                    $field_txt .= '<input name="field_rule[]" type="text" class="autofield" placeholder="编辑页验证规则" value="'.$v['comment']['add']['rule'].'" /></td><td>';
                    $field_txt .= '<a class="easyui-linkbutton" iconCls="fa fa-trash" onClick="del_point(this)"></a></td></tr>';
                    $field_txt .= '</tbody></table>';
                }
            }

            return $field_txt;
    }

    /**
     * 获取controller的方法
     * @param $cname
     * @return mixed
     */
    private function _get_functions($cname){
        $cname = ucfirst($cname);
        $path = APPPATH.'controllers'.DIRECTORY_SEPARATOR.$cname.'.php';
        if(!file_exists($path)) return false;
        require_once ($path);
        $info = get_class_methods($cname);
        $except = array('index','add','edit','delete','save','tangle','__construct','get_instance','call_func_index','call_func_edit','call_func_save');
        $data = array();
        foreach ($info as $k=>$v){
            if(!in_array($v,$except)){
                $data[$v] = $v;
            }
        }
        return $data;
    }

    private function _exist_table($cname){
        $cname = $this->db->escape_str($cname);
        $this->load->model('make_code_model','make');
        $dbname = $this->db->database;
        $table =  $this->make->query_sql("SELECT table_name FROM information_schema.TABLES WHERE table_name ='__$cname' and table_schema = '$dbname' LIMIT 1");
        if(!$table) return 0;

        $content =  $this->make->query_sql("SELECT * FROM __$cname LIMIT 1");
        if($content) return 2;

        return 1;
    }

    private function _exist_controller($cname){
        $cname = ucfirst($cname);
        return file_exists(APPPATH.'controllers/'.$cname.'.php');
    }

    private function _exist_model($cname){
        $cname = ucfirst($cname);
        return file_exists(APP_COMMON.'models/'.$cname.'_model.php');
    }

    private function _exist_view($cname){
        $list =  file_exists(APPPATH.'views/'.$cname.'/list.php');
        $add =   file_exists(APPPATH.'views/'.$cname.'/add.php');
        if($list && $add)
            return 3;
        elseif($list){
            return 2;
        }elseif($add){
            return 1;
        }else{
            return 0;
        }
    }

    private function _exist_auth($cname){
        $cname = $this->db->escape_str($cname);
        $this->load->model('make_code_model','make');
        return  $this->make->query_sql("SELECT * FROM __auth WHERE `c`='$cname' LIMIT 1");
    }

    private function _exist_config($cname){
        $this->config->load('table');
        $content = $this->config->item('table');
        return isset($content[$cname]);
    }

    /**
     * 设置表单数据规则
     *
     */
    private function set_save_form_rules()
    {
        $this->form_validation->set_rules('enname', '模块英文标识', 'trim|alpha_dash|required');
        $this->form_validation->set_rules('name', '模块名(中文)', 'trim|required');
        $this->form_validation->set_rules('field_enname[]', '字段名(英文)', 'required');
        $this->form_validation->set_rules('field_name[]', '字段名(中文)', 'required');
        $this->form_validation->set_rules('field_type[]', '字段类型', 'required');
        //$this->form_validation->set_rules('field_size[]', '字段长度', 'required');
        $this->form_validation->set_rules('html_type[]', '页面控件类型', 'required');
    }

    // --------------------------------------------------------------------

}