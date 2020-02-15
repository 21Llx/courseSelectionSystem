<template>
  <div class="login">
    <div class="loginSelect" v-if="selected==0">
      <div class="selectTitle">选择登陆入口</div>
      <ul>
        <li>
          <el-button type="primary" @click="selected=1">学生登陆</el-button>
        </li>
        <li>
          <el-button type="danger" @click="selected=2">教师登陆</el-button>
        </li>
      </ul>
    </div>
    <section class="form_container" v-else-if="selected==1">
      <div class="manage_tip">
        <span class="title">学生登陆界面</span>
      </div>
      <el-form
        :model="loginUser"
        :rules="rules"
        ref="loginForm"
        label-width="80px"
        class="loginForm"
      >
        <el-form-item label="学号" prop="ID">
          <el-input v-model="loginUser.ID" placeholder="请输入学号"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input type="password" v-model="loginUser.password" placeholder="请输入密码"></el-input>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" class="login_btn" @click="loginForm('loginForm','stu')">登陆</el-button>
        </el-form-item>
        <p>
          没有账号，前往
          <span @click="$router.push('register')">注册</span>
        </p>
        <p class="selectBack" @click="selected=0">返回登陆入口</p>
      </el-form>
    </section>
    <section class="form_container" v-else-if="selected==2">
      <div class="manage_tip">
        <span class="title">教师登陆界面</span>
      </div>
      <el-form
        :model="loginUser"
        :rules="rules"
        ref="loginForm"
        label-width="80px"
        class="loginForm"
      >
        <el-form-item label="教师号" prop="ID">
          <el-input v-model="loginUser.ID" placeholder="请输入教师"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input type="password" v-model="loginUser.password" placeholder="请输入密码"></el-input>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" class="login_btn" @click="loginForm('loginForm','tea')">登陆</el-button>
        </el-form-item>
        <p class="selectBack" @click="selected=0">返回登陆入口</p>
      </el-form>
    </section>
  </div>
</template>

<script>
import { myAxios } from "../../request/index.js";
export default {
  data() {
    return {
      selected: 0,
      loginUser: {
        ID: "",
        password: ""
      },
      rules: {
        ID: [
          {
            required: true,
            message: "学号不能为空",
            trigger: "blur"
          },
          {
            min: 6,
            max: 6,
            message: "学号格式不正确",
            trigger: "blur"
          }
        ],
        password: [
          {
            required: true,
            message: "密码不能为空",
            trigger: "blur"
          },
          {
            min: 6,
            max: 20,
            message: "长度在6到20",
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    loginForm(formName,identity) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let that = this;
          let login = { ...this.loginUser };
          this.$axios
            .post(
              "http://127.0.0.1/courseSelectionSystem/src/index.php",
              that.$qs.stringify({ ...this.loginUser, type: "login",identity}),
              {
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
              }
            )
            .then(function(res) {
              if (res.data.code === 1000) {
                if(identity === 'stu'){
                  sessionStorage.setItem('user',JSON.stringify(res.data.data.ID))
                  that.$router.push("indexStu");
                }else{
                  sessionStorage.setItem('teacher',JSON.stringify(res.data.data.ID))
                  that.$router.push("teacher")
                }
              } else if (res.data.code === 1002) {
                alert("你的账户被封禁");
              } else {
                alert("你的账户或密码不正确");
                that.loginUser = {ID: "", password: ""};
              }
            });
        }
      });
    }
  }
};
</script>

<style lang='less'>
.login {
  background-image: url(../../assets/bg.png);
  width: 100%;
  height: 900px;
  p {
    color: #ccc;
    margin-bottom: 10px;
    span {
      cursor: pointer;
      color: red;
      margin-left: 5px;
    }
  }
  .selectBack {
    color: #ccc;
    cursor: pointer;
  }
  .form_container {
    width: 370px;
    height: 260px;
    position: absolute;
    top: 30%;
    left: 55%;
    padding: 25px;
    border-radius: 5px;
    text-align: center;
    .manage_tip {
      .title {
        font-family: "Microsoft YaHei";
        font-weight: bold;
        font-size: 26px;
        color: #fff;
      }
    }
    .loginForm {
      margin-top: 20px;
      background-color: #fff;
      padding: 20px 40px 20px 20px;
      border-radius: 5px;
      box-shadow: 0px 5px 10px #cccc;
      .login_btn {
        width: 100%;
      }
    }
  }
  .loginSelect {
    width: 370px;
    height: 380px;
    position: absolute;
    top: 30%;
    right: 15%;
    @media screen and (max-width: 1400px){
      right: 10%;
      top: 35%;
    }
    padding: 25px;
    border-radius: 5px;
    text-align: center;
    background-color: #fff;
    .selectTitle {
      font-size: 30px;
      color: #1591d3;
      font-weight: 700;
    }
    ul {
      margin-top: 40px;
      margin-bottom: 30px;
      li {
        margin-top: 40px;
        button {
          span {
            font-size: 24px;
          }
          width: 280px;
          height: 70px;
          border-radius: 10px;
        }
      }
    }
  }
}
</style>
