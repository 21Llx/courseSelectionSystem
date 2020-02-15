<template>
  <div class="admin">
    <!-- 头部 -->
    <div class="head">
      <img class="logo" src="../../assets/logo.png" alt />
      <p class="head_title">后台中心</p>
      <div class="head_per">
        <p>
          <span>{{adminName}}</span> |
          <span @click="Cancellation">注销</span>
        </p>
      </div>
    </div>
    <!-- 主体 -->
    <div class="adminContent">
      <div class="tabLeft">
        <ul @click="chooseTab">
          <li :class="tabNum == 0 ? 'search1 pseudo' : 'search'" @click="tabNum = 0;">信息搜索</li>
          <li :class="tabNum == 1 ? 'perData1 pseudo' : 'perData'" @click="tabNum = 1;">学生管理</li>
          <li :class="tabNum == 2 ? 'upload1 pseudo' : 'upload'" @click="tabNum = 2">课程上传</li>
          <li :class="tabNum == 3 ? 'curriculum1 pseudo' : 'curriculum'" @click="tabNum = 3">课程管理</li>
        </ul>
      </div>
      <div class="tabRight" v-if="tabNum==0">
        <h2>信息搜索</h2>
        <el-input placeholder="请输入内容" v-model="selectInput" class="input-with-select">
          <el-select v-model="selectTab" slot="prepend" placeholder="请选择">
            <el-option label="学生姓名" value="1"></el-option>
            <el-option label="课程名字" value="2"></el-option>
          </el-select>
          <el-button @click="searchInput" slot="append" icon="el-icon-search"></el-button>
        </el-input>
        <div v-if="search==1">
          <table class="currTable">
            <tr class="currTable_th">
              <th>学生账号</th>
              <th>学生姓名</th>
              <th>学生密码</th>
              <th>学生电话</th>
              <th>学生状态</th>
            </tr>
            <tr class="currTable_td" v-for="(item,index) in searchList">
              <td>{{item.ID}}</td>
              <td>{{item.name}}</td>
              <td>{{item.password}}</td>
              <td>{{item.phone}}</td>
              <td>
                <el-button
                  @click="changeSearchInput(item.ID,item.state,index)"
                  :type="item.state=='正常'?'danger':'primary'"
                  round
                >{{item.state=='正常'?'封禁':'解封'}}</el-button>
              </td>
            </tr>
          </table>
        </div>
        <div v-else-if="search==2">
          <table id="currTable3" class="currTable">
            <tr class="currTable_th">
              <th>课程</th>
              <th>老师</th>
              <th>课程时长</th>
              <th>开始时间</th>
              <th>结束时间</th>
              <th>课程人数</th>
              <th>当前人数</th>
              <th>最低限额</th>
              <th>课程难度</th>
              <th>课程状态</th>
              <th>取消课程</th>
            </tr>
            <tr class="currTable_td" v-for="(item,index) in searchList">
              <td class="tdW100">{{item.curName}}</td>
              <td>{{item.curTeacher}}</td>
              <td>{{item.curDuration}}</td>
              <td class="tdW100">{{item.curBeginTime}}</td>
              <td class="tdW100">{{item.curEndTime}}</td>
              <td>{{item.curPeoples}}</td>
              <td>{{item.curPeople}}</td>
              <td>{{item.least}}</td>
              <td>{{item.evaluate}}</td>
              <td>{{item.curState}}</td>
              <td>
                <el-button @click="searchDeleteCur(item.curID,index)" type="danger" round>取消</el-button>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="tabRight" v-if="tabNum==1">
        <h2>学生管理</h2>
        <table class="currTable">
          <tr class="currTable_th">
            <th>学生账号</th>
            <th>学生姓名</th>
            <th>学生密码</th>
            <th>学生电话</th>
            <th>学生状态</th>
          </tr>
          <tr class="currTable_td" v-for="item in stuTableData">
            <td>{{item.ID}}</td>
            <td>{{item.name}}</td>
            <td>{{item.password}}</td>
            <td>{{item.phone}}</td>
            <td>
              <el-button
                @click="changeState(item.ID,item.state)"
                :type="item.state=='正常'?'danger':'primary'"
                round
              >{{item.state=='正常'?'封禁':'解封'}}</el-button>
            </td>
          </tr>
        </table>
      </div>
      <div class="tabRight" v-else-if="tabNum==2">
        <h2>课程上传</h2>
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
              <el-button type="primary" @click="onSubmit">立即创建</el-button>
              <el-button>取消</el-button>
            </el-form-item>
          </el-form>
        </div>
      </div>
      <div class="tabRight" v-else-if="tabNum==3">
        <h2>课程管理</h2>
        <table id="currTable3" class="currTable">
          <tr class="currTable_th">
            <th>课程</th>
            <th>老师</th>
            <th>课程时长</th>
            <th>开始时间</th>
            <th>结束时间</th>
            <th>课程人数</th>
            <th>当前人数</th>
            <th>最低限额</th>
            <th>课程难度</th>
            <th>课程状态</th>
            <th>取消课程</th>
          </tr>
          <tr class="currTable_td" v-for="item in curTableData">
            <td class="tdW100">{{item.curName}}</td>
            <td>{{item.curTeacher}}</td>
            <td>{{item.curDuration}}</td>
            <td class="tdW100">{{item.curBeginTime}}</td>
            <td class="tdW100">{{item.curEndTime}}</td>
            <td>{{item.curPeoples}}</td>
            <td>{{item.curPeople}}</td>
            <td>{{item.least}}</td>
            <td>{{item.evaluate}}</td>
            <td>{{item.curState}}</td>
            <td>
              <el-button @click="deleteCur(item.curID)" type="danger" round>取消</el-button>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import { myAxios } from "../../request/index.js";

export default {
  data() {
    return {
      adminName: JSON.parse(sessionStorage.getItem("adminName")),
      tabNum: 0,
      selectInput: "",
      selectTab: "",
      search: 0,
      searchList: [],
      stuTableData: [],
      form: {
        type: "uploadCur",
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
      curTableData: []
    };
  },
  async created() {
    if (!sessionStorage.getItem("admin")) {
      this.$message.error("检测到异常登录，请登录");
      setTimeout(() => {
        this.$router.push("adminLogin");
      }, 2000);
      return;
    }
    const res = await myAxios(this, {
      type: "getStuAll"
    });
    this.stuTableData = res;
  },
  methods: {
    Cancellation() {
      this.$confirm("是否要退出登陆", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.$router.push("/adminLogin");
          sessionStorage.removeItem("adminName");
        })
        .catch(() => {});
    },
    async chooseTab() {
      if(this.tabNum != 0){
        this.selectInput = ''
        this.selectTab = ''
        this.searchList = []
      }
      if (this.tabNum == 1) {
        const res = await myAxios(this, {
          type: "getStuAll"
        });
        this.stuTableData = res;
      } else if (this.tabNum == 3) {
        const res = await myAxios(this, {
          type: "getCurAll"
        });
        this.curTableData = res;
      }
    },
    async searchInput() {
      if (this.selectTab == "") {
        this.$message.error("你还未选择要查询的对象");
        return;
      }else if(this.selectTab == "1"){
        this.search = 1
      }else if(this.selectTab == "2"){
        this.search =2
      }
      if (this.selectInput != "") {
        
        const res = await myAxios(this, {
          type: "searchInput",
          selectTab: this.selectTab,
          selectInput: this.selectInput
        });
        this.searchList = res;
      } else {
        this.$message.error("你还未输入内容");
      }
    },
    changeSearchInput(ID, state, index) {
      this.$confirm(`该学生状态为${state}，是否要改变`, "学生状态", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(async () => {
          const res = await myAxios(this, {
            type: "changeState",
            stuID: ID,
            state
          });
          if (state == "正常") {
            this.searchList[index].state = "封禁";
          } else {
            this.searchList[index].state = "正常";
          }
          this.$message({
            message: "状态修改成功",
            type: "success"
          });
        })
        .catch(() => {});
    },
    async searchDeleteCur(ID, index) {
      this.$confirm("是否要取消该课程", "课程取消", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(async () => {
          const res = await myAxios(this, {
            type: "deleteCur",
            curID: ID
          });
          this.searchList.splice(index,1)
          this.$message({
            message: "课程取消成功",
            type: "success"
          });
        })
        .catch(() => {});
    },
    changeState(ID, state) {
      this.$confirm(`该学生状态为${state}，是否要改变`, "学生状态", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(async () => {
          const res = await myAxios(this, {
            type: "changeState",
            stuID: ID,
            state
          });
          this.$message({
            message: "状态修改成功",
            type: "success"
          });
          const res2 = await myAxios(this, {
            type: "getStuAll"
          });
          this.stuTableData = res2;
        })
        .catch(() => {});
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
      this.$confirm(`是否要上传该课程`, "学生状态", {
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
                  message: "课程上传成功",
                  type: "success"
                });
                for (const key in that.form) {
                  that.form[key] = "";
                }
                that.form.least = 1
                that.form.type = "uploadCur"
                delete that.form.beginTime 
                delete that.form.endTime 
              });
          } else {
            this.$message.error("课程填写没有完整");
          }
        })
        .catch(() => {});
    },
    async deleteCur(ID) {
      this.$confirm("是否要取消该课程", "课程取消", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(async () => {
          const res = await myAxios(this, {
            type: "deleteCur",
            curID: ID
          });
          this.curTableData = res;
          this.$message({
            message: "课程取消成功",
            type: "success"
          });
        })
        .catch(() => {});
    }
  }
};
</script>

<style lang='less'>
.admin {
  .head {
    width: 100%;
    min-width: 1400px;
    height: 100px;
    background-color: #fff;
    .logo {
      margin-left: 150px;
      margin-top: 25px;
      float: left;
      cursor: pointer;
    }
    .head_title {
      float: left;
      line-height: 100px;
      font-size: 26px;
      font-weight: 400;
      margin-left: 27px;
    }
    .head_per {
      float: right;
      margin-right: 150px;
      line-height: 100px;
      font-size: 24px;
      p {
        cursor: default;
        span {
          cursor: pointer;
          font-size: 20px;
          color: #666;
        }
        span:hover {
          color: rgb(240, 11, 11);
        }
      }
    }
  }
  .adminContent {
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
        .search {
          background: url("../../assets/search.png") 50px center no-repeat;
          background-size: 26px 28px;
        }

        .search1 {
          color: #0087cf !important;
          background: #fff url("../../assets/search1.png") 50px center no-repeat;
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
        .upload {
          background: url("../../assets/upload.png") 47px center no-repeat;
          background-size: 27px 27px;
        }

        .upload1 {
          color: #0087cf !important;
          background: #fff url("../../assets/upload1.png") 47px center no-repeat;
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
      .el-input-group {
        width: 48%;
        margin: 30px 206px;
        .el-input-group__prepend {
          width: 70px;
        }
      }
      #currTable3 {
        width: 98%;
      }
      .currTable {
        width: 1000px;
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
          width: 100%;
          border-bottom: 1px solid #ccc;
          td {
            padding-top: 20px;
            padding-bottom: 10px;
            text-align: center;
            color: #666;
          }
          .tdW100 {
            width: 100px;
          }
        }
        .currTable_td:hover {
          background-color: #f5f7fa;
        }
      }
      .upload {
        width: 50%;
      }
    }
  }
}
</style>
