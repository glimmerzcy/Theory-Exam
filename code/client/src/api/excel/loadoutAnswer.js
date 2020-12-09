import XLSX from 'xlsx'

export default store => {
  let excel

  try {
    excel = store.infoList.exam_paper.parseToExcel()
  } catch(error){
    window.showToast(error)
    return
  }
  XLSX.writeFile(excel, `${store.infoList.exam_paper.head.title}-${store.state.user_number}-${store.state.userName}-${store.state.college}.xls`)
  // XLSX.writeFile(excel, `${store.infoList.exam_paper.head.title}-${store.state.stu_id}-${store.state.userName}-${store.state.college}.xls`)
}
