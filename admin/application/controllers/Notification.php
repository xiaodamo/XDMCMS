<?php
/**
 * 通知
 *
 *
 */
class Notification extends CI_Controller
{
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

        $this->load->model('notification_model');
        $id = 1;
        $data['editing'] = $this->notification_model->load($id);

        if (!$data['editing']){
            show_message1('无效ID:'.$id);
        }

        $this->load->view('notification/add',$data);

    }

    // --------------------------------------------------------------------

    /**
     * 提交数据
     *
     *
     */
    function save()
    {
        //id
        $id = (int)$this->input->post('id');
        $link_url = $this->input->post('link_url',true);
        $content = $this->input->post('content') ;

        // 加载表单验证类
        $this->load->library('form_validation');

        // 设置表单数据规则
        $this->set_save_form_rules();

        // 如果提交数据符合所设置的规则，则继续运行
        if (TRUE == $this->form_validation->run()){

            //把数据提交给模型
            $this->load->model('notification_model');

            $data['link_url'] = $link_url;
            $data['content'] = $content;

            // 更新管理员资料
            if ($id){

                $data['id'] = $id;

                $res = $this->notification_model->update($data);

                if ($res){
                    show_message3('操作成功');
                }else{
                    show_message3('操作成功','操作失败',false);
                }

                // 添加新管理员
            } else {
                $res = $this->notification_model->create($data);
                if($res){
                    show_message3('操作成功');
                }
                show_message3('操作成功','操作失败',false);
            }

            // 对提交的数据不符合表单验证规则情况的处理
        }else{
            //获取错误信息
            $errorinfo = $this->form_validation->error_string();
            show_message3('操作成功',$errorinfo,false);
        }
    }

    // --------------------------------------------------------------------

    /**
     * 审核
     *
     *
     */
    function review()
    {
        $id = (int)$this->input->post('id');
        $review = intval($this->input->post('review'));
        if(!in_array($review,array(0,1))) show_message3('操作成功','操作失败:参数错误',false);

        $review = $review?2:3;

        if ($id > 0){
            $this->load->model('notification_model');
            $res = $this->notification_model->update_one(array('status'=>$review),$id);

            if ($res){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','操作失败:'.$id,false);
            }
        }

        show_message3('操作成功','无效ID:'.$id,false);
    }


    /**
     * 审核
     *
     *
     */
    function recommand()
    {
        $id = (int)$this->input->post('id');
        $recommand = intval($this->input->post('recommand'));

        if ($id > 0){
            $this->load->model('notification_model');
            $res = $this->notification_model->update_one(array('is_recommand'=>$recommand),$id);

            if ($res){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','操作失败:'.$id,false);
            }
        }

        show_message3('操作成功','无效ID:'.$id,false);
    }

    /**
     * 删除
     *
     *
     */
    function delete()
    {
        $params = $this->uri->uri_to_assoc(3);
        if (isset($params['id']) && ($id = $params['id']) > 0){

            $this->load->model('notification_model');
            if ($this->notification_model->delete($id)){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','无效ID:'.$id,false);
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * 设置表单数据规则
     *
     */
    function set_save_form_rules()
    {
        $this->form_validation->set_rules('content', '内容', 'trim|required');
    }


}