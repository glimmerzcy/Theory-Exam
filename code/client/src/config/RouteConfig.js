import Index from '@pages/Index'
import Notice from '@pages/Notice'
import Student from '@pages/Student'
import MoreExam from '@pages/MoreExam'
import College from '@pages/College'
import ExPaper from '@pages/ExPaper'
import SelStu from '@pages/SelectStu/index.jsx'
import ExDetail from '@pages/ExDetail'
import EditPaper from '@pages/EditPaper'
import Exam from '@pages/Exam'
import CollegeLogin from '@pages/CollegeLogin'

export default {
  Index:{
    route:'/home',
    page:Index
  },
  Notice:{
    route:'/notice',
    page:Notice
  },
  Student:{
    route:'/student',
    page:Student
  },
  MoreExam:{
    route:'/student/mine',
    page:MoreExam
  },
  College:{
    route:'/college',
    page:College
  },
  ExPaper:{
    route:'/college/paper',
    page:ExPaper
  },
  SelStu:{
    route:'/college/paper/publish',
    page: SelStu
  },
  ExDetail:{
    route:'/college/paper/datum',
    page:ExDetail
  },
  EditPaper:{
    route:'/college/paper/edit',
    page:EditPaper
  },
  Exam:{
    route:'/exam',
    page:Exam
  },
  CollegeLogin:{
    route:'/login',
    page: CollegeLogin
  }
}
