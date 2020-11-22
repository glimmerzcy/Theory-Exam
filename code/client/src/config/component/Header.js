import Store from '../../utils/Store'
import RouteConfig from '../RouteConfig'

export default {
  unknown: [
      {
          text:() => '学生登录',
          onClick: Store.request('log/in', 'student')
      },
      {
          text:() => '学院登录',
          onClick: Store.request('log/in', 'college')
      },
      { display: 'none' }
  ],
  student: [
      {
          text:() => '我的考试',
          onClick: () => window.navigateTo(RouteConfig.MoreExam.route)
      },
      {
          text:() => Store.state.userName,
          onClick: () => window.navigateTo('~https://i.twtstudio.com')
      },
      { display: 'block' }
  ],
  college: [
      {
          text:() => '试卷',
          onClick: () => window.navigateTo(RouteConfig.ExPaper.route)
      },
      {
          text:() => Store.state.userName,
          onClick: () => window.navigateTo('~https://theory-new.twt.edu.cn/index.php/admin/notice')
      },
      { display: 'block' }
  ]
}
