import XLSX from 'xlsx'
import { post } from '@utils/request'

const head = ['学号', '姓名', '成绩', '学院', '是否通过']

export default async (store, params) => {
    let { id, title } = params
    let paper_id = id
    let res = await post('api/score/export/v1', { paper_id })

    if (res.status !== 'succeed')
        return
    let
        data = res.data,
        wb = XLSX.utils.book_new(),
        ws = XLSX.utils.aoa_to_sheet([head, ...data.map(v => [v.stu_id, v.real_name, v.score, v.academic, `${v.score < 60 ? '不' : ''}通过`])])
    XLSX.utils.book_append_sheet(wb, ws, '学生成绩表')
    XLSX.writeFile(wb, `${title}-学生成绩表.xls`)
}
