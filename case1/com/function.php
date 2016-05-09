<?php 
    //函数库文件
    
    /**
     * [v 输出变量类型和值]
     * @param  mixed $var [传入要打印输出的变量]
     * @return 直接打印输出变量
     */
    function v($var)
    {
        echo '<pre>';
            var_dump($var);
        echo '</pre>';
    }


    /**
     * [p 格式化输出变量]
     * @param  mixed $var [传入要打印输出的变量]
     * @return 直接打印输出变量
     */
    function p($var)
    {
        echo '<pre>';
            echo '您输出的变量类型是:'.gettype($var).'<br>';
            print_r($var);
        echo '</pre>';
    }


    /**
     * [redirect 前台跳转3]
     * @param  string  $msg [页面的跳转信息]
     * @param  string  $url [跳转页面的地址url]
     * @param  integer $t   [跳转的时间s]
     * @return [显示跳转的页面,并执行跳转]
     */
    function redirect($msg='', $url='', $t=3)
    {
        //如果url为空
        if ($url == '') {
            //则回到来之前的页面
            $url = $_SERVER['HTTP_REFERER'];
        }
        require PATH.'com/redirect.php';
    }


  /**
     * [redirect 后台跳转]
     * @param  string  $msg [页面的跳转信息]
     * @param  string  $url [跳转页面的地址url]
     * @param  integer $t   [跳转的时间s]
     * @return [显示跳转的页面,并执行跳转]
     */


    function admin_redirect($msg='', $url='', $t=3)
    {
        //如果url为空
        if ($url == '') {
            //则回到来之前的页面
            $url = $_SERVER['HTTP_REFERER'];
        }
        require ADMIN_PATH.'redirect.php';
    }


    /**
     * [execute 专业增/删/改 函数30年]
     * @param  object $link [mysqli的连接标识]
     * @param  string $sql  [要执行的SQL语句]
     * @return 成功时:添加语句,返回自增ID,删/改语句,true
     *         以上都失败返回false
     */
    function execute($link, $sql)
    {
        //执行SQL语句
        $result = mysqli_query($link,$sql);
        //处理结果集
        if ($result) {
            //添加时会返回自增ID
            if (mysqli_insert_id($link)>0) {
                //添加时返回自增ID
                return mysqli_insert_id($link);
            }else{
                //删改时返回true
                return true;
            }
        }else{
            //操作失败
            return false;
        }
    }


    /**
     * [query 专业查询30年]
     * @param  object $link [mysqli的连接标识]
     * @param  string $sql  [要执行的SQL语句]
     * @return 查询成功返回数组,失败返回false
     */
    function query($link, $sql)
    {
        //执行SQL语句
        $result = mysqli_query($link,$sql);
        //判断执行结果
        if ($result) {
            $list = array();
            $list = mysqli_fetch_all($result,MYSQLI_ASSOC);
            mysqli_free_result($result);

            //返回查询结果数组
            return $list;
        }else{
            //查询失败,返回false
            return false;
        }
    }




