<?php
/*
 * base文件
 */
class Base_Controller extends CI_Controller
{

    protected $my_model;
    protected $fields;
    protected $combtn = array(true,true,true,true);//$add , $edit , $delete , $refresh
    protected $per_page = 15;
    protected $condition;
    protected $self_view;
    protected $call_func_index;
    protected $call_func_edit;
    protected $call_func_save;
    /**
     * 构造函数
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
        $this->load->model($this->my_model);
        $model = $this->my_model;

        $per_page=$this->input->get_post('rows');
        $per_page = empty( $per_page) ? $this->per_page : $per_page;//每页显示的记录数
        //当前页
        $p = (int)$this->input->get_post('page');
        $p = $p?$p:1;

        //排序
        $sort=$this->input->get_post('sort');
        $order=$this->input->get_post('order');

        if(!$this->condition){
            $field = $this->input->get_post('field');
            $value = $this->input->get_post('queryStr',true);
            $this->condition = $field && $value?"$field like '%$value%'":" 1=1 ";
        }

        //总条数
        $counts =  $this->$model->count_by($this->condition);
        $infos = $this->$model->limit($per_page, ($p-1)*$per_page)->order_by($sort,$order)->get_many_by($this->condition);

        if($this->call_func_index){
            $infos = call_user_func_array(array($this,$this->call_func_index),array($infos));
        }

        $data = array(
            'rows'  => $infos,
            'total' => $counts,
        );

        if (is_post()) {
            ajaxReturn($data);
        }else{
            $action = substr($this->my_model, 0, strrpos($this->my_model, '_'));
            if(!$this->fields){
                $this->load->library('maketable');
                $table_obj = new Maketable($action);
                $this->fields = $table_obj->read_table();
            }

            $fields = convert_field($this->fields['fields']);

            $data['tools'] = build_tools_html($fields['info'],$this->combtn);
            list($list,$option) = build_list_html($fields['info']);
            $data['lists'] = $list;
            $data['option'] = $option;
            $data['id'] = $fields['primary_key'];
            $data['action_name'] = $action;
            $data['action_title'] = $this->fields['info']['table_comment'];
            if($this->self_view){
                $this->load->view($action.'/list',$data);
            }else{
                $this->load->view('common/list',$data);
            }
        }

    }

    // --------------------------------------------------------------------

    /**
     * 添加
     *
     *
     */
    function add()
    {
        $this->edit();
    }


    // --------------------------------------------------------------------

    /**
     * 编辑
     *
     *
     */
    function edit()
    {
        $params = $this->uri->uri_to_assoc(3);
        $action = substr($this->my_model, 0, strrpos($this->my_model, '_'));
        if(!$this->fields){
            $this->load->library('maketable');
            $table_obj = new Maketable($action);
            $this->fields = $table_obj->read_table();
        }

        $has_record = false;
        if (!empty($params['id']) && $params['id'] > 0){
            $id = $params['id'];
            $this->load->model($this->my_model);
            $model = $this->my_model;
            $editing = $this->$model->get($id);
            $has_record = true;
            if (!$editing){
                show_message1('无效ID:'.$id);
            }
        }

        $fields = array();
        foreach ($this->fields['fields'] as $k=>$v){
            $fields[$v['field']] = $comment = json_decode($v['comment'],TRUE);
            if(!$has_record){
                $editing[$v['field']] = isset($comment['obj']['default'])?$comment['obj']['default']:null;
            }
        }

        //执行回调函数
        if($this->call_func_edit){
            $editing = call_user_func_array(array($this,$this->call_func_edit),array($editing));
        }

        $data['action_name'] = $action;
        $data['attr_info'] = build_add_html($editing,$fields);
        if($this->self_view){
            $this->load->view($action.'/add',$data);
        }else{
            $this->load->view('common/add',$data);
        }
    }

    // --------------------------------------------------------------------

    /**
     * 保存表单修改
     *
     *
     */
    function save()
    {
        $action = substr($this->my_model, 0, strrpos($this->my_model, '_'));

        if(!$this->fields){
            $this->load->library('maketable');
            $table_obj = new Maketable($action);
            $this->fields = $table_obj->read_table();
        }

        $info = convert_field($this->fields['fields']);
        $fields = array_keys($info['info']);
        $id = (int)$this->input->post($info['primary_key']);
        $data = array();
        foreach ($_POST as $k=>$v){
            if(!in_array($k,$fields) || $k==$info['primary_key']){
                continue;
            }
            if(!$id && $k==$info['primary_key']){
                continue;
            }

            if(in_array($info['info'][$k]['obj']['type'],array('image','file'))){
                if($v) $data[$k] = remove_xss($v);
            }
            elseif($info['info'][$k]['obj']['type']=='mult_image'){
                $data[$k] = implode(",",remove_xss($v));
                unset($_FILES[$k]);
            }
            elseif($info['info'][$k]['obj']['type']=='checkbox'){
                $data[$k] = implode(",",remove_xss($v));
            }
            elseif($info['info'][$k]['obj']['type']=='number'){
                $data[$k] = intval($v);
            }
            elseif($info['info'][$k]['obj']['type']=='ueditor'){
                $data[$k] = $v;
            }
            else{
                $data[$k] = remove_xss($v);
            }
        }

        //把数据提交给模型
        $this->load->model($this->my_model);
        $model = $this->my_model;
        // 更新
        if ($id){
            //文件上传情况
            if($_FILES){
                $this->load->library('my_upload');
                $upload_obj = new My_Upload();
                foreach ($_FILES as $fk=>$fv){
                    $upload_obj->_field = $fk;
                    $upload_obj->_id = $id;
                    $upload_obj->_module = $action;
                    $data[$fk] = $upload_obj->do_upload();
                }
            }

            $res = $this->$model->update($id,$data);
            if ($res){
                //执行回调函数
                if($this->call_func_save){
                    call_user_func_array(array($this,$this->call_func_edit),array($data));
                }
                show_message3('操作成功');
            }else{
                show_message3('操作成功','操作失败',false);
            }

        // 添加
        } else {
            if(!$data && $_FILES){
                $data = array(key($_FILES)=>'');
            }
            $res = $this->$model->insert($data);
            if($res){
                //文件上传情况
                if($_FILES){
                    $this->load->library('my_upload');
                    $upload_obj = new My_Upload();
                    foreach ($_FILES as $fk=>$fv){
                        $upload_obj->_field = $fk;
                        $upload_obj->_id = $res;
                        $upload_obj->_module = $action;
                        $file_name = $upload_obj->do_upload();
                        $this->$model->update($res,array($fk=>$file_name));
                    }
                }
                //执行回调函数
                if($this->call_func_save){
                    $data[$info['primary_key']] = $res;
                    call_user_func_array(array($this,$this->call_func_edit),array($data));
                }
                show_message3('操作成功！');
            }
            show_message3('操作成功','操作失败',false);
        }

    }

    /**
     * 删除
     *
     *
     */
    function delete()
    {
        $params = $this->uri->uri_to_assoc(3);
        if (!empty($params['id'])){
            $id = $params['id'];
            $this->load->model($this->my_model);
            $model = $this->my_model;
            if ($this->$model->delete($id)){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','操作失败:'.$id,false);
            }
        }

        show_message3('操作成功','无效ID:'.$id,false);
    }

    /**
     * 改变字段值
     *
     *
     */
    function tangle()
    {
        $id = (int)$this->input->post('id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');

        if ($id > 0){
            $this->load->model($this->my_model);
            $model = $this->my_model;
            $this->$model->skip_validation();
            $res = $this->$model->update($id,array($field=>$value));

            if ($res){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','操作失败:'.$id,false);
            }
        }

        show_message3('操作成功','无效ID:'.$id,false);
    }

}