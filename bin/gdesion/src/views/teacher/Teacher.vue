<template>
  <div class="teacher">
    <TeaHead></TeaHead>
    <div class="teaContent">
      <div class="tabLeft">
        <ul @click="getCur">
          <li
            :class="tabNum == 0 ? 'personal1 pseudo' : 'personal'"
            @click="tabNum = 0;changeDetail=false"
          >教师信息</li>
          <li :class="tabNum == 1 ? 'perData1 pseudo' : 'perData'" @click="tabNum = 1;">未开始课程</li>
          <li :class="tabNum == 2 ? 'curriculum1 pseudo' : 'curriculum'" @click="tabNum = 2">已开始课程</li>
          <li :class="tabNum == 3 ? 'project1 pseudo' : 'project'" @click="tabNum = 3">已结束课程</li>
          <li :class="tabNum == 4 ? 'upload1 pseudo' : 'upload'" @click="tabNum = 4">课程申请</li>
          <li :class="tabNum == 5 ? 'apply1 pseudo' : 'apply'" @click="tabNum = 5">申请记录</li>
        </ul>
      </div>
      <div class="tabRight" v-if="tabNum==0">
        <div v-if="changeDetail">
          <h2>修改个人资料</h2>
          <div class="perMain">
            <p class="perMain_title">姓名:</p>
            <input class="perMain_value" v-model="teacher.name" />
          </div>
          <div class="perMain">
            <p class="perMain_title">电话:</p>
            <input class="perMain_value" v-model="teacher.phone" />
          </div>
          <div class="perBtn">
            <el-button type="info" class="perBtn_pwd" plain @click="changPwd">修改密码</el-button>
            <el-button class="perBtn_pwd" type="primary" plain @click="changDetail">确认修改</el-button>
            <el-button type="danger" class="backBtn" plain @click="changeDetail=false">返回个人资料</el-button>
          </div>
        </div>
        <div class="stuDetail" v-else>
          <h2>个人资料</h2>
          <div class="perMain">
            <p class="perMain_title">账号:</p>
            <p class="perMain_value">{{teacher.ID}}</p>
          </div>
          <div class="perMain">
            <p class="perMain_title">姓名:</p>
            <p class="perMain_value">{{teacher.name}}</p>
          </div>
          <div class="perMain">
            <p class="perMain_title">联系电话:</p>
            <p class="perMain_value">{{teacher.phone}}</p>
          </div>
          <div class="perBtn">
            <el-button class="perBtn_detail" type="info" plain @click="changeDetail=true">修改个人信息</el-button>
          </div>
        </div>
      </div>
      <div class="tabRight" v-else-if="tabNum==1">
        <h2>未开始课程</h2>
        <table class="currTable">
          <tr class="currTable_th">
            <th>课程名称</th>
            <th>课程时长</th>
            <th>课程开始时间</th>
            <th>课程总人数</th>
            <th>已报名人数</th>
          </tr>
          <tr class="currTable_td" v-for="item in teacherCur">
            <td>{{item.curName}}</td>
            <td>{{item.curDuration}}小时</td>
            <td>{{item.curEndTime}}</td>
            <td>{{item.curPeoples}}</td>
            <td>{{item.curPeople}}</td>
          </tr>
        </table>
      </div>
      <div class="tabRight" v-else-if="tabNum==2">
        <h2>已开始课程</h2>
        <table class="currTable">
          <tr class="currTable_th">
            <th>课程名称</th>
            <th>课程时长</th>
            <th>课程开始时间</th>
            <th>课程人数</th>
            <th>结束课程</th>
          </tr>
          <tr class="currTable_td" v-for="item in teacherCur">
            <td>{{item.curName}}</td>
            <td>{{item.curDuration}}小时</td>
            <td>{{item.curEndTime}}</td>
            <td>{{item.curPeople}}</td>
            <td>
              <el-button @click="endCur(item.curID)" type="primary" round>结束课程</el-button>
            </td>
          </tr>
        </table>
      </div>
      <div class="tabRight" v-else-if="tabNum==3">
        <div v-if="!evaluated">
          <h2>已结束课程</h2>
          <table class="currTable">
            <tr class="currTable_th">
              <th>课程名称</th>
              <th>课程时长</th>
              <th>课程开始时间</th>
              <th>课程人数</th>
              <th>学生评价</th>
            </tr>
            <tr class="currTable_td" v-for="item in teacherCur">
              <td>{{item.curName}}</td>
              <td>{{item.curDuration}}小时</td>
              <td>{{item.curEndTime}}</td>
              <td>{{item.curPeople}}</td>
              <td>
                <el-button v-if="item.flag" type="primary" round disabled>已评价</el-button>
                <el-button v-else @click="evaluateStu(item.curID)" type="primary" round>学生打分</el-button>
              </td>
            </tr>
          </table>
        </div>
        <div v-else>
          <h2>学生打分</h2>
          <table class="stuEva">
            <tr v-for="item in stuName" :key="item.ID">
              <td class="stuEva_name">{{item.name}}</td>
              <td>
                <el-radio-group v-model="item.radio">
                  <el-radio :label="-3" border>不及格</el-radio>
                  <el-radio :label="3" border>及格</el-radio>
                  <el-radio :label="5" border>良</el-radio>
                  <el-radio :label="9" border>优</el-radio>
                </el-radio-group>
              </td>
            </tr>
          </table>
          <el-button class="sureEva_stu" @click="sureEvaed" type="primary" plain>确认评价</el-button>
          <el-button class="evaBack_stu" type="danger" plain @click="evaluated=false">返回课程</el-button>
        </div>
      </div>
      <div class="tabRight" v-else-if="tabNum==4">
        <h2>课程申请</h2>
        <div class="upload">
          <el-form ref="form" :model="form" label-width="80px">
            <el-form-item label="课程名称">
              <el-input v-model="form.curName"></el-input>
            </el-form-item>
            <el-form-item label="教师ID">
              <el-input v-model="form.teacherID"></el-input>
            </el-form-item>
            <el-form-item label="教师姓名">
              <el-input v-model="form.teacherName"></el-input>
            </el-form-item>
            <el-form-item label="课程时长">
              <el-input-number v-model="form.curDuration" :precision="1" :step="0.1" :max="1000"></el-input-number>
            </el-form-item>
            <el-form-item label="开始时间">
              <el-date-picker
                v-model="form.times"
                type="datetimerange"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="截止日期"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label="允许人数">
              <el-input-number v-model="form.curPeoples" :min="1" :max="1000"></el-input-number>
            </el-form-item>
            <el-form-item label="课程图片">
              <input id="inputFile" type="file" @change="getImgFile" value="选择文件" />
            </el-form-item>
            <el-form-item label="最低限额">
              <el-input-number v-model="form.least" :min="0" :max="1000"></el-input-number>
            </el-form-item>
            <el-form-item label="课程难度">
              <el-input-number v-model="form.evaluate" :precision="1" :step="0.1" :max="1000"></el-input-number>
            </el-form-item>
            <el-form-item label="课程详情">
              <el-input
                type="textarea"
                :autosize="{ minRows: 2, maxRows: 6}"
                placeholder="请输入内容"
                v-model="form.curDetail"
              ></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="onSubmit">立即申请</el-button>
            </el-form-item>
          </el-form>
        </div>
      </div>
      <div class="tabRight" v-else-if="tabNum==5">
        <h2>申请记录</h2>
          <table class="currTable">
            <tr class="currTable_th">
              <th>课程名称</th>
              <th>课程时长</th>
              <th>课程开始时间</th>
              <th>课程人数</th>
              <th>申请情况</th>
            </tr>
            <tr class="currTable_td" v-for="item in applys">
              <td>{{item.curName}}</td>
              <td>{{item.curDuration}}小时</td>
              <td>{{item.curEndTime}}</td>
              <td>{{item.curPeoples}}</td>
              <td v-if="item.state==0">正在申请</td>
              <td v-if="item.state==1">申请成功</td>
              <td v-if="item.state==2">申请失败：{{item.applyfail}}</td>

            </tr>
          </table>
      </div>
    </div>
  </div>
</template>

<script>
import TeaHead from "../../components/teaHead/TeaHead.vue";
import moment from "moment";
import { myAxios } from "../../request/index.js";

export default {
  data() {
    return {
      headTitle: "教师中心",
      teacher: {},
      tabNum: 0,
      changeDetail: false,
      teacherCur: [],
      stuName: [],
      dialogStuName: false,
      radio: 3,
      evaluated: false,
      curIDNow: 1,
      endCurIndex: 0,
      form: {
        type: "applyCur",
        curName: "",
        teacherID: "",
        teacherName: "",
        curDuration: 1,
        times: [],
        curPeoples: 1,
        image: "",
        least: 1,
        evaluate: 0,
        curDetail: ""
      },
      applys: []
    };
  },
  async created() {
    if (!sessionStorage.getItem("teacher")) {
      this.$message.error("检测到异常登录，请登录");
      setTimeout(() => {
        this.$router.push("/");
      }, 2000);
      return;
    }
    const res = await myAxios(this, {
      type: "getTeacher",
      teacherID: JSON.parse(sessionStorage.getItem("teacher"))
    });
    this.teacher = res;
  },
  methods: {
    changPwd() {
      var pwd = this.teacher.password;
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
                  type: "teacherChangePwd",
                  teacher: JSON.parse(sessionStorage.getItem("teacher")),
                  pwd: value
                });
                if ((res.code = 3000)) {
                  this.teacher.password = value;
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
    changDetail() {
      var phoneReg = /^\d{11}$/;
      this.$confirm("确认要修改个人信息吗？", "信息修改", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(async () => {
        if (
          phoneReg.test(this.teacher.phone) != true ||
          this.teacher.name == ""
        ) {
          this.$message({
            message: "信息格式不正确，请检查完再修改",
            type: "error"
          });
          return;
        }
        const res = await myAxios(this, {
          type: "changeTeaDetail",
          ID: sessionStorage.getItem("teacher"),
          name: this.teacher.name,
          phone: this.teacher.phone
        });
        if (res.code == 3010) {
          this.$message({
            message: "修改成功",
            type: "success"
          });
          this.changeDetail = false;
        }
      });
    },
    async getCur() {
      if(this.tabNum == 5){
        const res = await myAxios(this, {
          type: "getApply",
          teacherID: this.teacher.ID,
        });
        this.applys = res;
        return;
      }
      if (this.tabNum != 0) {
        const res = await myAxios(this, {
          type: "getTeaCur",
          teacherID: this.teacher.ID,
          tabNum: this.tabNum,
          timeNow: moment().format("YYYY-MM-DD-HH:mm:ss")
        });
        if (this.tabNum == 3) {
          let curIDs = res.map(obj => {
            return obj.curID;
          });
          const res2 = await myAxios(this, {
            type: "sureEvaStu",
            curIDs
          });
          for (let i = 0; i < res.length; i++) {
            var flag = res2[i].every(item => {
              return item.teacherEva != 0;
            });
            res[i].flag = flag;
          }
          this.teacherCur = res;
          return;
        }
        this.teacherCur = res;
      }
    },
    endCur(ID) {
      this.$confirm("确定课程已结束？", "课程结束", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(async () => {
          const res = await myAxios(this, {
            type: "endCur",
            curID: ID,
            teacherID: this.teacher.ID,
            timeNow: moment().format("YYYY-MM-DD-HH:mm:ss")
          });
          this.teacherCur = res;
        })
        .catch(() => {});
    },
    async evaluateStu(ID) {
      const res = await myAxios(this, {
        type: "getStuName",
        curID: ID
      });
      this.dialogStuName = true;
      this.curIDNow = ID;
      res.map(v => (v.radio = 0));
      this.stuName = res;
      this.evaluated = true;
      this.endCurIndex = this.teacherCur.findIndex(item => {
        return item.curID == ID;
      });
    },
    async sureEvaed() {
      var flag = this.stuName.every(item => {
        return item.radio != 0;
      });
      if (!flag) {
        this.$message.error("你还有学生没有评价");
        return;
      }
      const res = await myAxios(this, {
        type: "teacherEvaStu",
        stuName: this.stuName,
        curIDNow: this.curIDNow
      });
      if (res == true) {
        this.$confirm("确认提交评价吗", "学生评价", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning"
        })
          .then(() => {
            this.evaluated = false;
            this.teacherCur[this.endCurIndex].flag = true;
            this.$message({
              type: "success",
              message: "评价成功!"
            });
          })
          .catch(() => {});
      }
    },
    getImgFile(e) {
      if (e.target.files[0].type == "image/jpeg") {
        this.form.image = e.target.files[0];
      } else {
        this.$message.error("上传的图片为jpg格式");
        var inputFile = document.getElementById("inputFile");
        inputFile.value = "";
      }
    },
    onSubmit() {
      var that = this;
      var flag = true;
      for (const key in this.form) {
        if (this.form[key] == "" || this.form[key] == null) {
          flag = false;
          break;
        }
      }
      this.$confirm(`是否要上传该课程`, "课程上传", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          if (flag) {
            var formData = new FormData();
            this.form.beginTime = moment(this.form.times[0]).format(
              "YYYY-MM-DD-HH:mm:ss"
            );
            this.form.endTime = moment(this.form.times[1]).format(
              "YYYY-MM-DD-HH:mm:ss"
            );
            for (const key in this.form) {
              formData.append(key, this.form[key]);
            }
            that.$axios
              .post(
                "http://127.0.0.1/courseSelectionSystem/src/index.php",
                formData,
                {
                  headers: {
                    "Content-Type": "multipart/form-data"
                  }
                }
              )
              .then(function(res) {
                that.$message({
                  message: "课程进入申请",
                  type: "success"
                });
                for (const key in that.form) {
                  that.form[key] = "";
                }
                that.form.least = 1
                that.form.type = "applyCur"
                delete that.form.beginTime 
                delete that.form.endTime 
              });
          } else {
            this.$message.error("课程填写没有完整");
          }
        })
        .catch(() => {});
    },
  },
  components: {
    TeaHead
  }
};
</script>

<style lang='less'>
.teaContent {
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
        background: url("../../assets/personal.png") 50px center no-repeat;
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
        background: #fff url("../../assets/perData1.png") 50px center no-repeat;
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
        background: #fff url("../../assets/project1.png") 47px center no-repeat;
        background-size: 27px 27px;
      }
      .upload {
        background: url("../../assets/upload.png") 47px center no-repeat;
        background-size: 27px 27px;
      }

      .upload1 {
        color: #0087cf !important;
        background: #fff url("../../assets/upload1.png") 47px center no-repeat;
        background-size: 27px 27px;
      }
      .apply {
        background: url("../../assets/apply.png") 47px center no-repeat;
        background-size: 27px 27px;
      }

      .apply1 {
        color: #0087cf !important;
        background: #fff url("../../assets/apply1.png") 47px center no-repeat;
        background-size: 27px 27px;
      }
    }
  }
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
      .perBtn_detail {
        margin-left: 50px;
        width: 150px;
        font-size: 18px;
      }
      .perBtn_pwd {
        width: 150px;
        margin-left: 147px;
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
    .is-bordered {
      width: 110px;
    }
    .stuEva {
      tr {
        .stuEva_name {
          width: 100px;
          text-align: center;
          font-size: 20px;
          color: #333;
        }
        td {
          padding: 20px 0;
        }
      }
    }
    .sureEva_stu {
      margin: 30px 40px;
    }
    .evaBack_stu {
      margin: 30px 40px;
    }
    .upload {
      width: 50%;
    }
  }
}
</style>
