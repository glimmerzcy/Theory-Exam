import XLSX from 'xlsx'
import { get } from '../../utils/request'

const head = ['学号', '姓名', '成绩', '是否通过']

export default async (store, paper_id) => {
  let res = await get('students/transcript', { paper_id })
  if (!res)
    return
  let
    wb = XLSX.utils.book_new(),
    ws = XLSX.utils.aoa_to_sheet([head, ...res.map(v => [v.stu_id, v.real_name, v.score, `${v.score < 60 ? '不' : ''}通过`])])
  XLSX.utils.book_append_sheet(wb, ws, '学生成绩表')
  XLSX.writeFile(wb, '学生成绩表.xls')
}