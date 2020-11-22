import { get } from '../../utils/request'

const getFilter = (stus, key) => [...new Set(stus.map(stu => stu[key]))]

export default async store => {
  let res = await get('user/students')
  store.infoList.all_stus = res.map(stu => [
    stu.edu_level,
    stu.grade,
    stu.academic,
    stu.profession,
    stu.class,
    stu.real_name,
    stu.stu_id,
  ])
  store.infoList.stu_table = store.infoList.all_stus
  store.infoList.stu_table_filter = [
    getFilter(res, 'edu_level'),
    getFilter(res, 'grade'),
    getFilter(res, 'academic'),
    getFilter(res, 'profession'),
    getFilter(res, 'class'),
    [],
    [],
  ]
}