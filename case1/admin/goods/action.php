<?php 
    //此页面会进行 增 删 改
               require '../init.php';

                // p($_GET);
                // p($_POST);
                // p($_FILES);
    // exit;

                $a = $_GET['a'];
        switch ($a) {


        case 'state':
        //是否上架
               $id = $_GET['id'];
               $state = $_GET['state']==1?0:1;
               $sql="UPDATE ".PRE."goods SET `state`='$state' WHERE id='$id'";

               execute($link,$sql);

               header("location:".$_SERVER['HTTP_REFERER']);
               exit;
               break;

         //是否热销
         
        case 'is_hot':

              $id = $_GET['id'];

               $is_hot = $_GET['is_hot']==1?0:1;
               $sql="UPDATE ".PRE."goods SET `is_hot`='$is_hot' WHERE id='$id'";
               execute($link,$sql);
               header("location:".$_SERVER['HTTP_REFERER']);
               exit;
               break;


        case 'is_new':

               $id = $_GET['id'];

               $is_new = $_GET['is_new']==1?0:1;

               $sql = "UPDATE ".PRE."goods SET `is_new`='$is_new' WHERE id='$id'";

               execute($link,$sql);

               header("location:".$_SERVER['HTTP_REFERER']);

               exit;

               break;













        case 'display':
            //是否显示分类
            $id = $_GET['id'];
            $display = $_GET['display']==1?0:1;
            $sql = "UPDATE ".PRE."category SET `display`='$display' WHERE id='$id'";
            execute($link, $sql);
            header("location:".$_SERVER['HTTP_REFERER']);
            exit;
            
            break;

            case 'edit':

            $id=$_POST['id'];


            echo '正在编辑';
            $set = '';
            foreach ($_POST as $key => $val){
                $set .= "`$key`='$val',";

            }
            $set = rtrim($set,',');

            $sql="UPDATE ".PRE."goods SET $set WHERE `id`='$id'";
            $id = execute($link,$sql);

            if($id){
                admin_redirect('编辑成功','./index.php');
                exit;
            }else{
                admin_redirect('编辑失败请重试');
                exit;
            }
            

            
            
            break;

        case 'add':
        // p($_POST);
        // exit;
            //表单不为空,如果有空值,回之
            // foreach ($_POST as $key => $val) {
            //     if ($val == '') {
            //         admin_redirect('请完善表单信息!');
            //         exit;
            //     }
            // }

            /*
            1. 执行图片上传
                图片上传失败,则返回到来之前的页面
                成功,得到图片文件名
                    图片缩放:
                    350*350 前台
                    150*150 管理页面,个人中心
                    50*50 购物车/订单管理/预览
                    !(如果有兴趣,可以加水印)
                    缩放失败,则返回上级(删除上传的图片)

            2.图片处理成功之后,
            先写入商品信息 商品表
                写失败
                    删除所有图片信息
                写成功
                    商品的自增ID.
                    图片表的写入
                        写入失败
                            删除所有图片信息
                            删除刚刚添加的商品信息
                        写入成功
                恭喜你 商品添加完成
            */
            //上传图片,图片处理
            //生成保存根目录
             $save_dir = ADMIN_PATH.'../uploads/';
            
            $filename = up('file', 10485760, array('image'), $save_dir);
            // v($filename);
            //判断图片是否上传成功
            if (!$filename) {
                admin_redirect('文件上传失败!');
                exit;
            }

            //缩放吧,骚年!
            // 首先要得到上传图片的完整路径
            $img_path = ADMIN_PATH.'../uploads/';
            $img_path .= substr($filename, 0, 4).'/';
            $img_path .= substr($filename, 4, 2).'/';
            $img_path .= substr($filename, 6, 2).'/';
            $img_path .= $filename;
            // echo $img_path;
            
            if (
                !zoom($img_path,350,350) 
                || 
                !zoom($img_path,150,150) 
                || 
                !zoom($img_path,50,50)
            ) {
                //其中有一张缩放失败就,删除原图及小图
                unlink($img_path);
                @unlink(dirname($img_path).'/350_'.$filename);
                @unlink(dirname($img_path).'/150_'.$filename);
                @unlink(dirname($img_path).'/50_'.$filename);
                admin_redirect('图片缩放失败!');
                exit;
            }

            //处理商品信息
            $name = $_POST['gname'];
            $cate_id = $_POST['cate_id'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $state = $_POST['state'];
            $msg = htmlentities($_POST['msg']);

            // echo '<hr>';
            //Sql
            $sql = "INSERT INTO ".PRE."goods (`gname`,`cate_id`,`price`,`stock`,`state`,`msg`) VALUES('$name','$cate_id','$price','$stock','$state','$msg')";
            // echo $sql;
            // exit;

            //执行数据写入
            $id = execute($link, $sql);

            //判断商品表的添加情况
            if (!$id) {
                //商品添加失败,则删除所有图片
                @unlink($img_path);
                @unlink(dirname($img_path).'/350_'.$filename);
                @unlink(dirname($img_path).'/150_'.$filename);
                @unlink(dirname($img_path).'/50_'.$filename);
                admin_redirect('商品表写入失败');
                exit;
            }

            //图片表的处理
            $goods_id = $id;
            //商品的第一张必须是封面
            $cover = 1;
            $sql = "INSERT INTO ".PRE."image (`iname`,`goods_id`,`cover`) VALUES('$filename','$goods_id','$cover')";
            //执行图片商品表的写入
            $result = execute($link, $sql);

            //判断图片表的写入情况
            if (!$result) {
                //  图片表写入失败,删除原图和小图
                //  删除商品表的新添加信息
                @unlink($img_path);
                @unlink(dirname($img_path).'/350_'.$filename);
                @unlink(dirname($img_path).'/150_'.$filename);
                @unlink(dirname($img_path).'/50_'.$filename);
                //删除商品表的最新信息
                execute($link,"DELETE FROM ".PRE."goods WHERE id='$id'");
                admin_redirect('图片表写入失败');
                exit;
            }else{
                admin_redirect('商品添加成功!','./index.php');
                exit;
            }

            break; 
    }

    //8.关闭连接
    mysqli_close($link);



