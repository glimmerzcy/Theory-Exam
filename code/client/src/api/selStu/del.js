
import StuTable from '../../components/StuTable'

export default store => {
  let delnium = store.infoList.all_stus.filter(stu => StuTable.isShow(stu,true))
  delnium.forEach(del => store.infoList.stu_table.remove(del))
}