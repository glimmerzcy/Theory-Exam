// import { BASE_URL } from '@utils/request'
import Route from '@config/RouteConfig'
export default async (store, data) => {
  if (data === 'college')
    window.navigateTo(Route.CollegeLogin.route)
    // window.navigateTo(`${BASE_URL}/api/college/tmp/login/v1?college_code=89`)
  else
    window.navigateTo(Route.Student.route)
    // window.navigateTo(`${BASE_URL}/api/student/tmp/login/v1?stu_id=3017207553`)
}
