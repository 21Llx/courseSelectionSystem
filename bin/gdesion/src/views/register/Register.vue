<template>
  <div class="register">
    <section class="form_container">
      <div class="manage_tip">
        <span class="title">注册界面</span>
      </div>
      <el-form
        :model="registerUser"
        :rules="rules"
        ref="registerForm"
        label-width="80px"
        class="registerForm"
      >
        <el-form-item label="姓名" prop="name">
          <el-input v-model="registerUser.name" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item label="学号" prop="ID">
          <el-input v-model="registerUser.ID" placeholder="请输入学号"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input type="password" v-model="registerUser.password" placeholder="请输入密码"></el-input>
        </el-form-item>
        <el-form-item label="确认密码" prop="password2">
          <el-input type="password" v-model="registerUser.password2" placeholder="请确认密码"></el-input>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" class="submit_btn" @click="submitForm('registerForm')">注册</el-button>
        </el-form-item>
        <p>
          已有账号，前往
          <span @click="$router.push('/')">登陆</span>
        </p>
      </el-form>
    </section>
  </div>
</template>

<script>
export default {
  name: "register",
  data() {
    var pwdCheck = (rule, value, callback) => {
      var pwdR = /^\w{6,20}$/
      if(pwdR.test(value) !== true){
        callback(new Error("密码只能输入数字，字母，下划线!"));
      }else{
        callback()
      }
    };
    var validatePass2 = (rule, value, callback) => {
      if (value !== this.registerUser.password) {
        callback(new Error("两次输入密码不一致!"));
      } else {
        callback();
      }
    };
    var checkID = (rule, value, callback) => {
      if (!value) {
        return callback(new Error("学号不能为空"));
      }
      var account1 = /^[0-9]*$/;
      if (account1.test(value) !== true) {
        callback(new Error("请输入数字值"));
        console.log(value.length);
      } else {
        var account = /^\d{6,20}$/;
        if (account.test(value) !== true) {
          callback(new Error("学号为6位数"));
        } else {
          callback();
        }
      }
    };
    return {
      numberValidateForm: {
          age: ''
        },
      registerUser: {
        
        name: "",
        ID: "",
        password: "",
        password2: ""
      },
      rules: {
        name: [
          {
            required: true,
            message: "名字不能为空",
            trigger: "blur"
          },
          {
            min: 2,
            max: 20,
            message: "长度在2到20之间",
            trigger: "blur"
          }
        ],
        ID: [
          {
            required: true,
            message: "用户名不能为空",
            trigger: "blur"
          },
          {
            validator: checkID,
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
          },
          {
            validator: pwdCheck,
            trigger: "blur"
          }
        ],
        password2: [
          {
            required: true,
            message: "密码不能为空",
            trigger: "blur"
          },
          {
            min: 6,
            max: 30,
            message: "长度在6到30",
            trigger: "blur"
          },
          {
            validator: validatePass2,
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        console.log(valid)
        if (valid != false) {
          var that = this;
          this.$axios
            .post(
              "http://127.0.0.1/courseSelectionSystem/src/index.php",
              that.$qs.stringify({ ...this.registerUser, type: "register" }),
              {
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
              }
            )
            .then(function(res) {
              if (res.data.code === 1010) {
                alert(res.data.msg);
                that.$router.push(`/`);
              } else {
                alert(res.data.msg);
                that.registerUser = {
                  name: "",
                  ID: "",
                  password: "",
                  password2: ""
                };
              }
            });
          
        }
      });
    },
  }
};
</script>

<style lang='less'>
.register {
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
  .form_container {
    width: 370px;
    height: 260px;
    position: absolute;
    top: 30%;
    right: 15%;
    @media screen and (max-width: 1400px){
      right: 10%;
    }
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
    .registerForm {
      margin-top: 20px;
      background-color: #fff;
      padding: 20px 40px 20px 20px;
      border-radius: 5px;
      box-shadow: 0px 5px 10px #cccc;
      .el-form-item__label {
        padding: 0 9px 0 0;
      }
      .submit_btn {
        width: 100%;
      }
    }
  }
}
</style>
