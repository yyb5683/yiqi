<?php 
    require '../init.php';

$type=$_SESSION['admin']['name'];
    // echo '<pre>';
    // print_r($type);
    // $type['name'];


    // exit;

    $where = '';
    $urlname = '';
    $name = '';
    
    if (isset($_GET['name']) && !empty($_GET['name'])) {
        $name = $_GET['name'];
        $where = "WHERE `name` LIKE '%$name%'";//SQL查询条件
        $urlname = "&name=$name";//url的参数
    }

    //分页开始
    //总记录数
    $sql = "SELECT count(*) total FROM s47_admin_user $where";
    $row = query($link, $sql);
    $total = $row[0]['total'];
    //每页显示数
    $num = 5;
    //总页数
    $allpage = ceil($total / $num);

    //获取页码
    $page = isset($_GET['page'])?(int)$_GET['page']:1;
    //限制页码范围
    //页码:不能小于1 不能大于$allpage
    $page = max(1,$page);//[0,1]
    $page = min($page,$allpage);//[接收的页数,总页数]

    //获取偏移量
    $offset = ($page-1) * $num;
    //获取上一夜/下一夜
    $prev = $page - 1;
    $next = $page + 1;

    //控制数组页码的显示
    $start = max($page - 2, 1);
    $end = min($page + 2, $allpage);

    $pageurl = 'index2.php';
    //产生数字链接
    $num_link = '';
    for ($i = $start; $i <= $end; $i++) {
        if ($page == $i) {
            $num_link .= '<li class="active"><a href="./'.$pageurl.'?admin_page='.$i.$urlname.'">'.$i.'</a></li>';
            continue;
        }
        $num_link .= '<li><a href="./'.$pageurl.'?_admin_page='.$i.$urlname.'">'.$i.'</a></li>';
    }
    echo '<hr>';
    //5.SQL语句
    $sql = "SELECT `id`,`name`,`pwd`,`tel`,`type` 
    FROM s47_admin_user $where LIMIT $offset,$num";

    //处理结果集
    $user_list = query($link,$sql);


   // $user_list=$user_list['0'];
   // echo'<pre>';
   //  print_r($user_list);

   //  if($user_list['type']=='0'){


   //  };
   //  exit;
    //显示当前页查询到的记录数量
    $rows = mysqli_affected_rows($link);

    //8.关闭MYSQL连接
    mysqli_close($link);

    // p($user_list);exit;
?>

<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>user-list</title>

    <!-- Bootstrap -->
    <link href="../public/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="../public/js/html5shiv.min.js"></script>
    <script src="../public/js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="../public/admin.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <h2>后台用户信息表</h2>
            <nav class="navbar">
              <div class="container-fluid">
                <div class="collapse navbar-collapse">
                  <ul class="nav navbar-nav">
                    <li><a href="./index2.php">后台用户列表</a></li>
                    <li><a href="./admin_add.php">添加后台用户</a></li>
                  </ul>
                  <form class="navbar-form navbar-left" >
                    <div class="form-group">
                      <input type="text" name="name" class="form-control" placeholder="按用户名搜索">
                    </div>
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                  </form>
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>

            <table class="table table-bordered table-hover">
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
               
                    <th>手机号</th>
                   
                  <th>type</th>
                    <th>操作</th>
                </tr>

                <?php if (empty($user_list)): ?>


                    <tr>
                        <td colspan="7"><h3 class="text-center">暂无数据</h3></td>
                    </tr>

                <?php else: ?>
                <?php foreach ($user_list as $key => $val): ?>
                    <tr>
                        <td><?php echo $val['id'] ?></td>
                        <td><?php echo $val['name'] ?></td>
                        
                   
                        <td><?php echo $val['tel'] ?></td>
                        <td><?php echo $val['type'] ?></td>
                   
                 
                        <td>

                          <?php if( ($val['type']) !=='0'): ?>

                            <a href="./admin_edit.php?id=<?php echo $val['id'] ?>">编辑</a>
                            <a href="./admin_action.php?a=del&id=<?php echo $val['id'] ?>">删除</a>
                         <?php else: ?>

                            


                         
                             <a href="./admin_edit.php?id=<?php echo $val['id'] ?>"></a>
                             <a href="./admin_action.php?a=del&id=<?php echo $val['id'] ?>"></a>
                        

                        


                         <?php endif ?>

                        </td>
               

                    </tr>

                <?php endforeach ?>
                <?php endif ?>
            </table>
            <?php require ADMIN_PATH.'../com/admin_page.php'; ?>
        </div>
    </div>


    <!-- 此处引入jQuery -->
    <script src="../public/js/jquery.min.js"></script>
    <!-- bootstrap.min.js必须放在JQ后面 -->
    <script src="../public/js/bootstrap.min.js"></script>
</body>
</html>

        

