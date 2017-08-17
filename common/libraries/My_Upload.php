<?php


class My_Upload{

    private $__CI;

    public $_field = "";

    public $_module;

    public $_id;

    public $_filename = '';

    public $_type = 'gif|jpg|png|bmp|jpeg';

    public $_size = '10000';

    public $_del_old = TRUE;


    function __construct()
    {

    }


    function do_upload(){
        if (!empty($_FILES[$this->_field]['name'])){
            $this->__CI = &get_instance();
            // 定义保存位置
            $save_path = $this->_module.'/'.$this->_id.'/';
            $path = '..'.UPLOADS.$save_path;
            mkdirsByPath($path);

            // CI文件上传类 数据初始化
            if(!$this->_filename){
                $config['file_name']  = getMillisecond();
            }else{
                $config['file_name'] = $this->_filename;
            }
            $config['upload_path']  = $path ;
            $config['overwrite']  = TRUE ;
            $config['allowed_types'] = $this->_type;
            $config['max_size'] = $this->_size;
            $config['max_filename'] = '50';
            $this->__CI->load->library('upload', $config);

            if (!$this->__CI->upload->do_upload( $this->_field))
            {
                show_message3('',$this->__CI->upload->display_errors(),false);
            }

            $uploaded = $this->__CI->upload->data();
            $file_url = $save_path.$uploaded['file_name'];

            if($this->_del_old && !$this->_filename){
                //删除原文件
                $this->__CI->load->model($this->_module.'_model');
                $model = $this->_module.'_model';
                $info = $this->__CI->$model->get($this->_id);
                if (file_exists('..'.UPLOADS.$info[ $this->_field])){
                    @unlink('..'.UPLOADS.$info[ $this->_field]);
                }
            }

            return $file_url;
        }

        show_message3('','参数错误！',false);
    }

}

