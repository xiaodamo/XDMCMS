<?php

class Mpage
{
    // 起始行数
    public $firstRow = '';
    // 列表每页显示行数
    public $listRows = '';
    // 页数跳转时要带的参数
    public $parameter = '';
    // 分页总页面数
    protected $totalPages = '';
    // 总行数
    protected $totalRows = '';
    // 当前页数
    protected $nowPage = 1;
    // 分页的栏的总页数
    protected $coolPages;
    // 分页栏每页显示的页数
    protected $rollPage = 10;
    // 默认跳转
    protected $baseurl = '';

    protected $basetype = 0;
    
    // 分页显示定制
    // protected $config = array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first% %prePage% %linkPage% %nextPage% %end%');
    
    /**
     * +----------------------------------------------------------
     * 架构函数
     * +----------------------------------------------------------
     * 
     * @access public
     *         +----------------------------------------------------------
     * @param array $totalRows
     *            总的记录数
     * @param array $listRows
     *            每页显示记录数
     * @param array $parameter
     *            分页跳转的参数
     *            +----------------------------------------------------------
     */
    public function Mpages($totalRows, $listRows, $baseurl = '', $basetype = 0, $p = '')
    {
        $this->totalRows = $totalRows;
        $this->baseurl = $baseurl;
        $this->basetype = $basetype;
        $this->listRows = ! empty($listRows) ? $listRows : 10;
        $this->totalPages = ceil($this->totalRows / $this->listRows); // 总页数
        $this->coolPages = ceil($this->totalPages / $this->rollPage);
        
        if ($p) {
            $this->nowPage = $p;
        } else {
            $this->nowPage = ! empty($_GET['p']) ? intval($_GET['p']) : 1;
        }
        
        if ($this->nowPage < 1)
            $this->nowPage = 1;
        if (! empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows * ($this->nowPage - 1);
    }

    /**
     * +----------------------------------------------------------
     * 分页显示输出
     * +----------------------------------------------------------
     * 
     * @access public
     *         +----------------------------------------------------------
     */
    public function show()
    {
        if (0 == $this->totalRows || $this->totalRows<=$this->listRows)
            return '';
        $p = '';
        $nowCoolPage = ceil($this->nowPage / $this->rollPage);
        $geturl = explode('?', $_SERVER['REQUEST_URI']);
        $url = $geturl[0];
        $baseurl = $this->baseurl;
        $explode_baseurl = explode('?', $baseurl);
        $pre_explode_baseurl = $explode_baseurl[0];
        $param_explode_baseurl = isset($explode_baseurl[1])?$explode_baseurl[1]:'';
        $this->baseurl = $pre_explode_baseurl;
        if (strlen($param_explode_baseurl) == 0) {
            $origin_param = '';
        } else {
            $origin_param = "$param_explode_baseurl&";
        }
        
        $pageStr = '';
        
        switch ($this->basetype) {
            case 0: // 默认的样式
                $pageStr.= "<div class='col-xs-6'>";
                $pageStr.= "<div class='dataTables_info' id='sample-table-2_info'>";
                $pageStr.= "共".$this->totalRows."条记录,当前第".$this->nowPage."页,每页".$this->listRows."条";
                $pageStr.= "</div>";
                $pageStr.= "</div>";
                $pageStr.= "<div class='col-xs-6'>";
                $pageStr.= "<div class='dataTables_paginate paging_bootstrap'>";
                $pageStr.= "<ul class='pagination'>";
                
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        $pageStr .= "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
                        $pageStr .= "<li class='prev disabled'><a href='#'><i class='fa fa-angle-left'></i></a></li>";
                    } else {
                        $pageStr .= "<li class='prev'><a href='" . $this->baseurl . "?{$origin_param}p=1' title='第一页'><i class='fa fa-angle-double-left'></i></a></li> ";
                        $pageStr .= "<li class='prev'><a href='" . $this->baseurl . "?{$origin_param}p=" . ($this->nowPage - 1) . "' title='上一页'><i class='fa fa-angle-left'></i></a></li> ";
                    }
                }
                $s = $this->nowPage < 5 ? 1 : $this->nowPage - 4;
                $t = $this->nowPage < $this->totalPages - 5 ? $this->nowPage + 5 : $this->totalPages + 1;
                for ($i = $s; $i < $t; $i ++) {
                    $pageStr .= "<li";
                    if ($i == $this->nowPage) {
                        $pageStr .= " class='active'";
                    }
                    $pageStr .= "><a href='" . $this->baseurl . "?{$origin_param}p=" . $i . "' ";
                    $pageStr .= ">" . $i . "</a></li>";
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr .= "<li class='next disabled'><a href='#'><i class='fa fa-angle-right'></i></a></li>";
                        $pageStr .= "<li class='next disabled'><a href='#'><i class='fa fa-angle-double-right'></i></a></li>";
                    } else {
                        $pageStr = $pageStr . " <li class='next'><a href='" . $this->baseurl . "?{$origin_param}p=" . ($this->nowPage + 1) . "' title='下一页'><i class='fa fa-angle-right'></i></a></li> ";
                        $pageStr = $pageStr . " <li class='next'><a href='" . $this->baseurl . "?{$origin_param}p=" . $this->totalPages . "' title='最后页'><i class='fa fa-angle-double-right'></i></a></li> ";
                    }
                }
                $pageStr.= "</ul>";
                $pageStr.= "</div>";
                
                break;
            case 1: // 其他样式(移动端样式)
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        $pageStr = $pageStr . " <a>‹</a> ";
                    } else {
                        $pageStr = $pageStr . "<a href='$url?p=" . ($this->nowPage - 1) . "' title='上一页'>‹</a> ";
                    }
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr = $pageStr . " <a>›</a> ";
                    } else {
                        $pageStr = $pageStr . " <a href='$url?p=" . ($this->nowPage + 1) . "' title='下一页'>›</a> ";
                    }
                }
                break;
            
            case 10: // 后台样式
                $pageStr = "<form action='" . $this->baseurl . "' method='get'>";
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        $pageStr = $pageStr . "<a>第一页</a> ";
                        $pageStr = $pageStr . " <a>上一页</a> ";
                    } else {
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "/1' title='第一页'>第一页</a> ";
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "/" . ($this->nowPage - 1) . "' title='上一页'>上一页</a> ";
                    }
                }
                $s = $this->nowPage < 5 ? 1 : $this->nowPage - 4;
                $t = $this->nowPage < $this->totalPages - 5 ? $this->nowPage + 5 : $this->totalPages + 1;
                for ($i = $s; $i < $t; $i ++) {
                    $pageStr .= "&nbsp;<a href='" . $this->baseurl . "/" . $i . "' ";
                    if ($i == $this->nowPage) {
                        $pageStr .= " style='color:#ff0000'";
                    }
                    $pageStr .= ">[" . $i . "]</a> &nbsp;";
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr = $pageStr . " <a>下一页</a> ";
                        $pageStr = $pageStr . " <a>最后页</a> ";
                    } else {
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "/" . ($this->nowPage + 1) . "' title='下一页'>下一页</a> ";
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "/" . $this->totalPages . "' title='最后页'>最后页</a> ";
                    }
                    // $pageStr = $pageStr . "&nbsp;到第 <input onkeydown='onlyNum();' name='p' type='text' id='p' size='1' /> 页 <input type='submit' name='Submit' value='确定' />";
                }
                $pageStr = $pageStr . "</form>";
                break;
            
            default:
        }
        
        return $pageStr;
    }
    // url get 已有参数传递
    public function show_get_param()
    {
        if (0 == $this->totalRows)
            return '';
        $p = '';
        $nowCoolPage = ceil($this->nowPage / $this->rollPage);
        $geturl = explode('?', $_SERVER['REQUEST_URI']);
        $url = $geturl[0];
        // var_dump($url);var_dump($geturl[1]);var_dump($this->basetype);
        // $this->firstRow = ($this->nowPage)*($this->$listRows);
        // echo($this->$listRows);
        $baseurl = $this->baseurl;
        $explode_baseurl = explode('?', $baseurl);
        $pre_explode_baseurl = $explode_baseurl[0];
        $param_explode_baseurl = $explode_baseurl[1];
        // var_dump($this->basetype);var_dump($this->baseurl);var_dump($pre_explode_baseurl);var_dump($param_explode_baseurl);
        $this->baseurl = $pre_explode_baseurl;
        $hidden_str = '';
        if (strlen($param_explode_baseurl) == 0) {
            $origin_param = '';
        } else {
            $origin_param = "$param_explode_baseurl&";
            $hiden_data = explode('&', $param_explode_baseurl);
            if ($hiden_data) {
                foreach ($hiden_data as $item) {
                    if ($item) {
                        $item_explode = explode('=', $item);
                        $hidden_str .= "<input type='hidden' name='{$item_explode[0]}' value='{$item_explode[1]}'>";
                    }
                }
            }
        }
        
        // exit;
        // 首页部分
        $pageStr = '';
        
        switch ($this->basetype) {
            case 0: // 默认的样式
                $pageStr = "<form action='" . $this->baseurl . "' method='get'>";
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        $pageStr = $pageStr . "<a>第一页</a> ";
                        $pageStr = $pageStr . " <a>上一页</a> ";
                    } else {
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "?{$origin_param}p=1' title='第一页'>第一页</a> ";
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "?{$origin_param}p=" . ($this->nowPage - 1) . "' title='上一页'>上一页</a> ";
                    }
                }
                $s = $this->nowPage < 5 ? 1 : $this->nowPage - 4;
                $t = $this->nowPage < $this->totalPages - 5 ? $this->nowPage + 5 : $this->totalPages + 1;
                for ($i = $s; $i < $t; $i ++) {
                    $pageStr .= "&nbsp;<a href='" . $this->baseurl . "?{$origin_param}p=" . $i . "' ";
                    if ($i == $this->nowPage) {
                        $pageStr .= " style='color:#ff0000'";
                    }
                    $pageStr .= ">[" . $i . "]</a> &nbsp;";
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr = $pageStr . " <a>下一页</a> ";
                        $pageStr = $pageStr . " <a>最后页</a> ";
                    } else {
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "?{$origin_param}p=" . ($this->nowPage + 1) . "' title='下一页'>下一页</a> ";
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "?{$origin_param}p=" . $this->totalPages . "' title='最后页'>最后页</a> ";
                    }
                    $pageStr = $pageStr . "&nbsp;到第 <input  onkeydown='onlyNum();' name='p' type='text' id='p' size='1' /> 页 <input type='submit' name='Submit' value='确定' />";
                }
                
                $pageStr = $pageStr . $hidden_str . "</form>";
                
                break;
            case 1: // 其他样式(移动端样式)
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        $pageStr = $pageStr . " <a><</a> ";
                    } else {
                        $pageStr = $pageStr . "<a href='$url?p=" . ($this->nowPage - 1) . "' title='上一页'>上一页</a> ";
                    }
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr = $pageStr . " <a>›</a> ";
                    } else {
                        $pageStr = $pageStr . " <a href='$url?p=" . ($this->nowPage + 1) . "' title='下一页'>下一页</a> ";
                    }
                }
                break;
            
            case 10: // 后台样式
                $pageStr = "<form action='" . $this->baseurl . "' method='get'>";
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        $pageStr = $pageStr . "<a>第一页</a> ";
                        $pageStr = $pageStr . " <a>上一页</a> ";
                    } else {
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "/1' title='第一页'>第一页</a> ";
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "/" . ($this->nowPage - 1) . "' title='上一页'>上一页</a> ";
                    }
                }
                $s = $this->nowPage < 5 ? 1 : $this->nowPage - 4;
                $t = $this->nowPage < $this->totalPages - 5 ? $this->nowPage + 5 : $this->totalPages + 1;
                for ($i = $s; $i < $t; $i ++) {
                    $pageStr .= "&nbsp;<a href='" . $this->baseurl . "/" . $i . "' ";
                    if ($i == $this->nowPage) {
                        $pageStr .= " style='color:#ff0000'";
                    }
                    $pageStr .= ">[" . $i . "]</a> &nbsp;";
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr = $pageStr . " <a>下一页</a> ";
                        $pageStr = $pageStr . " <a>最后页</a> ";
                    } else {
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "/" . ($this->nowPage + 1) . "' title='下一页'>下一页</a> ";
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "/" . $this->totalPages . "' title='最后页'>最后页</a> ";
                    }
                    // $pageStr = $pageStr . "&nbsp;到第 <input onkeydown='onlyNum();' name='p' type='text' id='p' size='1' /> 页 <input type='submit' name='Submit' value='确定' />";
                }
                $pageStr = $pageStr . "</form>";
                break;
            
            default:
        }
        
        return $pageStr;
    }
    
    // url get 已有参数传递
    public function mini_show_get_param()
    {
        if (0 == $this->totalRows)
            return '';
        $p = '';
        $nowCoolPage = ceil($this->nowPage / $this->rollPage);
        $geturl = explode('?', $_SERVER['REQUEST_URI']);
        $url = $geturl[0];
        // var_dump($url);var_dump($geturl[1]);var_dump($this->basetype);
        // $this->firstRow = ($this->nowPage)*($this->$listRows);
        // echo($this->$listRows);
        $baseurl = $this->baseurl;
        $explode_baseurl = explode('?', $baseurl);
        $pre_explode_baseurl = $explode_baseurl[0];
        $param_explode_baseurl = $explode_baseurl[1];
        // var_dump($this->basetype);var_dump($this->baseurl);var_dump($pre_explode_baseurl);var_dump($param_explode_baseurl);
        $this->baseurl = $pre_explode_baseurl;
        $hidden_str = '';
        if (strlen($param_explode_baseurl) == 0) {
            $origin_param = '';
        } else {
            $origin_param = "$param_explode_baseurl&";
            $hiden_data = explode('&', $param_explode_baseurl);
            if ($hiden_data) {
                foreach ($hiden_data as $item) {
                    if ($item) {
                        $item_explode = explode('=', $item);
                        $hidden_str .= "<input type='hidden' name='{$item_explode[0]}' value='{$item_explode[1]}'>";
                    }
                }
            }
        }
        
        // exit;
        // 首页部分
        $pageStr = '';
        
        switch ($this->basetype) {
            case 0: // 默认的样式
                $pageStr = "<form action='" . $this->baseurl . "' method='get'>";
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        // $pageStr = $pageStr . "<a>第一页</a> ";
                        $pageStr = $pageStr . " <a>上一页</a> ";
                    } else {
                        // $pageStr = $pageStr . "<a href='".$this->baseurl."?{$origin_param}p=1' title='第一页'>第一页</a> ";
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "?{$origin_param}p=" . ($this->nowPage - 1) . "' title='上一页'>上一页</a> ";
                    }
                }
                $s = $this->nowPage < 5 ? 1 : $this->nowPage - 4;
                $t = $this->nowPage < $this->totalPages - 5 ? $this->nowPage + 5 : $this->totalPages + 1;
                for ($i = $s; $i < $t; $i ++) {
                    $pageStr .= "&nbsp;<a href='" . $this->baseurl . "?{$origin_param}p=" . $i . "' ";
                    if ($i == $this->nowPage) {
                        $pageStr .= " style='color:#ff0000'";
                    }
                    $pageStr .= ">[" . $i . "]</a> &nbsp;";
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr = $pageStr . " <a>下一页</a> ";
                        // $pageStr = $pageStr . " <a>最后页</a> ";
                    } else {
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "?{$origin_param}p=" . ($this->nowPage + 1) . "' title='下一页'>下一页</a> ";
                        // $pageStr = $pageStr . " <a href='".$this->baseurl."?{$origin_param}p=".$this->totalPages."' title='最后页'>最后页</a> ";
                    }
                    // $pageStr = $pageStr . "&nbsp;到第 <input onkeydown='onlyNum();' name='p' type='text' id='p' size='1' /> 页 <input type='submit' name='Submit' value='确定' />";
                }
                
                $pageStr = $pageStr . $hidden_str . "</form>";
                
                break;
            case 1: // 其他样式(移动端样式)
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        $pageStr = $pageStr . " <span>上一页</span> ";
                    } else {
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "?{$origin_param}p=" . ($this->nowPage - 1) . "' title='上一页'>上一页</a> ";
                    }
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr = $pageStr . " <span>下一页</span> ";
                    } else {
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "?{$origin_param}p=" . ($this->nowPage + 1) . "' title='下一页'>下一页</a> ";
                    }
                }
                break;
            
            case 10: // 后台样式
                $pageStr = "<form action='" . $this->baseurl . "' method='get'>";
                if ($this->totalPages > 0) {
                    if ($this->nowPage == 1) {
                        $pageStr = $pageStr . "<a>第一页</a> ";
                        $pageStr = $pageStr . " <a>上一页</a> ";
                    } else {
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "/1' title='第一页'>第一页</a> ";
                        $pageStr = $pageStr . "<a href='" . $this->baseurl . "/" . ($this->nowPage - 1) . "' title='上一页'>上一页</a> ";
                    }
                }
                $s = $this->nowPage < 5 ? 1 : $this->nowPage - 4;
                $t = $this->nowPage < $this->totalPages - 5 ? $this->nowPage + 5 : $this->totalPages + 1;
                for ($i = $s; $i < $t; $i ++) {
                    $pageStr .= "&nbsp;<a href='" . $this->baseurl . "/" . $i . "' ";
                    if ($i == $this->nowPage) {
                        $pageStr .= " style='color:#ff0000'";
                    }
                    $pageStr .= ">[" . $i . "]</a> &nbsp;";
                }
                if ($this->totalPages > 0) {
                    if ($this->totalPages <= $this->nowPage) {
                        $pageStr = $pageStr . " <a>下一页</a> ";
                        $pageStr = $pageStr . " <a>最后页</a> ";
                    } else {
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "/" . ($this->nowPage + 1) . "' title='下一页'>下一页</a> ";
                        $pageStr = $pageStr . " <a href='" . $this->baseurl . "/" . $this->totalPages . "' title='最后页'>最后页</a> ";
                    }
                    // $pageStr = $pageStr . "&nbsp;到第 <input onkeydown='onlyNum();' name='p' type='text' id='p' size='1' /> 页 <input type='submit' name='Submit' value='确定' />";
                }
                $pageStr = $pageStr . "</form>";
                break;
            
            default:
        }
        
        return $pageStr;
    }
}
?>
