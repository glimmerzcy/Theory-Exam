import { get } from '@utils/request'

export default async store => {
  let res = await get('api/colleges/v1')
  let { data } = res
  let college = Object.keys(data).reduce((acc, val) => {
    acc.push({
        id: data[val],
        name: val
    })
    return acc
  }, [])
store.infoList.all_college = college

}
