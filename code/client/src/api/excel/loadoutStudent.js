import XLSX from 'xlsx'
import { sel_stu } from '../../config/component/StuTable'

export default store => {
  let excel
  const parseToExcel = () => {
    let wb = XLSX.utils.book_new()
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(
        [
            sel_stu,
            ...store.infoList.stu_table
    ]), 'Sheet1')
    return wb
  }

  try {
    excel = parseToExcel()
  } catch(error){
    window.showToast(error)
    return
  }
  XLSX.writeFile(excel, `考试学生表.xls`)
}
