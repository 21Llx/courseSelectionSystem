<template>
  <div class="personalStu">
    <Head></Head>
    <div class="stuContent">
      <div class="tabLeft">
        <ul @click="chooseTab(tabNum)">
          <li :class="tabNum == 0 ? 'personal1 pseudo' : 'personal'" @click="tabNum = 0">用户信息</li>
          <li :class="tabNum == 1 ? 'perData1 pseudo' : 'perData'" @click="tabNum = 1;changeDetail=false">个人资料</li>
          <li :class="tabNum == 2 ? 'curriculum1 pseudo' : 'curriculum'" @click="tabNum = 2">学习课程</li>
          <li :class="tabNum == 3 ? 'project1 pseudo' : 'project'" @click="tabNum = 3">已完课程</li>
        </ul>
      </div>
      <div class="tabRightRow">
        <div class="tabRight" v-if="tabNum==0">
          <h2>用户信息</h2>
          <div class="perMain">
            <p class="perMain_title">学号:</p>
            <p class="perMain_value">{{user.ID}}</p>
          </div>
          <div class="perMain">
            <p class="perMain_title">姓名:</p>
            <p class="perMain_value">{{user.name}}</p>
          </div>
          <div class="perMain">
            <p class="perMain_title">积分:</p>
            <p class="perMain_value">{{user.paid}}分</p>
          </div>
          <div class="perBtn">
            <el-button type="text" @click="changPwd">
              <el-button class="perBtn_pwd" type="info" plain>修改密码</el-button>
            </el-button>
            <el-button type="danger" class="backBtn" plain @click="$router.push('indexStu')">返回选课中心</el-button>
          </div>
        </div>
        <div class="tabRight" v-else-if="tabNum==1">
          <div v-if="changeDetail">
            <h2>修改个人资料</h2>
            <div class="perMain">
              <p class="perMain_title">联系电话:</p>
              <input class="perMain_value" v-model="user.phone" />
            </div>
            <div class="perMain">
              <p class="perMain_title">qq:</p>
              <input class="perMain_value" v-model="user.qq" />
            </div>
            <div class="perMain">
              <p class="perMain_title">邮箱:</p>
              <input class="perMain_value" v-model="user.email" />
            </div>
            <div class="perMain">
              <p class="perMain_title">专业年级:</p>
              <input class="perMain_value" v-model="user.grade" />
            </div>
            <div class="perBtn">
              <el-button class="perBtn_pwd" type="primary" plain @click="changeStuDetail">确认修改</el-button>
              <el-button type="danger" class="backBtn" plain @click="changeDetail=false">返回个人资料</el-button>
            </div>
          </div>
          <div class="stuDetail" v-else>
            <h2>个人资料</h2>
            <div class="perMain">
              <p class="perMain_title">姓名:</p>
              <p class="perMain_value">{{user.name}}</p>
            </div>
            <div class="perMain">
              <p class="perMain_title">联系电话:</p>
              <p class="perMain_value">{{user.phone}}</p>
            </div>
            <div class="perMain">
              <p class="perMain_title">qq:</p>
              <p class="perMain_value">{{user.qq}}</p>
            </div>
            <div class="perMain">
              <p class="perMain_title">邮箱:</p>
              <p class="perMain_value">{{user.email}}</p>
            </div>
            <div class="perMain">
              <p class="perMain_title">专业年级:</p>
              <p class="perMain_value">{{user.grade}}</p>
            </div>
            <div class="perBtn">
              <el-button class="perBtn_pwd" type="info" plain @click="changeDetail=true">修改个人信息</el-button>
              <el-button type="danger" class="backBtn" plain>返回选课中心</el-button>
            </div>
          </div>
        </div>
        <div class="tabRight" v-else-if="tabNum==2">
          <h2>已选课程</h2>
          <table class="currTable">
            <tr class="currTable_th">
              <th>课程名称</th>
              <th>课程老师</th>
              <th>课程时长</th>
              <th>报名时间</th>
              <th>截止时间</th>
              <th>取消课程</th>
            </tr>
            <tr class="currTable_td" v-for="item in curChoosed">
              <td>{{item.curName}}</td>
              <td>{{item.curTeacher}}</td>
              <td>{{item.curTime}}小时</td>
              <td>{{item.chooseTime}}</td>
              <td>{{item.endTime}}</td>
              <td>
                <el-button @click="cancelCur(item.curID,item.stuID)" type="info" round>取消课程</el-button>
              </td>
            </tr>
          </table>
        </div>
        <div class="tabRight" v-else-if="tabNum==3">
          <h2>已完成课程</h2>
          <table class="currTable">
            <tr class="currTable_th">
              <th>课程名称</th>
              <th>课程老师</th>
              <th>课程时长</th>
              <th>报名时间</th>
              <th>老师评价</th>
              <th>课程评价</th>
            </tr>
            <tr class="currTable_td" v-for="item in curChoosed">
              <td>{{item.curName}}</td>
              <td>{{item.curTeacher}}</td>
              <td>{{item.curTime}}小时</td>
              <td>{{item.chooseTime}}</td>
              <td>{{item.teacherEva}}分</td>
              <td>
                <el-button v-if="item.StuEvaluate" type="success" disabled round>已评价</el-button>
                <el-button v-else @click="stuEvaluate(item.curID,item.stuID)" type="success" round>评价</el-button>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Head from "../../components/head/Head.vue";
import moment from "moment";
import { myAxios } from "../../request/index.js";

export default {
  data() {
    return {
      headTitle: "个人中心",
      tabNum: 0,
      user: [],
      curChoosed: [],
      changeDetail: false,
      stuEvaluated: false
    };
  },
  async created() {
    if(!sessionStorage.getItem('user')){
      this.$message.error("检测到异常登录，请登录");
      setTimeout(()=>{
        this.$router.push("/");
      },2000)
      return;
    }
    const res = await myAxios(this, {
      type: "getUser",
      user: JSON.parse(sessionStorage.getItem("user"))
    });
    this.user = res;
  },
  methods: {
    async changPwd() {
      var pwd = this.user.password;
      this.$prompt("请输入旧密码", "确认身份", {
        confirmButtonText: "确定",
        cancelButtonText: "取消"
      })
        .then(({ value }) => {
          if (value == pwd) {
            this.$prompt("请输入新密码", "修改密码", {
              confirmButtonText: "确定",
              cancelButtonText: "取消",
              inputPattern: /^\w{6,20}$/,
              inputErrorMessage: "密码格式不正确"
            })
              .then(async ({ value }) => {
                const res = await myAxios(this, {
                  type: "changePwd",
                  user: JSON.parse(sessionStorage.getItem("user")),
                  pwd: value
                });
                if ((res.code = 2020)) {
                  this.user.password = value
                  this.$message({
                    type: "success",
                    message: "密码修改成功 "
                  });
                }
              })
              .catch(() => {
                this.$message({
                  type: "info",
                  message: "取消输入"
                });
              });
          } else {
            this.$message({
              type: "error",
              message: "旧密码输入错误"
            });
          }
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "取消输入"
          });
        });
    },
    changeStuDetail() {
      var phoneReg = /^\d{11}$/;
      var qqReg = /^[0-9]*$/;
      var emailageReg = /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/;
      this.$confirm("确认要修改个人信息吗？", "信息修改", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(async () => {
        if (
          phoneReg.test(this.user.phone) != true ||
          qqReg.test(this.user.qq) != true ||
          emailageReg.test(this.user.email) != true
        ) {
          this.$message({
            message: "信息格式不正确，请检查完再修改",
            type: "error"
          });
          return;
        }
        const res = await myAxios(this, {
          type: "changeStuDetail",
          ...this.user
        });
        if (res.code == 2010) {
          this.$message({
            message: "修改成功",
            type: "success"
          });
        }
      });
    },
    async chooseTab(tabNum) {
      if (tabNum == 2 || tabNum == 3) {
        const res = await myAxios(this, {
          type: "chooseTab",
          user: this.user.ID,
          tabNum: tabNum
        });
        this.curChoosed = res;
      }
    },
    async cancelCur(curID, stuID) {
      this.$confirm(
        "是否要取消课程，如果在超过截止时间取消会扣除10积分",
        "课程取消",
        {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning"
        }
      )
        .then(async () => {
          const res = await myAxios(this, {
            type: "cancelCur",
            curID,
            stuID,
            timeNow : moment().format("YYYY-MM-DD-HH:mm:ss")
          });
          this.curChoosed = res;
          this.$message({
            type: "success",
            message: "取消成功!"
          });
        })
        .catch(() => {});
    },
    async stuEvaluate(curID,stuID){
      this.$prompt('请输入评价内容', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          inputPattern: /^.{1,50}$/,
          inputPlaceholder: '评价内容50字以内',
          inputErrorMessage: '评价内容不能为空且不能超过50个字'
        }).then(async ({ value }) => {
          const res = await myAxios(this, {
            type: "stuEvaluate",
            curID,
            stuID,
            evaluate: value,
            timeNow : moment().format("YYYY-MM-DD-HH:mm:ss")
          });
          const res2 = await myAxios(this, {
            type: "chooseTab",
            user: this.user.ID,
            tabNum: 3
          });
          this.curChoosed = res2;
          this.$message({
            type: 'success',
            message: res
          });

        }).catch(() => {});
    }
  },
  components: {
    Head
  }
};
</script>

<style lang='less'>
.personalStu {
  .stuContent {
    width: 1400px;
    background-color: #fff;
    margin: 30px auto;
    display: flex;
    .tabLeft {
      ul {
        height: 100%;
        width: 235px;
        background-color: #f2f6f7;
        li {
          position: relative;
          width: 210px;
          padding-left: 25px;
          line-height: 85px;
          font-size: 21px;
          color: #b3bdc9;
          text-align: center;
          cursor: pointer;
        }
        .pseudo::after {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 8px;
          height: 85px;
          background-color: #0087cf;
        }
        .personal {
          background: url("../../assets/personal1.png") 50px center no-repeat;
          background-size: 26px 28px;
        }

        .personal1 {
          color: #0087cf !important;
          background: #fff url("../../assets/personal1.png") 50px center no-repeat;
          background-size: 26px 28px;
        }

        .perData {
          background: url("../../assets/perData.png") 50px center no-repeat;
          background-size: 25px 30px;
        }

        .perData1 {
          color: #0087cf !important;
          background: #fff url("../../assets/perData1.png") 50px center
            no-repeat;
          background-size: 25px 30px;
        }

        .curriculum {
          background: url("../../assets/curriculum.png") 47px center no-repeat;
          background-size: 27px 27px;
        }

        .curriculum1 {
          color: #0087cf !important;
          background: #fff url("../../assets/curriculum1.png") 47px center
            no-repeat;
          background-size: 27px 27px;
        }
        .project {
          background: url("../../assets/project.png") 47px center no-repeat;
          background-size: 27px 27px;
        }

        .project1 {
          color: #0087cf !important;
          background: #fff url("../../assets/project1.png") 47px center
            no-repeat;
          background-size: 27px 27px;
        }
      }
    }
    .tabRightRow {
      width: 100%;
      .tabRight {
        width: 100%;
        padding-left: 40px;
        h2 {
          font-size: 34px;
          font-weight: 400;
          color: #000;
          margin: 30px 0;
        }
        .perMain {
          width: 100%;
          .perMain_title {
            font-size: 28px;
            color: #666;
            margin-bottom: 20px;
          }
          .perMain_value {
            height: 80px;
            width: 500px;
            background-color: #f2f6f7;
            font-size: 28px;
            color: #666;
            padding-left: 20px;
            line-height: 80px;
            border-radius: 10px;
          }
        }
        .perBtn {
          margin-top: 30px;
          margin-bottom: 30px;
          .perBtn_pwd {
            margin-left: 50px;
            width: 150px;
            font-size: 18px;
          }
          .backBtn {
            float: right;
            width: 150px;
            margin-right: 200px;
            font-size: 18px;
          }
        }
        .currTable {
          width: 90%;
          margin-bottom: 20px;
          .currTable_th {
            border-bottom: 1px solid #ccc;
            th {
              padding-bottom: 10px;
              color: #909399;
              font-size: 20px;
            }
          }
          .currTable_td {
            border-bottom: 1px solid #ccc;
            td {
              padding-top: 20px;
              padding-bottom: 10px;
              text-align: center;
              color: #666;
            }
          }
          .currTable_td:hover {
            background-color: #f5f7fa;
          }
        }
      }
    }
  }
}
</style>
