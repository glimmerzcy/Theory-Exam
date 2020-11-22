import XLSX from 'xlsx'

export default store => {
  let excel
  
  try {
    excel = store.infoList.paper.parseToExcel()
  } catch(error){
    window.showToast(error)
    return
  }
  XLSX.writeFile(excel, `${store.infoList.paper.head.title}.xls`)
}
