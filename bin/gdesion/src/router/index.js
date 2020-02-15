import Vue from 'vue'
import VueRouter from 'vue-router'
import IndexStu from '../views/indexStu/IndexStu.vue'
import Login from '../views/login/Login.vue'
import Register from '../views/register/Register.vue'
import PersonalStu from '../views/personalStu/PersonalStu.vue'
import ClassDetail from '../views/classDetail/ClassDetail.vue'
import Teacher from '../views/teacher/Teacher.vue'
import AdminLogin from '../views/adminLogin/AdminLogin.vue'
import Admin from '../views/admin/Admin.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/indexStu',
    name: 'indexStu',
    component: IndexStu
  },
  {
    path: '/',
    name: 'login',
    component: Login
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
  {
    path: '/personalStu',
    name: 'personalStu',
    component: PersonalStu
  },
  {
    path: '/classDetail',
    name: 'classDetail',
    component: ClassDetail
  },
  {
    path: '/teacher',
    name: 'teacher',
    component: Teacher
  },
  {
    path: '/adminLogin',
    name: 'adminLogin',
    component: AdminLogin
  },
  {
    path: '/admin',
    name: 'admin',
    component: Admin
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
