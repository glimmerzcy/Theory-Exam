
import StuTable from '@components/StuTable'

export default store => {
  let addnium = store.infoList.all_stus.filter(stu => StuTable.isShow(stu,true))
  store.infoList.stu_table = [...new Set([...store.infoList.stu_table,...addnium])]
}