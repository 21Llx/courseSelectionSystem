<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: text/html; charset=utf-8");
$sqlObj = new mysqli("127.0.0.1", "root", "root", "courseSelectionSystem", "3306");
if ($sqlObj->connect_errno != 0) {
  echo '失败';
} else {
  // echo '连接成功';
  $type = $_POST['type'];
  switch ($type) {
    case "login": //学生老师登陆
      $ID = $_POST['ID'];
      $pwd = $_POST['password'];
      if ($_POST['identity'] == 'stu') {
        $sql = 'SELECT * FROM students WHERE ID ="' . $ID . '" AND password="' . $pwd . '";';
      } else {
        $sql = 'SELECT * FROM teacher WHERE ID ="' . $ID . '" AND password="' . $pwd . '";';
      }
      $result = $sqlObj->query($sql);
      $info = $result->fetch_assoc();
      if ($info) {
        if ($info['state'] == '正常') {
          echo json_encode(array('code' => 1000, 'msg' => '登陆成功', 'data' => $info,));
        } else {
          echo json_encode(array('code' => 1002, 'msg' => '账号被封禁'));
        }
      } else {
        echo json_encode(array('code' => 1001, 'msg' => '你的账户或密码不正确'));
      }
      break;

//学生端
    case "register": //学生注册
      $ID = $_POST['ID'];
      $pwd = $_POST['password'];
      $userName = $_POST['name'];
      $sqlSel = 'SELECT * FROM students WHERE ID ="' . $ID . '" ;';
      $sqlInt = 'INSERT INTO students(ID,name,password)VALUE("' . $ID . '","' . $userName . '","' . $pwd . '")';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_assoc();
      if ($info) {
        echo json_encode(array('code' => 1011, 'msg' => '账号已存在'));
      } else {
        if ($sqlObj->query($sqlInt) === TRUE) {
          echo json_encode(array('code' => 1010, 'msg' => '注册成功'));
        }
      }
      break;

    case "getUser": //获取用户名
      $sqlSel = 'SELECT * FROM students WHERE ID ="' . $_POST['user'] . '" ;';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_assoc();
      echo json_encode($info);
      break;

    case "chooseTab": //personalStu下tabNum点击选择课程和项目
      if ($_POST['tabNum'] == 2) {
        $sqlSel = 'SELECT * FROM choosecur WHERE stuID=' . $_POST['user'] . ' and state=1';
      } else {
        $sqlSel = 'SELECT * FROM choosecur WHERE stuID=' . $_POST['user'] . ' and state=0';
      }
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      if ($info) {
        echo json_encode($info);
      } else {
        $info = $result->fetch_assoc();
        echo json_encode($info);
      }
      break;
    case "searchCurInput":    //学生搜索课程
      if($_POST['selectTab']=="1"){
        $sqlSel = 'select * from curriculum where curID='.$_POST['selectInput'].'';
      }else if($_POST['selectTab']=="2"){
        $sqlSel = 'select * from curriculum where curName like "%'.$_POST['selectInput'].'%"';
      }
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      echo json_encode($info);
      // echo $sqlSel;
      break;
    case "changePwd": //修改密码
      $sqlUpd = 'UPDATE students SET password="' . $_POST['pwd'] . '" WHERE ID=' . $_POST['user'];
      $result = $sqlObj->query($sqlUpd);
      if ($result) {
        echo json_encode(array('code' => '2000'));
      }
      break;


    case "changeStuDetail": //修改个人信息
      $sqlUpd = 'UPDATE students SET name="' . $_POST['name'] . '",phone="' . $_POST['phone'] . '",qq="' . $_POST['qq'] . '",email="' . $_POST['email'] . '",university="' . $_POST['university'] . '",grade="' . $_POST['grade'] . '" WHERE ID=' . $_POST['ID'];
      $result = $sqlObj->query($sqlUpd);
      if ($result) {
        echo json_encode(array('code' => '2010'));
      }
      break;

    case "curriculum": // 获取课程 (点击不同 tab 获取不同状态的课程)
      $sqlSelUser = 'select * from students where ID=' . $_POST['user'] . '';
      $resultselUser = $sqlObj->query($sqlSelUser);
      $infoSelUser = $resultselUser->fetch_assoc();
      if ($_POST['tab'] == 0) {
        $sqlSel = 'SELECT * FROM curriculum limit 0,6';
        $sqlSelAll = 'SELECT * FROM curriculum';
      } else if ($_POST['tab'] == 1) {
        $sqlSel = 'SELECT * FROM curriculum where least<=' . $infoSelUser['paid'] . ' and curEndTime>"' . $_POST['timeNow'] . '" and curBeginTime<="' . $_POST['timeNow'] . '" limit 0,6';
        $sqlSelAll = 'SELECT * FROM curriculum where least<=' . $infoSelUser['paid'] . ' and curEndTime>"' . $_POST['timeNow'] . '" and curBeginTime<="' . $_POST['timeNow'] . '"';
      } else {
        $sqlSel = 'SELECT * FROM curriculum WHERE LEAST>' . $infoSelUser['paid'] . ' OR curEndTime<="' . $_POST['timeNow'] . '" OR curBeginTime>"' . $_POST['timeNow'] . '" limit 0,6';
        $sqlSelAll = 'SELECT * FROM curriculum WHERE LEAST>' . $infoSelUser['paid'] . ' OR curEndTime<="' . $_POST['timeNow'] . '" OR curBeginTime>"' . $_POST['timeNow'] . '"';
      }
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      $resultAll = $sqlObj->query($sqlSelAll);
      $infoAll = $resultAll->fetch_all(MYSQLI_ASSOC);
      if ($info) {
        echo json_encode(array('data' => $info, 'dataAll' => $infoAll));
      }
      break;
    case "cancelCur":  //删除已选课程
      $sqlSelCur = 'SELECT * FROM choosecur WHERE curID=' . $_POST['curID'] . ' and stuID=' . $_POST['stuID'] . '';
      $resultSelCur = $sqlObj->query($sqlSelCur);
      $infoSelCur = $resultSelCur->fetch_assoc();
      if ($_POST['timeNow'] > $infoSelCur['endTime']) {
        $sqlUpd = 'UPDATE students SET paid=students.paid-3 WHERE ID=' . $_POST['stuID'] . '';
        $result = $sqlObj->query($sqlUpd);
      }
      $sqlDel = 'delete FROM choosecur WHERE curID=' . $_POST['curID'] . ' and stuID=' . $_POST['stuID'] . '';
      $resultDel = $sqlObj->query($sqlDel);
      $sqlPeoUpd = 'update curriculum set curPeople = curriculum.curPeople-1 where curID=' . $_POST['curID'] . '';
      $resultPeoUpd = $sqlObj->query($sqlPeoUpd);
      if ($resultDel) {
        $sqlUpdCur = 'update students set choosed = students.choosed-1 where ID=' . $_POST['stuID'] . '';
        $resultUpd = $sqlObj->query($sqlUpdCur);
        
        $sqlSel = 'SELECT * FROM choosecur WHERE stuID=' . $_POST['stuID'] . '';
        $result = $sqlObj->query($sqlSel);
        $info = $result->fetch_all(MYSQLI_ASSOC);
        if ($info) {
          echo json_encode($info);
        } else {
          $info = $result->fetch_assoc();
          echo json_encode($info);
        }
      }
      break;
    case "stuEvaluate": //课程评价
      $sqlUpd = 'UPDATE choosecur SET stuEvaluateTime="' . $_POST['timeNow'] . '",stuEvaluate="' . $_POST['evaluate'] . '" WHERE stuID=' . $_POST['stuID'] . ' AND curID=' . $_POST['curID'] . '';
      $resultUpd = $sqlObj->query($sqlUpd);
      if ($resultUpd) {
        echo '评价成功';
      } else {
        echo '评价失败';
      }
      break;
    case "curriculumChoose": // 点击不同页获取不同索引的课程
      $sqlSelUser = 'select * from students where ID=' . $_POST['user'] . '';
      $resultselUser = $sqlObj->query($sqlSelUser);
      $infoSelUser = $resultselUser->fetch_assoc();

      $page = $_POST['page'];
      if ($page == 1) {
        $begin = 0;
      } else {
        $begin = ($page - 1) * 6;
      }
      if ($_POST['tab'] == 0) {
        $sqlSel = 'SELECT * FROM curriculum limit ' . $begin . ',6';
      } else if ($_POST['tab'] == 1) {
        $sqlSel = 'SELECT * FROM curriculum where least<=' . $infoSelUser['paid'] . ' and curEndTime>"' . $_POST['timeNow'] . '" limit ' . $begin . ',6';
      } else {
        $sqlSel = 'SELECT * FROM curriculum WHERE LEAST>' . $infoSelUser['paid'] . ' OR curEndTime<="' . $_POST['timeNow'] . '" limit ' . $begin . ',6';
      }
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      if ($info) {
        echo json_encode($info);
      }
      break;

    case "chooseCur": // 点击开始学习，判断是否可以报名
      $sqlSelCur = 'SELECT * FROM curriculum WHERE curID=' . $_POST['curID'] . '';
      $resultSelCur = $sqlObj->query($sqlSelCur);
      $infoSelCur = $resultSelCur->fetch_assoc();

      $sqlSelUser = 'select * from students where ID=' . $_POST['user'] . '';
      $resultselUser = $sqlObj->query($sqlSelUser);
      $infoSelUser = $resultselUser->fetch_assoc();

      if ($infoSelCur) {
        if ($infoSelCur['curEndTime'] < $_POST['timeB']) {
          echo json_encode(array('code' => 1022, 'msg' => '已超过截止时间'));
          return;
        }
        if ($infoSelCur['curPeople'] >= $infoSelCur['curPeoples']) {
          echo json_encode(array('code' => 1023, 'msg' => '报名人数已满'));
          return;
        }
        if ($infoSelCur['least'] > $infoSelUser['paid']) {
          echo json_encode(array('code' => 1024, 'msg' => '你的余额没达到最低要求'));
          return;
        }
        if($infoSelUser['choosed'] ==3){
          echo json_encode(array('code' => 1025, 'msg' => '你的选课数量已达上限'));
          return;
        }
        $sqlInsCur = "INSERT INTO choosecur(curID,curName,stuID,chooseTime,endTime,curTeacher,curTime) VALUES(" . $_POST['curID'] . ",'" . $infoSelCur['curName'] . "'," . $infoSelUser['ID'] . ",'" . $_POST['timeB'] . "','" . $_POST['timeE'] . "','" . $infoSelCur['curTeacher'] . "','" . $infoSelCur['curDuration'] . "')";
        $resultInsCur = $sqlObj->query($sqlInsCur);
        if ($resultInsCur) {
          $sqlUpd = 'update curriculum set curPeople = curriculum.curPeople+1 where curID=' . $_POST['curID'] . '';
          $resultUpd = $sqlObj->query($sqlUpd);
          $sqlUpd2 = 'update students set choosed = students.choosed+1 where ID=' . $_POST['user'] . '';
          $resultUpd2 = $sqlObj->query($sqlUpd2);
          echo json_encode(array('code' => 1020, 'msg' => '报名成功'));
        } else {
          echo json_encode(array('code' => 1021, 'msg' => '报名失败'));
        }
      } else {
        echo json_encode(array('code' => 10211, 'msg' => '报名失败'));
      }
      break;

    case "judgeChoose": // 判断课程是否已经选中,改变开始学习按钮的样式
      $sqlSelCur = 'SELECT * FROM choosecur WHERE curID=' . $_POST['curID'] . ' and stuID=' . $_POST['stuID'] . '';
      $resultSelCur = $sqlObj->query($sqlSelCur);
      $infoSelCur = $resultSelCur->fetch_assoc();
      $sqlSel = 'SELECT StuEvaluate,StuEvaluateTime FROM choosecur WHERE curID=' . $_POST['curID'] . '';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      if ($infoSelCur) {
        echo json_encode(array('code' => 'chooseTru','data' => $info));
      } else {
        echo json_encode(array('code' => 'chooseFal','data' => $info));
      }
      break;

//教师端
    case "getTeacher":   //获取教师信息
      $sqlSel = 'select * from teacher where ID=' . $_POST['teacherID'] . '';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_assoc();
      echo json_encode($info);
      break;
    case "teacherChangePwd": //修改密码
      $sqlUpd = 'UPDATE teacher SET password="' . $_POST['pwd'] . '" WHERE ID=' . $_POST['teacher'];
      $result = $sqlObj->query($sqlUpd);
      if ($result) {
        echo json_encode(array('code' => '3000'));
      } else {
        echo '修改失败';
      }
      break;
    case "changeTeaDetail":   //修改信息
      $sqlUpd = 'UPDATE teacher SET name="' . $_POST['name'] . '",phone="' . $_POST['phone'] . '" WHERE ID=' . $_POST['ID'];
      $result = $sqlObj->query($sqlUpd);
      if ($result) {
        echo json_encode(array('code' => '3010'));
      }
      break;
    case "getTeaCur":  //获取教师课程
      $sqlSel = 'select * from curriculum where teacherID=' . $_POST['teacherID'] . ' and curEndTime>"' . $_POST['timeNow'] . '"';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      if ($result) {
        if ($_POST['tabNum'] == 1) {
          echo json_encode($info);
          
        }else if($_POST['tabNum'] == 2){
          $sqlSelBeg = 'select * from curriculum where teacherID="' . $_POST['teacherID'] . '" and curEndTime<"' . $_POST['timeNow'] . '" and curState="未结束"';
          $resultBeg = $sqlObj->query($sqlSelBeg);
          $infoBeg = $resultBeg->fetch_all(MYSQLI_ASSOC);
          if($resultBeg){
            echo json_encode($infoBeg);
          }
        }else{
          $sqlSelBeg = 'select * from curriculum where teacherID=' . $_POST['teacherID'] . ' and curState="已结束"';
          $resultBeg = $sqlObj->query($sqlSelBeg);
          $infoBeg = $resultBeg->fetch_all(MYSQLI_ASSOC);
          if($resultBeg){
            echo json_encode($infoBeg);
          }
        }
      }
      break;
    case "endCur":    //结束课程
      $sqlUpd = 'update curriculum set curState="已结束" where curID="' . $_POST['curID'] . '"';
      $result = $sqlObj->query($sqlUpd);
      $sqlUpd2 = 'update choosecur set state=0 where curID="' . $_POST['curID'] . '"';
      $result2 = $sqlObj->query($sqlUpd2);
      if ($result) {
        $sqlSelCho = 'select stuID from chooseCur where curID="' . $_POST['curID'] . '"';
        $resultSelCho = $sqlObj->query($sqlSelCho);
        $infoSelCho = $resultSelCho->fetch_all(MYSQLI_ASSOC);
        for($i = 0; $i<count($infoSelCho);$i++){
          $sqlUpd3 = 'update students set choosed = students.choosed-1 where ID=' . $infoSelCho[$i]['stuID'] . '';
          $result3 = $sqlObj->query($sqlUpd3);
        }

        $sqlSelBeg = 'select * from curriculum where teacherID="' . $_POST['teacherID'] . '" and curEndTime<"' . $_POST['timeNow'] . '" and curState="未结束"';
        $resultBeg = $sqlObj->query($sqlSelBeg);
        $infoBeg = $resultBeg->fetch_all(MYSQLI_ASSOC);
        if($resultBeg){
          echo json_encode($infoBeg);
        }
      }
      break;
    case "getStuName":  //获取学生名字
      $sqlSel = 'select stuID,teacherEva from chooseCur where curID="' . $_POST['curID'] . '"';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      for($i = 0; $i<count($info);$i++){
        $sqlSelStu = 'select name,ID from students where ID="' . $info[$i]['stuID'] . '"';
        $resultStu = $sqlObj->query($sqlSelStu);
        $infoStu = $resultStu->fetch_assoc();
        $stuName[] = $infoStu;
      }
      echo json_encode($stuName);
      break;
    case "sureEvaStu":
      for($i = 0; $i<count($_POST['curIDs']);$i++){
        $sqlSel = 'select teacherEva from chooseCur where curID=' . $_POST['curIDs'][$i] . '';
        $result = $sqlObj->query($sqlSel);
        $info = $result->fetch_all(MYSQLI_ASSOC);
        $teacherEvaStu[] = $info;
      }
      echo json_encode($teacherEvaStu);
      // echo $sqlSel;
      break;
    case "teacherEvaStu":  //教师对学生进行评价
      $stuName = $_POST['stuName'];
      for($i = 0; $i<count($stuName);$i++){
        $sqlUpd = 'update choosecur set teacherEva='.$stuName[$i]['radio'].' where curID=' . $_POST['curIDNow'] . ' and stuID ='.$stuName[$i]['ID'].' ';
        $result = $sqlObj->query($sqlUpd);
        $sqlUpd2 = 'update students set paid=students.paid+'.$stuName[$i]['radio'].' where ID ='.$stuName[$i]['ID'].' ';
        $result2 = $sqlObj->query($sqlUpd2);
      }
      echo true;
      break;
    case "applyCur":  //课程申请
      $tmpName = $_FILES['image']['tmp_name'];
      $imgPath = 'images/curImgs/';
      $picUrl = $imgPath . time() . '.jpg';
      $result = move_uploaded_file($tmpName, $picUrl);
      $sqlIns = 'INSERT INTO applycur(curName,curTeacher,teacherID,curDuration,curBeginTime,curEndTime,curPeoples,image,LEAST,evaluate,curdetail)
      VALUES("'.$_POST['curName'].'","'.$_POST['teacherName'].'",'.$_POST['teacherID'].',"'.$_POST['curDuration'].'","'.$_POST['beginTime'].'","'.$_POST['endTime'].'","'.$_POST['curPeoples'].'","'.$picUrl.'","'.$_POST['least'].'",'.$_POST['evaluate'].',"'.$_POST['curDetail'].'")';
      $result2 = $sqlObj->query($sqlIns);
      echo json_encode(array('code' => 3020));
      break;  
    case "getApply":
      $sqlSel = 'select * from applycur where teacherID="'.$_POST['teacherID'].'"';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      echo json_encode($info);
      break;
//管理员      
    case "adminLogin": //管理员登陆
      $sql = 'SELECT * FROM admin WHERE adminID ="' . $_POST['ID'] . '" AND password="' . $_POST['password'] . '";';
      $result = $sqlObj->query($sql);
      $info = $result->fetch_assoc();
      if ($info) {
        echo json_encode(array('code' => 3000, 'msg' => '登陆成功', 'data' => $info,));
      } else {
        echo json_encode(array('code' => 3001, 'msg' => '你的账户或密码不正确'));
      }
      break;
    case "getStuAll":    //获取全部学生
      $sqlSel = 'select * from students';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      echo json_encode($info);
      break;
    case "changeState":   //改变学生状态
      if($_POST['state']=='正常'){
        $sqlUpd = 'update students set state="封禁" where ID='.$_POST['stuID'].'';
      }else{
        $sqlUpd = 'update students set state="正常" where ID='.$_POST['stuID'].'';
      }
      $result = $sqlObj->query($sqlUpd);
      echo json_encode(array('code' => 3010, 'msg' => '状态改变成功'));
      break;
    case "getApplyCur":   //获取申请的课程 
      $sqlSel = 'select * from applycur where state=0';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      echo json_encode($info);
      break;
    case "sureApply":     //确认申请
      $sqlIns = 'INSERT INTO curriculum(curName,curTeacher,teacherID,curDuration,curBeginTime,curEndTime,curPeoples,image,LEAST,evaluate,curdetail)
      VALUES("'.$_POST['curName'].'","'.$_POST['curTeacher'].'",'.$_POST['teacherID'].',"'.$_POST['curDuration'].'","'.$_POST['curBeginTime'].'","'.$_POST['curEndTime'].'","'.$_POST['curPeoples'].'","'.$_POST['image'].'","'.$_POST['least'].'",'.$_POST['evaluate'].',"'.$_POST['curDetail'].'")';
      $result = $sqlObj->query($sqlIns);
      $sqlUpd = 'update applycur set state=1 where curID='.$_POST['curID'].'';
      $result = $sqlObj->query($sqlUpd);
      echo '上传成功';
      break;
    case "backApply":     //退回申请
      $sqlUpd = 'update applycur set applyfail="'.$_POST['applyFail'].'",state=2 where curID='.$_POST['curID'].'';
      $result = $sqlObj->query($sqlUpd);
      echo '申请退回成功';
      break;
    case "getCurAll":    //获取全部课程
      $sqlSel = 'select * from curriculum';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      echo json_encode($info);
      break;
    case "deleteCur":    //删除课程
      $sqlDel = 'DELETE FROM curriculum WHERE curID='.$_POST['curID'].'';
      $resultDel = $sqlObj->query($sqlDel);
      $sqlSel = 'select * from curriculum';
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      echo json_encode($info);
      break;
    case "searchInput":
      if($_POST['selectTab'] == '1'){
        $sqlSel = 'SELECT * FROM students WHERE NAME LIKE "%'.$_POST['selectInput'].'%"';
      }else{
        $sqlSel = 'SELECT * FROM curriculum WHERE curName LIKE "%'.$_POST['selectInput'].'%"';
      }
      $result = $sqlObj->query($sqlSel);
      $info = $result->fetch_all(MYSQLI_ASSOC);
      echo json_encode($info);
      break;
    default: //没有相关操作
      echo '没有相关操作';
  }
}
